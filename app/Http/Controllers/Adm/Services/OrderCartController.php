<?php

namespace App\Http\Controllers\Adm\Services;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCart;
use App\Models\Storage;
use App\Models\Product;
use App\Models\ListItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrderCartController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe tela com dados do cliente mais os itens do pedido
     *
     * @param string $id
     * @return object
     */
    public function showCart(string $orderId = null): object
    {
        $decOrderId = Crypt::decryptString($orderId);
        
//        dd($decOrderId);
        
        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Registrando pedido',
            'dataOrder' => Order::where('id', $decOrderId)->first(),
            'itemList' => $this->getItensByOrder($decOrderId),
            'orderId' => $orderId
        ];

        session()->flash('order_id', $orderId);

        return view('adm.service.orderCart.showCart', $this->dataView);
    }

    /**
     * Função que exibe os itens disponíveis para por no carrinho, mais formulário de busca
     * 
     * @param Request $request
     * @param string $orderId
     * @return object
     */
    public function searchingItem(Request $request, string $orderId = null): object
    {
        session()->keep('order_id');
        
        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Registrando pedido',
            'orderId' => $orderId,
        ];

        if ($request->has('description') && $request->input('description') !== null) {
            $this->runValidation($request);

            $description = $request->input('description');

            $this->dataView['storageList'] = $this->findProductByDescription($description);
            $this->dataView['description'] = $description;
            $this->dataView['fromStorage'] = false;
        } else {
            $this->dataView['storageList'] = Storage::paginate(10);
            $this->dataView['fromStorage'] = true;
        }

        return view('adm.service.orderCart.searchingItem', $this->dataView);
    }

    /**
     * Função que adiciona item no carrinho
     * 
     * @param Request $request
     * @param string $storageId
     * @return object
     */
    public function addItem(Request $request, string $storageId = null): object
    {

        session()->keep('order_id');

        if ($this->checkItemInCart($storageId)) {
            return redirect()->back()->with('warning', 'Este item já existe no carrinho, por favor excluí-lo antes!');
        }

        if (!$request->has('quantity')) {
            $this->dataView = [
                'title' => 'ADM - Serviços',
                'dashboard' => 'Registrando pedido',
                'orderId' => session()->get('order_id'),
                'storageId' => $storageId
            ];

            return view('adm.service.orderCart.addItem', $this->dataView);
        }

        $this->runValidationAddItem($request);

        $dataForm = $request->except(['_token']);
        $dataForm['storage_id'] = Crypt::decryptString($storageId);
        $dataForm['order_id'] = Crypt::decryptString(session()->get('order_id'));

        if (!$this->checkStorageAvailable($dataForm)) {
            return redirect()->back()->with('warning', 'Não há estoque suficiente do produto!');
        }


        if (!$this->persistDataItem($dataForm)) {
            return redirect()->route('employee.list')->with('danger', 'Operação temporáriamente indisponível, contacte o administrador');
        }

        return redirect()->route('order.show-cart', session()->get('order_id'))->with('success', 'Item adicionado com sucesso!');
    }

    /**
     * Função que remove item do carrinho
     * 
     * @param string $itemId
     * @return object
     */
    public function removeItem(string $itemId = null): object
    {
        session()->keep('order_id');

        $decItemId = Crypt::decryptString($itemId);

        $this->findCartItemById($decItemId);

        if (!$this->removeDataItem($decItemId)) {
            return redirect()->back()->with('darnger', 'Operação temporáriamente indisponível, contacte o administrador');
        }

        return redirect()->route('order.show-cart', session()->get('order_id'))->with('success', 'Item removido do carrinho!');
    }

    /**
     * Função para verificar se já existe o item no carrinho
     * 
     * @param string $storageId
     * @return null|object
     */
    private function checkItemInCart(string $storageId): null|object
    {
        $decStorageId = Crypt::decryptString($storageId);
        $decOrderId = Crypt::decryptString(session()->get('order_id'));

        return ListItem::where('order_id', $decOrderId)->where('storage_id', $decStorageId)->first();
    }

    /**
     * Verifica se há quantidade de item deisponível antes de adicionar no carrinho
     * 
     * @param array $dataItem
     * @return bool
     */
    private function checkStorageAvailable(array $dataItem): bool
    {
        $storageItem = Storage::where('id', $dataItem['storage_id'])->first();

        if ($storageItem->quantity < $dataItem['quantity']) {
            return false;
        }

        return true;
    }

    /**
     * Persisti os itens do carrinho no banco de dados
     * 
     * @param array $dataItem
     * @return bool
     */
    private function persistDataItem(array $dataItem): bool
    {
        try {
            DB::transaction(function () use ($dataItem) {
                OrderCart::create($dataItem);
                $storageItem = Storage::where('id', $dataItem['storage_id'])->first();
                $storageItem->quantity = $storageItem->quantity - $dataItem['quantity'];
                $storageItem->save();
            });

            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
            return false;
        }
    }

    /**
     * Remove item do carrinho
     * 
     * @param int $itemId
     * @return bool
     */
    private function removeDataItem(int $itemId): bool
    {
        try {
            $cartItem = OrderCart::where('id', $itemId)->first();
            $storageItem = Storage::where('id', $cartItem->storage_id)->first();
            $storageItem->quantity = $storageItem->quantity + $cartItem->quantity;
            $storageItem->save();
            $cartItem->delete();

            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
            return false;
        }
    }

    /**
     * Exibe os itens de um pedido
     * 
     * @param int $decOrderId
     * @return object
     */
    private function getItensByOrder(int $decOrderId): object
    {
        return DB::table('order_carts')
                        ->join('storages', 'order_carts.storage_id', '=', 'storages.id')
                        ->join('products', 'storages.product_id', '=', 'products.id')
                        ->select('products.description', 'order_carts.id', 'order_carts.order_id', 'order_carts.quantity', 'storages.id as storage_id')
                        ->where('order_carts.order_id', $decOrderId)
                        ->paginate(10);
    }

    /**
     * Faz a validação do formulário de busca por produto
     *
     * @param Request $request
     * @return void
     */
    private function runValidation(Request $request): void
    {
        $request->validate([
            'description' => 'nullable|string|max:100|min:3',
                ], [
            'description.max' => 'Este campo não atende aos requisitos mínimos.',
            'description.min' => 'Este campo não atende aos requisitos mínimos.',
            'description.string' => 'Este campo não atende aos requisitos mínimos.',
        ]);
    }

    /**
     * Faz a validação do formulário de adicionar quantidade do item
     *
     * @param Request $request
     * @return void
     */
    private function runValidationAddItem(Request $request): void
    {
        $request->validate([
            'quantity' => 'required|numeric|digits_between:1,2',
                ], [
            'quantity.required' => 'Este campo é obrigatório.',
            'quantity.numeric' => 'Este campo não atende aos requisitos mínimos.',
            'quantity.digits_between' => 'Este campo não atende aos requisitos mínimos.',
        ]);
    }

    /**
     * Realiza busca do produto pela descrição
     *
     * @param string $description
     * @return void
     */
    private function findProductByDescription(string $description)
    {
        return Product::where('description', 'like', '%' . $description . '%')->paginate(10);
    }

    /**
     * Retorna dados de um item do carrinho
     * 
     * @param int $idItem
     * @return null|object
     */
    private function findCartItemById(int $idItem): null|object
    {
        return OrderCart::findOrFail($idItem);
    }

}

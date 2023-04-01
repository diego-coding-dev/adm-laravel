<?php

namespace App\Http\Controllers\Adm\Services;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Client;
use App\Models\OrderCart;
use App\Models\ListItem;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe a lista com os pedidos registrados
     *
     * @return object
     */
    public function index(): object
    {
        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Pedidos',
            'orderList' => Order::where('is_settled', true)->paginate(10)
        ];

        return view('adm.service.order.index', $this->dataView);
    }

    /**
     * Exibe tela com os clientes cadastrados, mais um formulário de busca
     *
     * @param Request $request
     * @return object
     */
    public function searchingClient(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Registrando pedido'
        ];

        if (!$request->has('name') || $request->input('name') === null) {
            $this->dataView['clientList'] = Client::paginate(10);
        } else {

            $this->runValidation($request);

            $name = $request->query('name');
            
            $this->dataView['name'] = $name;
            $this->dataView['clientList'] = $this->findCLientByName($name);
        }

        return view('adm.service.order.searchingClient', $this->dataView);
    }

    /**
     * Registra um novo pedido e redireciona para a tela de itens para o pedido
     *
     * @param string $id
     * @return object
     */
    public function register(string $id): object
    {
        $clientId = Crypt::decryptString($id);

        $settledOrder = $this->findNotSettledOrderByClient($clientId);

        if ($settledOrder) {
            return redirect()->route('order.show-cart', Crypt::encryptString($settledOrder->id))->with('warning', 'Existe um pedido em aberto!');
        }

        $dataOrder = Order::create([
                    'client_id' => $clientId,
                    'employee_id' => Auth::id(),
                    'register' => bin2hex(random_bytes(5))
        ]);

        return redirect()->route('order.show-cart', Crypt::encryptString($dataOrder->id))->with('success', 'Pedido aberto com sucesso!');
    }

    /**
     * Exibe a tela para confirmar o pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function confirm(string $orderId): object
    {
        $decOrderId = Crypt::decryptString($orderId);

        if (!$this->checkOrderCartIsEmpty($decOrderId)) {
            return redirect()->route('order.show-cart', $orderId)->with('warning', 'O carrinho está vazio!');
        }

        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Confirmar',
            'orderId' => $orderId,
        ];

        return view('adm.service.order.confirm', $this->dataView);
    }

    /**
     * Finaliza o pedido
     * 
     * @param type $orderId
     * @return object
     */
    public function finishOrder($orderId = null): object
    {
        if (!$this->persistFinishData($orderId)) {
            abort(404, 'Operação não realizada, contacte o administrador');
        }

        session()->forget('order_id');

        return redirect()->route('order.list')->with('success', 'Pedido registrado com sucesso!');
    }

    /**
     * Exibe tela para confirmar o cancelamento do pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function confirmCancel(string $orderId = null): object
    {
        $decOrderId = Crypt::decryptString($orderId);

        $oder = $this->checkIfOrderExists($decOrderId);

        $this->dataView = [
            'title' => 'ADM - Serviços',
            'dashboard' => 'Confirmar Cancelamento',
            'orderId' => $orderId,
        ];

        return view('adm.service.order.confirmCancel', $this->dataView);
    }

    /**
     * Funão que cancela o pedido
     * 
     * @param string $orderId
     * @return object
     */
    public function cancelOrder(string $orderId = null): object
    {
        if (!$this->persistCancelData($orderId)) {
            abort(404, 'Operação não realizada, entre em contato com o administrador.');
        }

        session()->forget('order_id');

        return redirect()->route('order.list')->with('success', 'Pedido cancelado com sucesso!');
    }

    /**
     * Persiste dados referentes ao cancelamento de um pedido
     * 
     * @param string $orderId
     * @return bool
     */
    private function persistCancelData(string $orderId = null): bool
    {
        $decOrderId = Crypt::decryptString($orderId);

        try {

            if (!$this->checkOrderCartIsEmpty($decOrderId)) {
                Order::where('id', $decOrderId)->delete();

                return true;
            }

            DB::transaction(function () use ($decOrderId) {

                $itemList = OrderCart::where('order_id', $decOrderId)->get();

                foreach ($itemList as $item) {
                    $storage = Storage::where('id', $item->storage_id)->first();

                    $storage->quantity = $storage->quantity + $item->quantity;
                    $storage->save();
                }

                OrderCart::where('order_id', $decOrderId)->delete();
                Order::where('id', $decOrderId)->update([
                    'is_canceled' => true
                ]);
            });

            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
            return false;
        }


        dd($this->checkIfOrderExists($decOrderId));
    }

    /**
     * Persiste os dados do carrinho de compras na tabela LIST_ITENS e remove da tabela temporária ORDER_CARTs
     * 
     * @param int $orderId
     * @return bool
     */
    private function persistFinishData(string $orderId): bool
    {
        try {
            $decOrderId = Crypt::decryptString($orderId);

            DB::transaction(function () use ($decOrderId) {
                $order = Order::where('id', $decOrderId)->first();
                $CartItem = OrderCart::where('order_id', $decOrderId)->get();

                foreach ($CartItem as $item) {
                    ListItem::create([
                        'storage_id' => $item->storage_id,
                        'order_id' => $item->order_id,
                        'quantity' => $item->quantity
                    ]);
                }

                $order->is_settled = true;
                $order->save();

                OrderCart::where('order_id', $decOrderId)->delete();
            });

            return true;
        } catch (Exception $exc) {
            // echo $exc->getTraceAsString();
            return false;
        }
    }

    /**
     * Verifica se existe pelo menos um item no carrinho de um pedido
     * 
     * @param int $orderId
     * @return null|object
     */
    private function checkOrderCartIsEmpty(int $orderId): null|object
    {
        return OrderCart::where('order_id', $orderId)->first();
    }

    /**
     * Função que valida o campo de busca pelo cliente
     *
     * @param Request $request
     * @return void
     */
    private function runValidation(Request $request): void
    {
        $request->validate([
            'name' => 'nullable|string|digits_between:1,2',
                ], [
            'name.string' => 'Este campo não atende aos requisitos mínimos.',
            'name.digits_between' => 'Este campo não atende aos requisitos mínimos.',
        ]);
    }

    /**
     * Verifica se existe algum pedido em aberto do cliente
     *
     * @param integer $clientId
     * @return null|object
     */
    private function findNotSettledOrderByClient(int $clientId): null|object
    {
        return Order::where('client_id', $clientId)->where('is_settled', false)->first();
    }

    /**
     * Função que realize busca no banco de dados com base no nome
     *
     * @param string $name
     * @return void
     */
    private function findCLientByName(string $name)
    {
        return Client::where('name', 'like', '%' . $name . '%')->paginate(10);
    }

    private function checkIfOrderExists(int $orderId)
    {
        return Order::findOrFail($orderId);
    }

}

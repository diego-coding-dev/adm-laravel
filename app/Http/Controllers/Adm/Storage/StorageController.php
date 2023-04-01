<?php

namespace App\Http\Controllers\Adm\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class StorageController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Função que exibe a tela com lista de produtos no estoque
     *
     * @return object
     */
    public function listSearch(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque',
            'dashboard' => 'Estoque',
            'storageList' => Storage::paginate(10)
        ];

        if (!$request->has('description') || $request->input('description') === null) {
            $this->dataView['storageList'] = $this->getStorageByDescription(null);
        } else {
            $this->runValidation($request);

            $description = $request->query('description');

            $this->dataView['description'] = $description;
            $this->dataView['storageList'] = $this->getStorageByDescription($description);
        }

        return view('adm.storage.storage.listSearch', $this->dataView);
    }

    /**
     * Função que exibe a tela para adicionar produtos no estoque
     *
     * @param string $id
     * @return object
     */
    public function adding(string $id): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque',
            'dashboard' => 'Adicionando produto ao estoque',
            'storage' => $this->findOrFailById(Crypt::decryptString($id))
        ];

        return view('adm.storage.storage.add', $this->dataView);
    }

    public function add(Request $request)
    {
        $request->validate([
            'quantity' => 'required|regex:/^\d*[1-9]\d*$/|between:1,4'
                ], [
            'quantity.required' => 'Este campo é obrigatório!',
            'quantity.regex' => 'Este campo não atende aos requisitos mínimos.',
            'quantity.between' => 'Este campo não atende aos requisitos mínimos.',
        ]);

        $dataForm = $request->except(['_token']);

        if (!$this->persistDataStorage($dataForm)) {
            return redirect()->route('storage.list-search')->with('danger', 'Operação temporáriamente indisponível, contacte o administrador');
        }

        return redirect()->route('storage.list-search')->with('success', 'Dado(s) atualizado(s) com sucesso!');
    }

    /**
     * Exibe tela para confirmar a remoção do item do estoque
     *
     * @param string $id
     * @return object
     */
    public function remove(string $id): object
    {
        $id = Crypt::decryptString($id);

        $this->dataView = [
            'title' => 'ADM - Estoque',
            'dashboard' => 'Confirmar remoção?',
            'storage' => $this->findOrFailById($id)
        ];

        return view('adm.storage.storage.remove', $this->dataView);
    }

    /**
     * Função para remove item do estoque no banco de dados
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {
        $id = Crypt::decryptString($id);

        if (Storage::where('id', $id)->delete() !== 1) {
            return redirect()->route('storage.list-search')->with('danger', 'Operação temporáriamente indisponível, contacte o administrador');
        }

        return redirect()->route('storage.list-search')->with('success', 'Item removido com sucesso!');
    }

    /**
     * Persiste os dados do estoque no banco de dados
     *
     * @param array $dataStorage
     * @return boolean
     */
    private function persistDataStorage(array $dataStorage): bool
    {

        $id = Crypt::decryptString($dataStorage['id']);
        $storage = $this->findOrFailById($id);

        $storage->quantity = $storage->quantity + $dataStorage['quantity'];

        return $storage->save();
    }

    private function getStorageByDescription(null|string $description): null|object
    {
        if ($description !== null) {
            return DB::table('storages')
                            ->join('products', 'storages.product_id', '=', 'products.id')
                            ->select('storages.id', 'storages.product_id', 'storages.quantity', 'products.description', 'products.image')
                            ->where('description', 'like', '%' . $description . '%')
                            ->paginate(10);
        }

        return DB::table('storages')
                        ->join('products', 'storages.product_id', '=', 'products.id')
                        ->select('storages.id', 'storages.product_id', 'storages.quantity', 'products.description', 'products.image')
                        ->paginate(10);
    }
    
    /**
     * Função que valida o campo de busca pelo produto no estoque
     *
     * @param Request $request
     * @return void
     */
    private function runValidation(Request $request): void
    {
        $request->validate([
            'description' => 'nullable|string|max:220|min:3',
                ], [
            'description.string' => 'Este campo não atende aos requisitos mínimos.',
            'description.max' => 'Este campo não atende aos requisitos mínimos.',
            'description.min' => 'Este campo não atende aos requisitos mínimos.'
        ]);
    }

    /**
     * Realiza a verificação da existência do produto no estoque
     * 
     * @param int $id Id do produto no estoque
     * @return object|null
     */
    private function findOrFailById(int $id): object
    {
        return Storage::where('id', $id)->first();
    }

}

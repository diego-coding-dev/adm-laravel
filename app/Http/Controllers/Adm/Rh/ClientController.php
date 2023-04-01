<?php

namespace App\Http\Controllers\Adm\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateClient as CreateClientRequest;
use App\Http\Requests\UpdateClient as UpdateClientRequest;
use App\Models\Client;

class ClientController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe tela com a lista de clientes
     *
     * @return object
     */
    public function listSearch(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Clientes',
        ];

        if (!$request->has('name') || $request->input('name') === null) {
            $this->dataView['clientList'] = Client::where('type_user_id', 1)->paginate(10);
        } else {
            $this->runValidation($request);

            $name = $request->query('name');

            $this->dataView['name'] = $name;
            $this->dataView['clientList'] = $this->findCLientByName($name);
        }

        return view('adm.rh.client.listSearch', $this->dataView);
    }

    /**
     * Exibe o formulário para criar novo cliente
     *
     * @return object
     */
    public function create(): object
    {
        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Registrar cliente'
        ];

        return view('adm.rh.client.create', $this->dataView);
    }

    /**
     * Persiste os dados no banco de dados
     * 
     * @return object Illuminate\Http\RedirectResponse
     */
    public function insert(CreateClientRequest $request): object
    {
        $request->validated();

        $dataForm = $request->except(['_token']);
        $dataForm['type_user_id'] = 1;

        $client = Client::create($dataForm);

        if (isset($client->id)) {
            return redirect()->route('client.list-search')->with('success', 'Cliente criado com sucesso!');
        }

        return redirect()->route('client.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
    }

    /**
     * Exibe formulário para edição de produto
     * 
     * @param int $id Id do cliente
     * @return object view()
     */
    public function edit(int $id): object
    {

        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Atualizar cliente',
            'client' => $this->checkExists($id)
        ];

        return view('adm.rh.client.edit', $this->dataView);
    }

    /**
     * Atualiza dados no banco de dados
     * 
     * @return object
     */
    public function update(UpdateClientRequest $request): object
    {
        $request->validated();

        $clientData = $this->checkExists($request->input('id'));

        $dataChecked = $this->removeEmptyFields($request->except(['_token']));

        if (!array_key_exists('name', $dataChecked) && !array_key_exists('email', $dataChecked)) {
            return redirect()->route('client.list-search')->with('warning', 'Não há dados para atualizar!');
        }

        $clientData->fill($dataChecked);

        if ($clientData->save()) {
            return redirect()->route('client.list-search')->with('success', 'Cliente atualizado com sucesso!');
        }

        return redirect()->route('client.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
    }

    /**
     * Exibe tela para confirmar exclusão de um cliente
     * 
     * @param int $id
     * @return object
     */
    public function remove(int $id): object
    {
        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Confirmar remoção?',
            'client' => $this->checkExists($id)
        ];

        return view('adm.rh.client.remove', $this->dataView);
    }

    /**
     * Remove cliente no banco de dados
     * 
     * @return object
     */
    public function delete(int $id)
    {
        $this->checkExists($id);

        Client::where('id', $id)->delete();

        return redirect()->route('client.list-search')->with('success', 'Cliente deletado com sucesso!');
    }

    /**
     * Verifica se há campos vazios vindos do formulário e os remove
     * 
     * @param array $fields
     * @return array|null
     */
    private function removeEmptyFields(array $fields): array|null
    {
        foreach ($fields as $key => $value) {
            if (empty($value)) {
                unset($fields[$key]);
            }
        }

        return $fields;
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
            'name' => 'nullable|string|max:220|min:3',
                ], [
            'name.string' => 'Este campo não atende aos requisitos mínimos.',
            'name.max' => 'Este campo não atende aos requisitos mínimos.',
            'name.min' => 'Este campo não atende aos requisitos mínimos.'
        ]);
    }

    /**
     * Função que realiza busca no banco de dados com base no nome
     *
     * @param string $name
     * @return void
     */
    private function findCLientByName(string $name)
    {
        return Client::where('name', 'like', '%' . $name . '%')->paginate(10);
    }

    /**
     * Realiza a verificação da existência do produto
     * 
     * @param int $id Id da categoria do produto
     * @return object|null
     */
    private function checkExists(int $id): object|null
    {
        return Client::findOrFail($id);
    }

}

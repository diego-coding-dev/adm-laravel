<?php

namespace App\Http\Controllers\Adm\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TypeProduct as TypeProductRequest;
use App\Models\TypeProduct;
use Illuminate\Support\Facades\Crypt;

class TypeProductController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe tela com a lista de tipos de produto
     *
     * @return object
     */
    public function listSearch(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Categoria de Produtos'
        ];

        if (!$request->has('description') || $request->input('description') === null) {
            $this->dataView['productTypeList'] = TypeProduct::paginate(10);
        } else {
            $this->runValidation($request);

            $description = $request->query('description');

            $this->dataView['description'] = $description;
            $this->dataView['productTypeList'] = $this->findTypeProductByDescription($description);
        }

        return view('adm.storage.type-product.listSearch', $this->dataView);
    }

    /**
     * Exibe o formulário para criar nova categoria de produto
     *
     * @return object
     */
    public function create(): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Criar categoria de produto'
        ];

        return view('adm.storage.type-product.create', $this->dataView);
    }

    /**
     * Inser dados no banco de dados
     * 
     * @return object Illuminate\Http\RedirectResponse
     */
    public function insert(TypeProductRequest $request): object
    {
        $request->validated();

        $dataTypeProduct = TypeProduct::create([
                    'description' => $request->input('description')
        ]);

        if (isset($dataTypeProduct->id)) {
            return redirect()->route('type-product.list-search')->with('success', 'Categoria criada com sucesso!');
        }

        return redirect()->route('type-product.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
    }

    /**
     * Exibe formulário para edição da categoria de produto
     * 
     * @param string $id Id da categoria do produto
     * @return object view()
     */
    public function edit(string $id): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Editando categoria de produto',
            'category' => $this->checkExists(Crypt::decryptString($id))
        ];

        return view('adm.storage.type-product.edit', $this->dataView);
    }

    /**
     * Atualiza dados no banco de dados
     * 
     * @return object
     */
    public function update(TypeProductRequest $request): object
    {
        $request->validated();

        $data = $this->checkExists($request->input('id'));

        $data->description = $request->input('description');

        if ($data->isDirty()) {
            $data->save();
            return redirect()->route('type-product.list-search')->with('success', 'Categoria atualizado com sucesso!');
        }

        return redirect()->route('type-product.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
    }

    /**
     * Exibe tela para confirmar exclusão de uma categoria
     * 
     * @param string $id
     * @return object
     */
    public function remove(string $id)
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Confirmar remoção?',
            'category' => $this->checkExists(Crypt::decryptString($id))
        ];

        return view('adm.storage.type-product.remove', $this->dataView);
    }

    /**
     * Remove categoria de produto no banco de dados
     * 
     * @param string $id
     * @return object
     */
    public function delete(string $id): object
    {
        $id = Crypt::decryptString($id);

        $this->checkExists($id);

        TypeProduct::where('id', $id)->delete();

        return redirect()->route('type-product.list-search')->with('success', 'Categoria deletada com sucesso!');
    }

    /**
     * Função que valida o campo de busca pela categoria do produto
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
     * Função que realiza busca no banco de dados com base na descrição
     *
     * @param string $description
     * @return void
     */
    private function findTypeProductByDescription(string $description)
    {
        return TypeProduct::where('description', 'like', '%' . $description . '%')->paginate(10);
    }

    /**
     * Realiza a verificação da existência da categoria do produto
     * 
     * @param int $id Id da categoria do produto
     * @return object|null
     */
    private function checkExists(int $id): object|null
    {
        return TypeProduct::findOrFail($id);
    }

}

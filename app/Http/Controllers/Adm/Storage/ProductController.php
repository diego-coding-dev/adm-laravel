<?php

namespace App\Http\Controllers\Adm\Storage;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TypeProduct;
use App\Models\Storage as StorageModel;
use Illuminate\Http\Request;
use App\Http\Requests\Product as ProductRequest;
use App\Http\Requests\ProductUpdate as ProductUpdateRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe tela com a lista de produtos
     *
     * @return object
     */
    public function listSearch(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Produtos'
        ];

        if (!$request->has('description') || $request->input('description') === null) {
            $this->dataView['productList'] = Product::paginate(10);
        } else {
            $this->runValidation($request);

            $description = $request->query('description');

            $this->dataView['description'] = $description;
            $this->dataView['productList'] = $this->findProductByDescription($description);
        }

        return view('adm.storage.product.listSearch', $this->dataView);
    }

    /**
     * Exibe o formulário para criar novo produto
     *
     * @return object
     */
    public function create(TypeProduct $typeProduct): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Criar produto',
            'typeProductList' => $typeProduct::all()
        ];

        return view('adm.storage.product.create', $this->dataView);
    }

    /**
     * Persiste os dados no banco de dados
     * 
     * @return object Illuminate\Http\RedirectResponse
     */
    public function insert(ProductRequest $request): object
    {
        $request->validated();

        $dataProduct = $this->putProductImage($request);

        if (!$this->persistProductData($dataProduct)) {
            return redirect()->route('product.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
        }

        return redirect()->route('product.list-search')->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Exibe formulário para edição de produto
     * 
     * @param string $id Id da categoria do produto
     * @return object view()
     */
    public function edit(string $id): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Editar produto',
            'product' => $this->checkExists(Crypt::decryptString($id)),
            'typeProductList' => TypeProduct::all()
        ];

        return view('adm.storage.product.edit', $this->dataView);
    }

    /**
     * Atualiza dados no banco de dados
     * 
     * @return object
     */
    public function update(ProductUpdateRequest $request): object
    {
        $request->validated();

        $productData = $this->checkExists($request->input('id'));

        $dataChecked = $this->removeEmptyFields($request->except(['_token']));

        if (!array_key_exists('description', $dataChecked) && !array_key_exists('image', $dataChecked)) {
            return redirect()->route('product.list-search')->with('warning', 'Não há dados para atualizar!');
        }

        if ($request->hasFile('image')) {
            $dataChecked['image'] = $request->file('image')->store(config('constants.PRODUCT_PATH'));

            if (!mb_strpos($productData->image, 'null.jpg')) {
                Storage::disk('public')->delete($productData->image);
            }
        }

        $productData->fill($dataChecked);

        if ($productData->save()) {
            return redirect()->route('product.list-search')->with('success', 'Produto atualizado com sucesso!');
        }

        return redirect()->route('product.list-search')->with('danger', 'Operação indisponível, contacte o administrador!');
    }

    /**
     * Exibe tela para confirmar exclusão de uma categoria
     * 
     * @param string $id
     * @return object
     */
    public function remove(string $id): object
    {
        $this->dataView = [
            'title' => 'ADM - Estoque e Produtos',
            'dashboard' => 'Confirmar remoção?',
            'product' => $this->checkExists(Crypt::decryptString($id))
        ];

        return view('adm.storage.product.remove', $this->dataView);
    }

    /**
     * Remove produto no banco de dados
     * 
     * @param string $id
     * @return object
     */
    public function delete(string $id): object
    {
        $id = Crypt::decryptString($id);

        $this->checkExists($id);

        Product::where('id', $id)->delete();

        return redirect()->route('product.list-search')->with('success', 'Produto deletado com sucesso!');
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

    private function putProductImage($request): array
    {
        $dataForm = $request->except(['_token']);

        if ($request->hasFile('image')) {
            $dataForm['image'] = $request->file('image')->store(config('constants.PRODUCT_PATH'));
        } else {
            $dataForm['image'] = config('constants.PRODUCT_PATH') . '/null.jpg';
        }

        return $dataForm;
    }

    private function persistProductData(array $dataProduct): bool
    {
        try {
            DB::transaction(function () use ($dataProduct) {
                $product = Product::create($dataProduct);

                StorageModel::create(['product_id' => $product->id]);
            });

            return true;
        } catch (\Exception $th) {
            return false;
        }
    }
    
    /**
     * Função que valida o campo de busca pelo produto
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
    private function findProductByDescription(string $description)
    {
        return Product::where('description', 'like', '%' . $description . '%')->paginate(10);
    }

    /**
     * Realiza a verificação da existência do produto
     * 
     * @param int $id Id da categoria do produto
     * @return object|null
     */
    private function checkExists(int $id): object|null
    {
        return Product::findOrFail($id);
    }

}

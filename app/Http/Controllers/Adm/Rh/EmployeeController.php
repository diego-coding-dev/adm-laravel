<?php

namespace App\Http\Controllers\Adm\Rh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeCreate as EmployeeCreateRequest;
use App\Mail\Employee\activation;
use App\Models\ActivationToken;
use App\Models\Employee;
use App\Lib\Token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Classe responsável por Employee
 */
class EmployeeController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe tela com a lista de funcionários
     *
     * @return object
     */
    public function listSearch(Request $request): object
    {
        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Funcionários'
        ];

        if (!$request->has('name') || $request->name === null) {
            $this->dataView['employeeList'] = Employee::where('type_user_id', 2)->paginate(10);
        } else {
            $this->runValidation($request);

            $name = $request->query('name');

            $this->dataView['name'] = $name;
            $this->dataView['employeeList'] = $this->findEmployeeByName($name);
        }

        return view('adm.rh.employee.listSearch', $this->dataView);
    }

    /**
     * Persiste os dados no banco de dados
     * 
     * @return object Illuminate\Http\RedirectResponse
     */
    public function create(): object
    {
        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Criando Funcionário'
        ];

        return view('adm.rh.employee.create', $this->dataView);
    }

    /**
     * Persiste os dados no banco de dados, utiliza transaction
     * 
     * @return object Illuminate\Http\RedirectResponse
     */
    public function insert(EmployeeCreateRequest $request)
    {
        $request->validated();

        $dataForm = $request->except(['_token']);

        $result = $this->persitDataEmployee($dataForm);

        if (!$result) {
            return redirect()->route('employee.list-search')->with('danger', 'Operação temporáriamente indisponível, contacte o administrador');
        };

        $dataForm['token'] = $result;

        Mail::to(config('mail.from.address'))->send(new activation($dataForm));

        return redirect()->route('employee.list-search')->with('success', 'Funcionário cadastrado com sucesso!');
    }

    /**
     * FUnção para exibir tela com os informações do usuário
     *
     * @param string|null $id
     * @return void
     */
    public function edit(string $id = null)
    {
        $employee = $this->checkExists(Crypt::decryptString($id));

        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Informações',
            'employee' => $employee
        ];

        return view('adm.rh.employee.edit', $this->dataView);
    }

    /**
     * Função para modificar o status do funcionário
     *
     * @param string|null $id
     * @return object
     */
    public function changeStatus(string $id = null): object
    {
        $employee = $this->checkExists(Crypt::decryptString($id));

        if ($employee->is_active) {
            $employee->is_active = false;
            $employee->save();

            return redirect()->route('employee.list-search')->with('success', 'Funcionário desativado com sucesso!');
        } else {
            $employee->is_active = true;
            $employee->save();

            return redirect()->route('employee.list-search')->with('success', 'Funcionário ativado com sucesso!');
        }
    }

    /**
     * Exibe tela para confirmar exclusão de um funcionário
     * 
     * @param string $id
     * @return object
     */
    public function remove(string $id): object
    {
        $employee = $this->checkExists(Crypt::decryptString($id));

        $this->dataView = [
            'title' => 'ADM - RH',
            'dashboard' => 'Confirmar remoção?',
            'employee' => $employee
        ];

        return view('adm.rh.employee.remove', $this->dataView);
    }

    /**
     * Remove funcionário no banco de dados
     * 
     * @param string $id
     * @return object
     */
    public function delete(string $id)
    {
        $employee = $this->checkExists(Crypt::decryptString($id));

        if (Employee::where('id', $employee->id)->delete()) {
            return redirect()->route('employee.list-search')->with('success', 'Funcionário deletado com sucesso!');
        }

        return redirect()->route('employee.list-search')->with('danger', 'Operação temporáriamente indisponível, contacte o administrador');
    }

    public function logout(Request $request): object
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Até a próxima!');
    }

    /**
     * Função para persistir dados do novo funcionário e da ativação utilizando transaction
     *
     * @param array $employeeData
     * @return string|boolean
     */
    private function persitDataEmployee(array $employeeData): string|bool
    {
        $token = new Token();
        $employeeData['type_user_id'] = 2;

        $activationData['email'] = $employeeData['email'];
        $activationData['token_hash'] = $token->getHash();
        $activationData['created_at'] = Date('Y-m-d H:i:s', time() + 3600);

        try {
            DB::transaction(function () use ($employeeData, $activationData) {
                Employee::create($employeeData);
                ActivationToken::create($activationData);
            });

            return $token->getToken();
        } catch (\Exception $th) {
            dd($th->getMessage());
            return false;
        }
    }
    
    /**
     * Função que valida o campo de busca pelo funcionário
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
    private function findEmployeeByName(string $name)
    {
        return Employee::where('type_user_id', 2)->where('name', 'like', '%' . $name . '%')->paginate(10);
    }

    /**
     * Realiza a verificação da existência do funcionário
     * 
     * @param int $id Id do funcionário
     * @return object|null
     */
    private function checkExists(int $id): object|null
    {
        return Employee::findOrFail($id);
    }

}

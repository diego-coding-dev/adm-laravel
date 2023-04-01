<?php

namespace App\Http\Controllers\Activation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\setPassword;
use Illuminate\Support\Facades\DB;
use App\Lib\Token;
use App\Mail\Employee\resendActivation;
use App\Models\ActivationToken;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Classe que realiza a ativação da conta do funcionário
 * 
 * @method object start(string $token = null) --public
 * @method object resendActivation() --public
 * @method object (Request $request) --public
 * @method object setPassword(setPassword $request) --public
 * @method void checkTokenFromDB(string $token) --private
 * @method bool checkIfExpired() --private
 * @method void checkEmployeeData() --private
 * @method bool active(object $employeeData) --private
 * @method bool findOrFailByEmailActivation(string $email) --private
 * @method bool findOrFailByEmail(string $email) --private
 * @method bool persistActivationData(string $email) --private
 * @method null|object checkExists(int $id) --private
 * @method object authenticateAfterActivation($request, array $credentials) --private
 */
class EmployeeActivationController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Representa null|object dados tabela ACTIVE_TOKEN
     *
     * @var null|object
     */
    private null|object $activeData;

    /**
     * Representa null|object dados tabela EMPLOYEE
     *
     * @var null|object
     */
    private null|object $employeeData;

    /**
     * Função que inicia o processo de ativação da conta
     *
     * @param string $token
     * @return object
     */
    public function start(string $token = null): object
    {
        if (!$token) {
            return abort(404, 'Desculpe pelo transtorno.');
        }

        $this->checkTokenFromDB($token);

        if (!$this->activeData) {
            return abort(404, 'Desculpe pelo transtorno.');
        }

        if (!$this->checkExpired()) {
            return redirect()->route('activation.resend')->with('warning', 'Sua ativação expirou, por favor envie-o novamente.');
        }

        $this->checkEmployeeData();

        if (!$this->employeeData) {
            return abort(404, 'Desculpe pelo transtorno.');
        }

        $this->dataView = [
            'title' => 'Registrando um senha',
            'id' => $this->employeeData->id
        ];

        return view('activation.employeeActivation.createPassword', $this->dataView);
    }

    /**
     * Função que exibe formulário para reenvio da ativação
     *
     * @return object
     */
    public function resendActivation(): object
    {
        $this->dataView = [
            'title' => 'Reenviar ativação'
        ];

        return view('activation.employeeActivation.resendActivation', $this->dataView);
    }

    public function sendActivation(Request $request): object
    {
        $request->validate([
            'email' => 'required|string|email|max:100|min:10'
        ], [
            'email.required' => 'Este campo é obrigatório.',
            'email.string' => 'Este campo não atende aos requisitos mínimos.',
            'email.email' => 'Este campo não atende aos requisitos mínimos.',
            'email.max' => 'Este campo não atende aos requisitos mínimos.',
            'email.min' => 'Este campo não atende aos requisitos mínimos.',
        ]);

        $dataForm = $request->except(['_token']);

        if (!$this->findOrFailByEmail($dataForm['email'])) {
            return redirect()->back()->with('warning', 'Seus dados estão inválidos!');
        }

        $dataForm['token'] = $this->persistActivationData($dataForm['email']);

        Mail::to(config('mail.from.address'))->send(new resendActivation($dataForm));

        return redirect()->back()->with('success', 'Ativação enviada com sucesso!');
    }

    /**
     * Função que atualiza a senha do funcionário
     *
     * @param setPassword $request
     * @return void
     */
    public function setPassword(setPassword $request)
    {
        $request->validated();

        $dataForm = $request->except(['_token']);

        $employeeData = $this->checkExists($dataForm['id']);

        if ($this->active($employeeData, $dataForm['password'])) {
            $dataForm['email'] = $employeeData->email;
            return $this->authenticateAfterActivation($request, $dataForm);
        }

        // precisa implementar caso ocorra erro de login
    }

    /**
     * Função para verificar se existe dados na table active_token
     *
     * @param string $token
     * @return void
     */
    private function checkTokenFromDB(string $token): void
    {
        $tokenLib = new Token($token);

        $this->activeData = ActivationToken::where('token_hash', $tokenLib->getHash())->first();
    }

    /**
     * Função para verificar se a data do token está expirada
     *
     * @return boolean
     */
    private function checkExpired(): bool
    {
        return Date('Y-m-d H:i:s') < $this->activeData->created_at ;
    }

    /**
     * Função para verificar se existe dados na table employee
     *
     * @return void
     */
    private function checkEmployeeData(): void
    {
        $this->employeeData = Employee::where('email', $this->activeData->email)->first();
    }

    /**
     * Função que ativa a conta do funcionário e apaga o dado referente na tebela active_token
     *
     * @return boolean
     */
    private function active(object $employeeData, string $password): bool
    {
        try {
            DB::transaction(function () use ($employeeData, $password) {
                ActivationToken::where('email', $employeeData->email)->delete();
                Employee::where('id', $employeeData->id)->update(['is_active' => true, 'password' => Hash::make($password)]);
            });

            return true;
        } catch (\Exception $th) {
            return false;
        }
    }

    /**
     * Função que verifica a existência do funcionário na tabela EMPLOYEE
     *
     * @param string $email
     * @return bool
     */
    private function findOrFailByEmail(string $email): bool
    {
        $employee = Employee::where('email', $email)->first();

        if (!$employee) {
            return false;
        }

        return true;
    }

    /**
     * Função para persistir os dados da ativação na tabela ACTIVATION_TOKEN
     *
     * @param string $email
     * @return string
     */
    private function persistActivationData(string $email): string
    {
        $tokenLib = new Token();

        if (!$this->findOrFailByEmailActivation($email)) {
            ActivationToken::create([
                'email' => $email,
                'token_hash' => $tokenLib->getHash(),
                'created_at' => Date('Y-m-d H:i:s', time() + 3600)
            ]);
        } else {
            ActivationToken::where('email', $email)->update([
                'token_hash' => $tokenLib->getHash(),
                'created_at' => Date('Y-m-d H:i:s', time() + 3600)
            ]);
        }

        return $tokenLib->getToken();
    }

    /**
     * Função que verifica a existência do funcionário na tabela ACTIVATION_TOKEN
     *
     * @param string $email
     * @return bool
     */
    private function findOrFailByEmailActivation(string $email): bool
    {
        $data = ActivationToken::where('email', $email)->first();

        if (!$data) {
            return false;
        }

        return true;
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

    /**
     * Realiza a autenticação no sistema
     * 
     * @param type $request
     * @param array $credentials
     * @return object
     */
    private function authenticateAfterActivation($request, array $credentials): object
    {
        if (Auth::guard('employee')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('adm.home')->with('success', 'Seja bem vindo!');
        }
        dd('falha de autenticação');
    }
}

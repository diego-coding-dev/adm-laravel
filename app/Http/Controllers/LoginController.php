<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeAuthenticate;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Função que exibe formulário de autenticação
     *
     * @return object
     */
    public function index(): object
    {
        $this->dataView = [
            'title' => 'Login'
        ];

        return view('login.index', $this->dataView);
    }

    public function authenticate(EmployeeAuthenticate $request): object
    {
        $request->validated();

        $dataForm = $request->except(['_token']);

        if (Auth::guard('employee')->attempt($dataForm)) {
            $request->session()->regenerate();

            return redirect()->route('client.list-search')->with('success', 'Bem vindo(a) ' . Auth::user()->name);
        }

        return redirect()->back()->with('danger', 'Suas credênciais são inválidas.');
    }
}

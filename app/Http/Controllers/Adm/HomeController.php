<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * 
     * @var array $dataView Array com informações para serem exibidas na view
     */
    private array $dataView;

    /**
     * Exibe a tela Home da área ADM
     * 
     * @return string
     */
    public function index(): object {
        $this->dataView = [
            'title' => 'ADM - Home',
            'dashboard' => 'Home'
        ];

        return view('adm.home.index', $this->dataView);
    }

}

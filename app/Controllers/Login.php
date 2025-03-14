<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index(): string
    {
       return view('login/login');
    }
    public function registrar(): string
    {
        return view('login/registrar');
    }
    public function recuperar(): string
    {
        return view('login/recuperar');
    }
    public function alterar(): string{
       return view('login/alterar');
    }
}

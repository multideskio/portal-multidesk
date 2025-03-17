<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RedirectResponse;

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

   public function confirmar(string $id = null): string|RedirectResponse
   {
      $modelUser = new UsuarioModel();
      $result = $modelUser->where('token', $id)->first();

      if (!$result) {
         return redirect()->to(base_url('login?&confirm=false'));
      }

      $data = [
         'token' => $id
      ];

      return view('login/confirmar', $data);
   }
    public function alterar(): string{
       return view('login/alterar');
    }

    public function logout(): RedirectResponse
    {
       session()->destroy();
       return redirect()->to(base_url('login'));
    }
}

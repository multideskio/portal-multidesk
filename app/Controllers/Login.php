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

   /**
    * Valida o token e email para alterar a senha
    *
    * @param string|null $id Token de validação
    * @return string|RedirectResponse Retorna a view de alteração ou redireciona para login
    */
   public function alterar(string $id = null): string|RedirectResponse
   {
      $modelUser = new UsuarioModel();
      $result = $modelUser->where('token', $id)->first();
      $email = $this->request->getGet('email');

      // Valida se o token existe e pertence ao email informado
      if (!$result || $result['email'] !== $email) {
         log_message('info', "O email $email tentou alterar a senha sem um token válido");
         return redirect()->to(base_url('login?&alterar=false'));
      }

      // Retorna a view com o token e email validados
      return view('login/alterar', ['token' => $id, 'email' => $email]);
   }
    public function logout(): RedirectResponse
    {
       $this->session->destroy();
       return redirect()->to(base_url('login'));
    }
}

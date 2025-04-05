<?php

namespace App\Libraries;

use App\Models\ClienteModel;
use App\Models\UsuarioModel;
use Exception;
use JsonException;
use RuntimeException;

class AuthLibrarie
{
   protected UsuarioModel $usuarioModel;
   protected ClienteModel $clienteModel;
   protected ?object $session;

   public function __construct()
   {
      $this->usuarioModel = new UsuarioModel();
      $this->clienteModel = new ClienteModel();
      $this->session = session();
   }

   /**
    * @throws JsonException
    */
   public function loginAdmin($input): void
   {
      // Busca o usuário no banco de dados pelo e-mail fornecido
      $usuario = $this->usuarioModel->where('email', $input['email'])->findAll();

      // Verifica se existe exatamente um usuário com o e-mail fornecido
      if (count($usuario) !== 1) {
         log_message('debug', "Usuário não foi encontrado ou tem mais de 1 usuário com o mesmo e-mail. == " . count($usuario));
         throw new RuntimeException('Usuário não encontrado');
      }

      // Verifica se a senha fornecida é válida
      if (!password_verify($input['password'], $usuario[0]['senha'])) {
         log_message('debug', "Senha incorreta");
         throw new RuntimeException('Senha incorreta');
      }

      // Dados a serem armazenados na sessão
      $data = [
         'isLoggedIn' => true,
         'id' => $usuario[0]['id'],
         'empresa' => $usuario[0]['empresa_id'],
         'nome' => $usuario[0]['nome'],
         'email' => $usuario[0]['email'],
         'foto' => $usuario[0]['foto'],
         'roles' => json_decode($usuario[0]['roles']) ?? [] // Garante que 'roles' seja um array, mesmo se não definido
      ];

      // Armazena os dados na sessão
      $this->session->set(['data' => $data]);
   }

   public function loginCliente($input): void
   {
      // Busca o cliente no banco de dados pelo e-mail fornecido
      $cliente = $this->clienteModel->where('email', $input['email'])->findAll();

      // Verifica se existe exatamente um cliente com o e-mail fornecido
      if (count($cliente) !== 1) {
         log_message('debug', "Cliente não foi encontrado ou há duplicidade de registros com o mesmo e-mail. Quantidade encontrada: " . count($cliente));
         throw new RuntimeException('Cliente não encontrado');
      }

      // Verifica se a senha fornecida é válida
      if (!password_verify($input['password'], $cliente[0]['senha'])) {
         log_message('debug', "Senha incorreta para o cliente com o e-mail: " . $input['email']);
         throw new RuntimeException('Senha incorreta');
      }

      // Prepara os dados a serem armazenados na sessão
      $data = [
         'isLoggedIn' => true,
         'id' => $cliente[0]['id'],
         'empresa' => $cliente[0]['empresa_id'],
         'nome' => $cliente[0]['nome'],
         'email' => $cliente[0]['email'],
         'foto' => $cliente[0]['foto'],
      ];

      // Armazena os dados na sessão
      $this->session->set(['data' => $data]);
   }

   public function loginClienteNoPass(string $email): void
   {
      // Busca o cliente no banco de dados pelo e-mail fornecido
      $cliente = $this->usuarioModel->where('email', $email)->findAll();

      // Verifica se existe exatamente um cliente com o e-mail fornecido
      if (count($cliente) !== 1) {
         log_message('debug', "Cliente não foi encontrado ou há duplicidade de registros com o mesmo e-mail. Quantidade encontrada: " . count($cliente));
         throw new RuntimeException('Cliente não encontrado');
      }

      $usuario = $cliente[0];

      // Prepara os dados a serem armazenados na sessão
      $data = [
         'isLoggedIn' => true,
         'id' => $usuario['id'],
         'empresa' => $usuario['empresa_id'],
         'nome' => $usuario['nome'],
         'email' => $usuario['email'],
         'foto' => $usuario['foto'],
         'roles' => json_decode($usuario['roles']) ?? [] // Garante que 'roles' seja um array, mesmo se não definido
      ];

      // Armazena os dados na sessão
      $this->session->set(['data' => $data]);
   }


   public function loginGoogle($email): void
   {
      // Busca o usuário no banco de dados pelo e-mail fornecido
      $usuario = $this->usuarioModel->where('email', $email)->findAll();

      // Dados a serem armazenados na sessão
      try {
         $data = [
            'isLoggedIn' => true,
            'id' => $usuario[0]['id'],
            'empresa' => $usuario[0]['empresa_id'],
            'nome' => $usuario[0]['nome'],
            'email' => $usuario[0]['email'],
            'foto' => $usuario[0]['foto'],
            'roles' => json_decode($usuario[0]['roles'] ?? '{}', false, 512, JSON_THROW_ON_ERROR) ?? [] // Garante que 'roles' seja um array, mesmo se não definido
         ];
      } catch (JsonException $e) {
         throw new RuntimeException('Erro ao decodificar o JSON');
      }

      // Armazena os dados na sessão
      $this->session->set(['data' => $data]);
   }
}
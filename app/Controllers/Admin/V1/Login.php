<?php

namespace App\Controllers\Admin\V1;

use App\Libraries\AuthLibrarie;
use App\Libraries\EmailLibrarie;
use App\Models\EmpresaModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;


class Login extends ResourceController
{
   use ResponseTrait;

   protected AuthLibrarie $authLibrarie;
   private UuidInterface $uuid;

   public function __construct()
   {
      $this->authLibrarie = new AuthLibrarie();
      $this->uuid = Uuid::uuid4();
   }

   /**
    * Handles the login process for an admin user.
    *
    * Validates the input data to ensure the email and password meet the required
    * rules. If the validation passes, the admin user is authenticated. In case
    * of an error, a failure response with the error message is returned.
    *
    * @return ResponseInterface Returns a success response with a confirmation
    * message on successful login or a failure response with an error message
    * in case of validation or other exceptions.
    */
   public function login(): ResponseInterface
   {
      try {
         // Obtém os dados enviados na requisição POST
         $input = $this->request->getPost();

         // Instancia o serviço de validação e define as regras para os campos
         $validation = Services::validation();
         $validation->setRules([
            'email' => 'required|valid_email', // O campo 'email' é obrigatório e deve ser um e-mail válido
            'password' => 'required|min_length[6]' // O campo 'password' é obrigatório e deve conter no mínimo 6 caracteres
         ]);

         // Verifica se os dados da entrada não passam na validação
         if (!$validation->run($input)) {
            // Lança uma exceção com os erros de validação concatenados
            throw new RuntimeException(implode('. ', $validation->getErrors()));
         }

         // Chama o méthodo para autenticar o administrador
         $this->authLibrarie->loginAdmin($input);


         // Retorna uma resposta de sucesso com uma mensagem
         return $this->respond(['message' => 'Login realizado com sucesso']);
      } catch (Exception $e) {
         // Em caso de erro, retorna uma resposta de falha com a mensagem de exceção
         return $this->fail($e->getMessage());
      }
   }

   /**
    * Handles the registration process for a new company and its associated user.
    *
    * This method creates a new company and user record, generates a unique token
    * and a verification code for the user, and sends an email containing the code.
    * It performs database transactions to ensure consistency.
    *
    * @return ResponseInterface Returns a response containing the generated token
    * if the operation is successful, or an error message if an exception occurs.
    */
   public function registrar(): ResponseInterface
   {
      $db = \Config\Database::connect();

      try {
         // Obtém os dados enviados na requisição POST
         $input = $this->request->getPost();

         // Instancia o modelo de Empresa
         $modelEmpresa = new EmpresaModel();

         // Inicia a transação do banco de dados
         $db->transStart();

         // Insere uma nova empresa e obtém o ID gerado
         $idEmpresa = $modelEmpresa->insert(['nome' => 'Empresa']);

         // Instancia o modelo de Usuário
         $modelUsuario = new UsuarioModel();

         // Gera um token único para o usuário
         $token = $this->uuid->toString();

         // Gera um código de verificação aleatório de 6 dígitos
         $code = random_int(100000, 999999);

         $verify = $modelUsuario->where('email', $input['email'])->countAllResults();
         if ($verify > 0) {
            throw new RuntimeException('Esse e-mail já está em uso no sistema.');
         }

         // Insere o usuário no banco de dados com os campos fornecidos
         $modelUsuario->insert([
            'email' => $input['email'],
            'senha' => password_hash($input['password'], PASSWORD_DEFAULT), // Criptografa a senha
            'empresa_id' => $idEmpresa,
            'token' => $token,
            'code' => $code
         ]);

         // Envia um e-mail para o usuário com o código de verificação
         $view = view('login/emails/codigo', ['code' => $code, 'email' => $input['email']]);

         $emailLibraries = new EmailLibrarie();
         $emailLibraries->sendEmail($input['email'], 'Código', $view);

         // Finaliza a transação do banco de dados
         $db->transComplete();

         // Retorna uma resposta de sucesso contendo o token gerado
         return $this->respond(['token' => $token . '?email=' . $input['email']]);
      } catch (Exception $e) {
         // Em caso de erro, realiza o rollback da transação
         $db->transRollback();

         // Retorna uma resposta de falha com a mensagem da exceção
         return $this->fail($e->getMessage());
      }
   }

   /**
    * Confirma a verificação da conta do usuário através de um token
    *
    * Este méthodo valida o código de verificação enviado pelo usuário junto com seu email.
    * Se os dados estiverem corretos, atualiza o status da conta para verificado e
    * gera novos códigos de segurança.
    *
    * @param string|null $token Token de verificação
    * @return ResponseInterface Retorna uma resposta de sucesso se a conta for verificada
    * ou uma mensagem de erro caso ocorra alguma exceção
    */
   public function confirmar(string $token = null): ResponseInterface
   {
      try {
         // Obtém os dados enviados na requisição POST
         $input = $this->request->getPost();

         $modelUsuario = new UsuarioModel();

         // Busca o usuário que corresponde ao email, código e token fornecidos
         $user = $modelUsuario->where(
            [
               'email' => $input['email'],
               'code' => $input['code'],
               'token' => $token,
            ])->first();

         // Se encontrou o usuário, atualiza os dados de verificação
         if ($user) {
            $modelUsuario->update($user['id'], [
               'code' => random_int(100000, 999999), // Gera novo código
               'token' => $this->uuid->toString(), // Gera novo token
               'verificado' => 1 // Marca conta como verificada
            ]);

            // Envia um e-mail para o usuário com o código de verificação
            $view = view('login/emails/boas_vindas', ['email' => $input['email']]);

            $emailLibraries = new EmailLibrarie();
            $emailLibraries->sendEmail($input['email'], 'Seja muito bem vindo!', $view);

            return $this->respond(['message' => 'Conta verificada com sucesso']);
         }

         throw new RuntimeException('Houve um erro ao verificar sua conta.');
      } catch (Exception $e) {
         return $this->fail($e->getMessage());
      }
   }
}

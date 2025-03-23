<?php

namespace App\Controllers\Admin\V1;

use App\Libraries\AuthLibrarie;
use App\Libraries\EmailLibrarie;
use App\Models\EmpresaModel;
use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Google_Client;
use Google_Service_Oauth2;


class Login extends ResourceController
{
   use ResponseTrait;

   protected AuthLibrarie $authLibrarie;
   private UuidInterface $uuid;
   private Google_Client $client;

   public function __construct()
   {
      $this->authLibrarie = new AuthLibrarie();
      $this->uuid = Uuid::uuid4();

      // Configurar o cliente do Google API
      $this->client = new Google_Client();
      $this->client->setClientId(env('GOOGLE_ID'));  // Insira seu Client ID
      $this->client->setClientSecret(env('GOOGLE_SECRET'));  // Insira seu Client Secret
      $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));  // URI de redirecionamento
      $this->client->setAccessType('offline'); // Necessário para obter o refresh token
      $this->client->setPrompt('consent');    // Força a solicitação de permissões (apenas no primeiro login)
      $this->client->addScope('email');
      $this->client->addScope('profile');
      $this->client->addScope('https://www.googleapis.com/auth/calendar');

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

   /**
    * Realiza o processo de recuperação de senha do usuário
    *
    * Este méthodo recebe um email do usuário que esqueceu a senha,
    * verifica se o usuário existe no sistema, gera um novo código
    * e token de verificação, atualiza os dados do usuário no banco
    * e envia um email com as instruções para redefinir a senha.
    *
    * @return ResponseInterface Retorna mensagem de sucesso se email foi enviado
    * ou mensagem de erro em caso de falha
    */
   public function recuperarSenha(): ResponseInterface
   {
      try {
         // Obtém dados do POST
         $input = $this->request->getPost();

         $modelUsuario = new UsuarioModel();

         // Busca o usuário pelo email
         $usuario = $modelUsuario->where('email', $input['email'])->first();

         if (!$usuario) {
            throw new RuntimeException('Usuário não encontrado');
         }

         // Gera novo código de verificação e token
         $code = random_int(100000, 999999);
         $token = $this->uuid->toString();

         // Atualiza os dados do usuário
         $modelUsuario->update($usuario['id'], [
            'code' => $code,
            'token' => $token,
         ]);

         //Prepara e envia o email
         $view = view('login/emails/recuperacao', ['token' => $token, 'email' => $input['email'], 'nome' => $usuario['nome']]);;
         $emailLibraries = new EmailLibrarie();
         $emailLibraries->sendEmail($input['email'], 'Recuperar senha', $view);

         return $this->respond(['message' => 'Email de recuperação de conta enviado com sucesso', 'token' => $token . '?email=' . $input['email'] . '&type=recover']);
      } catch (Exception $e) {
         return $this->fail($e->getMessage());
      }
   }

   /**
    * @return void
    * @TODO TERMINAR RECUPERAÇÃO DE SENHA
    */
   public function novaSenha()
   {

   }

   /**
    * @TODO LOGIN COM O GOOGLE
    */
   public function google(): RedirectResponse
   {
      $authUrl = $this->client->createAuthUrl();  // Gerar URL de autenticação
      return redirect()->to($authUrl);  // Redirecionar para a página de login do Google
   }

   public function callbackGoogle(): ResponseInterface
   {
      try {
         $code = $this->request->getVar('code'); // Código recebido do Google

         if ($code) {
            // Inicializa a sessão
            //$session = session();

            // Obtém o token de acesso com base no código de autorização recebido
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            // Verifica se houve erro ao obter o token
            if (isset($token['error'])) {
               throw new RuntimeException($token['error']);
            }

            // Define o token no cliente
            $this->client->setAccessToken($token);

            // Obter o refresh token (somente é retornado no primeiro login com 'offline' ativado)
            $refreshToken = $token['refresh_token'] ?? null;

            // Obter informações do usuário pelo Google API
            $googleService = new Google_Service_Oauth2($this->client);
            $googleUser = $googleService->userinfo->get();

            // Exemplo de dados do usuário retornados
            $data = [
               'id' => $googleUser->id,
               'nome' => $googleUser->name,
               'email' => $googleUser->email,
               'foto' => $googleUser->picture,
               'refreshToken' => $refreshToken,
            ];

            // Verifica se o usuário já existe no banco pelo e-mail
            $user = $this->getUserByEmail($googleUser->email);

            if ($user) {
               // Atualiza o refresh token do usuário, se necessário
               if ($refreshToken) {
                  $this->updateUserRefreshToken($user['id'], $refreshToken);
               }
            } else {
               // Caso o usuário não exista, crie-o no banco
               $this->createUser([
                  'nome' => $googleUser->name,
                  'email' => $googleUser->email,
                  'foto' => $googleUser->picture,
                  'google_id' => $googleUser->id,
                  'refresh_token' => $refreshToken,
               ]);
            }

            // Salva o token de acesso na sessão (somente para uso temporário durante a sessão do navegador)
            //$session->set('access_token', $this->client->getAccessToken());

            $this->authLibrarie->loginGoogle($googleUser->email);

            //return $this->respond($data);
            return redirect()->to(base_url('admin'));
         }

         throw new RuntimeException('O código de autorização não foi encontrado.');
      } catch (Exception $e) {
         return $this->fail($e->getMessage());
      }
   }

   private function getUserByEmail(string $email): ?array
   {
      $modelUsuario = new UsuarioModel();
      return $modelUsuario->where('email', $email)->first();
   }

   private function createUser(array $data): void
   {
      try {
         $modelEmpresa = new EmpresaModel();
         $modelUsuario = new UsuarioModel();

         // Insere uma nova empresa e obtém o ID gerado
         $idEmpresa = $modelEmpresa->insert(['nome' => 'Empresa']);
         $token = $this->uuid->toString();
         $code = random_int(100000, 999999);

         $data['empresa_id'] = $idEmpresa;
         $data['token'] = $token;
         $data['code'] = $code;

         $modelUsuario->insert($data);
      } catch (Exception $e) {
         throw new RuntimeException($e->getMessage());
      }
   }

   private function updateUserRefreshToken(int $id, string $refreshToken): void
   {
      $modelUsuario = new UsuarioModel();
      try {
         $modelUsuario->update($id, ['refresh_token' => $refreshToken]);
      } catch (\ReflectionException $e) {
         throw new RuntimeException($e->getMessage());
      }
   }
}

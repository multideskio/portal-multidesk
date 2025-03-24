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
use Config\Database;
use Config\Services;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Google_Client;
use Google_Service_Oauth2;


/**
 * Classe responsável por gerenciar as operações de login, registro, verificação de conta e recuperação de senha.
 *
 * Estende o ResourceController e utiliza a ResponseTrait para lidar com respostas HTTP. A classe
 * também integra bibliotecas para autenticação, integração com API do Google, manipulação UUID e cache.
 */
class Login extends ResourceController
{
   use ResponseTrait;

   /**
    * Biblioteca de autenticação utilizada no sistema.
    */
   protected AuthLibrarie $authLibrarie;
   /**
    *
    */
   private UuidInterface $uuid;
   /**
    *
    */
   private Google_Client $client;

   /**
    *
    */
   private ?object $cache ;

   /**
    * Construtor da classe responsável pela inicialização de bibliotecas de autenticação e configuração do cliente da API Google.
    *
    * @return void
    */
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

      $this->cache = Services::cache();

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
      $db = Database::connect();

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
    * Atualiza a senha de um usuário no sistema com base no token ou e-mail fornecido.
    *
    * Este méthodo verifica se o token informado é válido ou, caso contrário, busca o
    * usuário com base no e-mail fornecido. Se as informações forem válidas, ele gera
    * um novo token, um código de verificação e atualiza a senha utilizando um hash seguro.
    *
    * @return ResponseInterface Retorna uma resposta indicando o sucesso ou falha da operação.
    */
   public function novaSenha(): ResponseInterface
   {
      try {
         $input = $this->request->getPost();
         $modelUsuario = new UsuarioModel();
         // Verifica se o token corresponde a algum usuário
         $usuario = $modelUsuario->where('token', $input['token'])->first();
         if (!$usuario) {
            // Caso o token não seja encontrado, busca o usuário pelo email
            $usuario = $modelUsuario->where('email', $input['email'])->first();
            if (!$usuario) {
              throw new RuntimeException('Token ou e-mail inválidos. Por favor, solicite uma nova recuperação de conta e tente novamente.'); // Retorna erro se o usuário não for encontrado
            }
         }
         // Gera um novo token e código de verificação
         $token = $this->uuid->toString();
         $code = random_int(100000, 999999);
         $data = [
            'code' => $code, // Atualiza o código de verificação
            'token' => $token, // Atualiza o token
            'senha' => PASSWORD_HASH($input['password'], PASSWORD_DEFAULT), // Atualiza a senha com hash
         ];
         $modelUsuario->update($usuario['id'], $data); // Atualiza os dados do usuário no banco
         return $this->respond(['message' => 'Nova senha enviada com sucesso']); // Retorna mensagem de sucesso
      } catch (Exception $e) {
         return $this->fail($e->getMessage()); // Retorna mensagem de erro em caso de exceção
      }
   }

   /**
    * Lida com a autenticação e autorização do Google OAuth 2.0.
    *
    * Este méthodo verifica o cache para um usuário autenticado utilizando um "Refresh Token",
    * tenta renovar o "Access Token" e autentica o usuário automaticamente. Caso não encontre
    * tokens válidos, redireciona o usuário para a página de login do Google.
    *
    * @return RedirectResponse Um redirecionamento para a área autenticada da aplicação ou para a página de login do Google.
    */
   public function google(): RedirectResponse
   {
      $cacheKey = $this->getCacheKey(); // Gera a chave única para o contexto atual
      $userId = $this->cache->get($cacheKey);

      if ($userId) {
         $modelUsuario = new UsuarioModel();
         $user = $modelUsuario->find($userId);

         if ($user && !empty($user['refresh_token'])) {
            try {
               log_message('info', 'Usuário encontrado no cache. Tentando renovar Access Token.');

               // Renova o Access Token através do Refresh Token
               $this->client->fetchAccessTokenWithRefreshToken($user['refresh_token']);
               $accessToken = $this->client->getAccessToken();

               if (isset($accessToken['access_token'])) {
                  $this->cache->save('access_token_' . $cacheKey, $accessToken['access_token'], 3600);

                  $this->authLibrarie->loginGoogle($user['email']);
                  return redirect()->to(base_url('admin'));
               }
            } catch (\Exception $e) {
               log_message('error', 'Erro ao renovar Access Token: ' . $e->getMessage());
            }
         }
      }

      log_message('info', 'Usuário não autenticado ou Refresh Token ausente. Redirecionando ao Google.');
      $authUrl = $this->client->createAuthUrl();
      return redirect()->to($authUrl);
   }

   /**
    * Recupera o usuário autenticado a partir do cache.
    * Caso o usuário não seja encontrado, registra um erro no log.
    *
    * @return array|null Retorna os dados do usuário autenticado ou null se não encontrado.
    */
   private function getAuthenticatedUser(): ?array
   {
      $cacheKey = $this->getCacheKey();
      $userId = $this->cache->get($cacheKey);

      if ($userId) {
         return (new UsuarioModel())->find($userId);
      }

      log_message('error', 'Usuário autenticado não encontrado no cache.');
      return null;
   }

   /**
    * Gerencia o callback do Google após autenticação, validando o código recebido, obtendo o token de acesso
    * e criando ou atualizando informações do usuário. Realiza login no sistema após sucesso no processo.
    *
    * @return ResponseInterface
    */
   public function callbackGoogle(): ResponseInterface
   {
      try {
         $code = $this->request->getVar('code'); // Código recebido do Google

         log_message('info', 'Callback recebido do Google. Iniciando validação do código.');

         if ($code) {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            // Verifica se houve erro ao obter o token
            if (isset($token['error'])) {
               throw new RuntimeException($token['error']);
            }

            // Define o token no cliente
            $this->client->setAccessToken($token);

            // Obter o Refresh Token
            $refreshToken = $token['refresh_token'] ?? null;

            // Obter informações do usuário pelo Google API
            $googleService = new Google_Service_Oauth2($this->client);
            $googleUser = $googleService->userinfo->get();

            // Verifica se o usuário já existe no banco
            $user = $this->getUserByEmail($googleUser->email);

            if ($user) {
               log_message('info', "Usuário encontrado no banco: {$user['email']}");
               if ($refreshToken) {
                  $this->updateUserRefreshToken($user['id'], $refreshToken);
               }
            } else {
               log_message('info', 'Criando um novo usuário. Salvando informações...');
               $this->createUser([
                  'nome' => $googleUser->name,
                  'email' => $googleUser->email,
                  'foto' => $googleUser->picture,
                  'google_id' => $googleUser->id,
                  'refresh_token' => $refreshToken,
               ]);

               $user = $this->getUserByEmail($googleUser->email);
            }

            // Salvar o ID do usuário no cache com uma chave única
            $cacheKey = $this->getCacheKey();
            $this->cache->save($cacheKey, $user['id'], 86400 * 365); // Cache válido por 24 horas

            // Realiza o login no sistema
            $this->authLibrarie->loginGoogle($googleUser->email);

            log_message('info', "Login pelo Google bem-sucedido para o usuário: {$googleUser->email}");

            return redirect()->to(base_url('admin'));
         }

         throw new RuntimeException('O código de autorização não foi encontrado.');
      } catch (Exception $e) {
         log_message('error', 'Erro no callback do Google: ' . $e->getMessage());
         return $this->fail($e->getMessage());
      }
   }

   /**
    * @param string $email O e-mail do usuário a ser buscado.
    * @return array|null Retorna os dados do usuário como um array se encontrado, ou null se não encontrado.
    */
   private function getUserByEmail(string $email): ?array
   {
      $modelUsuario = new UsuarioModel();
      return $modelUsuario->where('email', $email)->first();
   }

   /**
    * Cria um novo usuário e uma nova empresa associada.
    *
    * @param array $data Dados do usuário para inserção, contendo informações necessárias.
    * @return void
    * @throws RuntimeException Caso ocorra um erro durante o processo de inserção.
    */
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

   /**
    * Atualiza o token de atualização do usuário fornecido pelo ID.
    *
    * @param int $id ID do usuário a ser atualizado.
    * @param string $refreshToken Novo token de atualização a ser associado ao usuário.
    * @return void
    */
   private function updateUserRefreshToken(int $id, string $refreshToken): void
   {
      $modelUsuario = new UsuarioModel();
      try {
         $modelUsuario->update($id, ['refresh_token' => $refreshToken]);
      } catch (\ReflectionException $e) {
         throw new RuntimeException($e->getMessage());
      }
   }

   /**
    * Gera uma chave de cache única para identificar o usuário.
    *
    * @return string Uma string única gerada com base no identificador do navegador e no endereço IP do cliente.
    */
   private function getCacheKey(): string
   {
      // Pode ser um identificador único baseado em cookies, tokens de autenticação ou outro valor único
      $browserIdentifier = $this->request->getUserAgent(); // Identificador do navegador (opcional)
      $ipAddress = $this->request->getIPAddress(); // IP do cliente

      // Concatenar para criar uma chave única
      return 'user_id_' . md5($browserIdentifier . $ipAddress);
   }

   /**
    * Realiza o logout do usuário, removendo o cache associado e redirecionando para a página inicial.
    *
    * @return RedirectResponse Retorna uma resposta de redirecionamento para a página inicial.
    */
   public function logout(): RedirectResponse
   {
      // Remove o cache associado ao contexto
      $cacheKey = $this->getCacheKey();
      $this->cache->delete($cacheKey);
      $this->cache->delete('access_token_' . $cacheKey);

      log_message('info', 'Cache do usuário removido com sucesso no logout.');

      return redirect()->to(base_url());
   }

}

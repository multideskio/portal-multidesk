<?php

namespace App\Models;

use App\Libraries\AuthLibrarie;
use App\Libraries\EmailLibrarie;
use CodeIgniter\Model;
use Random\RandomException;
use ReflectionException;
use RuntimeException;
use Ramsey\Uuid\Uuid;

class UsuarioModel extends Model
{
   protected $table = 'usuarios';
   protected $primaryKey = 'id';
   protected $useAutoIncrement = true;
   protected $returnType = 'array';
   protected $useSoftDeletes = true;
   protected $protectFields = true;
   protected $allowedFields = [
      'empresa_id', 'nome', 'sobrenome', 'telefone', 'cpf',
      'email', 'senha', 'token', 'code', 'verificado',
      'foto', 'google_id', 'refresh_token', 'roles'
   ];

   protected bool $allowEmptyInserts = false;
   protected bool $updateOnlyChanged = true;

   protected array $casts = [];
   protected array $castHandlers = [];

   protected $useTimestamps = true;
   protected $dateFormat = 'datetime';
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';

   protected $validationRules = [];
   protected $validationMessages = [];
   protected $skipValidation = false;
   protected $cleanValidationRules = true;

   protected $allowCallbacks = true;
   protected $beforeInsert = [];
   protected $afterInsert = [];
   protected $beforeUpdate = [];
   protected $afterUpdate = [];
   protected $beforeFind = [];
   protected $afterFind = [];
   protected $beforeDelete = [];
   protected $afterDelete = [];

   /**
    * Verifica se o cliente existe, e se não, cria o cliente após a criação do usuário.
    *
    * @throws ReflectionException
    * @throws RandomException
    */
   public function verificarOuCriarCliente(array $data, array $carrinho): bool|array
   {
      $modelCliente = new ClienteModel();
      log_message('info', 'Iniciando verificação ou criação de cliente');

      // Verifica se o usuário já existe
      $user = $this->where('email', $data['email'])->first();

      if ($user) {
         log_message('info', 'Usuário encontrado com o email: ' . $data['email']);
         // Se o usuário existir, verifica ou cria o cliente para o usuário
         return $this->verificarOuCriarClienteParaUsuario($data, $user, $modelCliente);
      }

      // Caso o usuário não exista, cria o usuário e depois cria o cliente
      try {
         $user = $this->criarUsuario($data, $carrinho);
         // Cria o cliente após criar o usuário
         return $this->verificarOuCriarClienteParaUsuario($data, $user, $modelCliente);
      } catch (RandomException|ReflectionException $e) {
         throw new RuntimeException($e->getMessage());
      }
   }

   /**
    * Verifica se o cliente já existe para o usuário e cria o cliente se necessário.
    */
   private function verificarOuCriarClienteParaUsuario(array $data, array $user, ClienteModel $modelCliente): array
   {
      $cliente = $modelCliente->where('email', $user['email'])->first();

      if (is_null($cliente)) {
         log_message('info', 'Cliente não encontrado para o usuário ID: ' . $user['id']);
         return $this->criarClienteParaUsuario($data, $user, $modelCliente);
      }

      $modelCliente->update($cliente['id'], ['usuario_id' => $user['id']]);

      $auth = new AuthLibrarie();

      $auth->loginClienteNoPass($user['email']);

      return [
         'cliente' => $modelCliente->where('id', $cliente['id'])->first(),
         'user' => $user
      ];
   }

   /**
    * Cria um cliente após o usuário ser criado.
    */
   private function criarClienteParaUsuario(array $data, array $user, ClienteModel $modelCliente): array|null
   {
      $nome = strtok($data['nome'], ' ') ?: '';
      $sobrenome = trim(strstr($data['nome'], ' ', false)) ? ltrim(trim(strstr($data['nome'], ' ', false))) : '';

      $dataCliente = [
         'usuario_id' => $user['id'],
         'nome' => $nome,
         'sobrenome' => $sobrenome,
         'cpf' => preg_replace('/\D/', '', $data['cpf']),
         'email' => $data['email'],
         'telefone' => $data['telefone'],
         'endereco' => json_encode(array_intersect_key($data, array_flip(['cep', 'rua', 'numero', 'bairro', 'cidade', 'uf'])), JSON_THROW_ON_ERROR)
      ];

      try {
         $modelCliente->insert($dataCliente);
         log_message('info', 'Cliente inserido com sucesso para o usuário ID: ' . $user['id']);
         return [
            'cliente' => $modelCliente->where('usuario_id', $user['id'])->first(),
            'user' => $user
         ];
      } catch (ReflectionException $e) {
         log_message('error', 'Erro ao inserir cliente: ' . $e->getMessage());
         throw new RuntimeException($e->getMessage());
      }
   }

   /**
    * Cria um novo usuário no sistema.
    * @throws \JsonException
    */
   private function criarUsuario(array $data, array $carrinho): array|null
   {
      $empresaId = $this->obterEmpresaIdEvento($carrinho['evento_id']);
      $this->validarEmailExistente($data['email'], $carrinho);

      $dataUser = $this->prepararDadosUsuario($data, $empresaId);

      try {
         // Criação do usuário
         $this->insert($dataUser);

         // Obtém os dados do usuário recém-criado usando o e-mail
         $dataUserDb = $this->where('email', $data['email'])->first();

         if (!$dataUserDb) {
            throw new RuntimeException('Erro: Não foi possível recuperar o usuário recém-criado.');
         }

         // Envia o código de verificação por e-mail
         $this->enviarEmailBoasVindas($data['email'], $data['nome']);

         // Retorna os dados do usuário criado
         return $dataUserDb;
      } catch (\Exception $e) {
         throw new RuntimeException('Erro ao criar usuário ou enviar e-mail: ' . $e->getMessage());
      }
   }


   /**
    * Obtém o ID da empresa associada ao evento.
    */
   private function obterEmpresaIdEvento(int $eventoId): int
   {
      $modelEvent = new EventosModel();
      $rowEvent = $modelEvent->select('empresa_id')->where('id', $eventoId)->first();

      if (!$rowEvent) {
         throw new RuntimeException('Evento não encontrado.');
      }

      return $rowEvent['empresa_id'];
   }

   /**
    * Verifica se o email já está registrado, caso contrário, cria o cliente.
    */
   private function validarEmailExistente(string $email, array $carrinho): void
   {
      if ($this->where('email', $email)->countAllResults() > 0) {
         // Se o e-mail já existir, cria o cliente para o usuário
         $user = $this->where('email', $email)->first();
         $this->verificarOuCriarClienteParaUsuario(['email' => $email], $user, new ClienteModel());
         return;
      }
   }

   /**
    * Prepara os dados do usuário a ser criado.
    * @throws \JsonException
    */
   private function prepararDadosUsuario(array $data, int $empresaId): array
   {
      // Obtém o primeiro nome a partir do campo 'nome'
      $nome = strtok($data['nome'], ' ') ?: '';
      
      // Obtém o sobrenome a partir do campo 'nome'
      $sobrenome = trim(strstr($data['nome'], ' ', false)) ? ltrim(trim(strstr($data['nome'], ' ', false))) : '';
      
      return [
         // Define o email do usuário
         'email' => $data['email'],
         // Define a senha do usuário utilizando hash
         'senha' => password_hash(env('PASS_DEFAULT'), PASSWORD_DEFAULT),
         // Associa o ID da empresa ao usuário
         'empresa_id' => $empresaId,
         // Gera um token único para o usuário
         'token' => Uuid::uuid4()->toString(),
         // Gera um código aleatório de 6 dígitos
         'code' => random_int(100000, 999999),
         // Define o primeiro nome do usuário
         'nome' => $nome,
         // Define o sobrenome do usuário
         'sobrenome' => $sobrenome,
         // Define o telefone do usuário, se fornecido
         'telefone' => $data['telefone'] ?? '',
         // Remove todos os caracteres não numéricos do CPF
         'cpf' => preg_replace('/\D/', '', $data['cpf'] ?? ''),
         // Define o papel padrão do usuário como 'cliente'
         'roles' => json_encode(['cliente'], JSON_THROW_ON_ERROR),
      ];
   }

   /**
    * Envia um código de verificação por email.
    */
   private function enviarEmailBoasVindas(string $email, string $nome): void
   {
      $view = view('login/emails/boas_vindas_cliente', ['nome' => $nome, 'email' => $email]);
      $emailLibraries = new EmailLibrarie();

      try {
         // Envia o e-mail
         log_message('info', 'Enviando e-mail de boas-vindas para ' . $email);
         $emailLibraries->sendEmail($email, 'Seja Bem Vindo!', $view);
      } catch (\Exception $e) {
         // Log do erro, mas sem travar o código
         log_message('error', 'Erro ao enviar e-mail para ' . $email . ': ' . $e->getMessage());
         // Você pode optar por não lançar a exceção, apenas logar o erro
      }
   }
}

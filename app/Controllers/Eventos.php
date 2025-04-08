<?php

namespace App\Controllers;

use App\Gateway\Mp\MPProcessor;
use App\Gateway\Mp\PixHandler;
use App\Models\EmpresaGatewaysModel;
use App\Models\EventosModel;
use App\Models\ItensPedidoModel;
use App\Models\ParticipanteModel;
use App\Models\PedidoModel;
use App\Models\TransacoesModel;
use App\Models\UsuarioModel;
use App\Services\PagamentoService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Random\RandomException;
use ReflectionException;

class Eventos extends BaseController
{
   public function index($slug = null): string
   {
      // Configurações básicas (como título e descrição da página)
      $data['titulo'] = 'Evento';
      $data['descricao'] = 'Escolha seu ingresso';
      $data['slug'] = $slug;
      $this->session->set('slug', $slug);

      // Criação do modelo
      $modelEvento = new EventosModel();

      $data['evento'] = $modelEvento->getEventosSlug($slug);

      // Renderiza a view com os dados
      return view('public/evento', $data);
   }

   /**
    * @throws \JsonException
    */
   public function participantes($slug = null): string|RedirectResponse
   {
      $data['titulo'] = 'Evento';
      $data['descricao'] = 'Adicione participantes';
      $data['slug'] = $slug;

      $session = session();
      $eventoModel = new EventosModel();
      $eventosArray = $eventoModel->getEventosSlug($slug);

      if (empty($eventosArray) || !isset($eventosArray[0])) {
         return redirect()->back()->with('error', 'Evento não encontrado.');
      }

      $eventoRaw = $eventosArray[0];

      // ✅ DECODIFICAR campos extras
      if (!empty($eventoRaw['campos']) && is_string($eventoRaw['campos'])) {
         $eventoRaw['campos'] = json_decode($eventoRaw['campos'], true);
      }

      if (!$session->has('carrinho')) {
         $session->set('carrinho', []);
      }

      if ($this->request->getMethod() === 'POST') {
         $qtd = $this->request->getPost('qtd'); // array: [id_variacao => quantidade]

         if (!is_array($qtd)) {
            return redirect()->back()->with('error', 'Dados inválidos.');
         }

         $carrinho = [];
         $totalGeral = 0;

         foreach ($qtd as $idVariacao => $quantidade) {
            if ((int)$quantidade <= 0) {
               continue;
            }

            // Buscar os dados completos da variação dentro do evento
            $variacaoEncontrada = null;
            foreach ($eventoRaw['variacoes'] as $v) {
               if ((int)$v['id'] === (int)$idVariacao) {
                  $variacaoEncontrada = $v;
                  break;
               }
            }

            if ($variacaoEncontrada) {
               $subtotal = $quantidade * $variacaoEncontrada['preco'];
               $totalGeral += $subtotal;

               $carrinho[$idVariacao] = [
                  'id_variacao' => $idVariacao,
                  'nome' => $variacaoEncontrada['nome'],
                  'descricao' => $variacaoEncontrada['descricao'],
                  'preco' => $variacaoEncontrada['preco'],
                  'quantidade' => $quantidade,
                  'subtotal' => $subtotal
               ];
            }
         }

         if (empty($carrinho)) {
            return redirect()->back()->with('error', 'Escolha ao menos um ingresso.');
         }

         $session->set('carrinho', [
            'itens' => $carrinho,
            'total' => $totalGeral,
            'evento_id' => $eventoRaw['id'],
            'evento_titulo' => $eventoRaw['titulo'],
         ]);

         $data['carrinho'] = $session->get('carrinho');
         $data['evento'] = $eventoRaw;

         return view('public/participante', $data);
      }

      return redirect()->back()->with('error', 'Escolha uma variação de ingresso.');
   }

   public function confirmParticipante($slug = null): string|RedirectResponse
   {
      $input = $this->request->getPost();
      $session = session();

      if (!$session->has('carrinho')) {
         return redirect()->to('/evento/' . $slug)->with('error', 'Carrinho não encontrado.');
      }

      // Validação mínima
      if (
         !isset($input['nome'], $input['email'], $input['telefone'], $input['idEvento'], $input['idVariacao']) ||
         count($input['nome']) === 0
      ) {
         return redirect()->back()->with('error', 'Preencha todos os dados dos participantes.');
      }

      $participantes = [];

      foreach ($input['nome'] as $i => $nome) {
         $extras = [];

         // Se existirem campos extras enviados
         if (isset($input['extras'])) {
            foreach ($input['extras'] as $variacaoKey => $campoArray) {
               // Verifica se esse campo extra tem um valor para esse participante $i
               foreach ($campoArray as $campoNome => $valores) {
                  if (isset($valores[$i])) {
                     $extras[$campoNome] = $valores[$i];
                  }
               }
            }
         }

         $participantes[] = [
            'idEvento' => $input['idEvento'][$i],
            'idVariacao' => $input['idVariacao'][$i],
            'nome' => $nome,
            'email' => $input['email'][$i],
            'telefone' => $input['telefone'][$i],
            'extras' => $extras // array associativo com os campos extras
         ];
      }

      $session->set('participantes', $participantes);

      return redirect()->to('/checkout/' . $slug); // ✅ Redireciona com slug
   }


   public function carrinho(): string|RedirectResponse
   {
      // Configurações básicas (como título e descrição da página)
      $data['titulo'] = 'Evento';
      $data['descricao'] = 'Adicione participantes';

      $session = session();

      // Inicializa o carrinho na sessão, caso ele ainda não exista
      if (!$session->has('carrinho')) {
         $session->set('carrinho', []);
      }

      if (!$session->has('participantes')) {
         $session->set('participantes', []);
      }

      // Verifica se o méthodo da requisição é POST
      if ($this->request->getMethod() === 'post') {
         // Obtém os dados enviados pelo formulário
         $variacoes = $this->request->getPost();

         // Redireciona com uma mensagem de erro se os dados forem inválidos
         return redirect()->to('/carrinho')->with('erro', 'Os dados enviados são inválidos.');
      }

      // Renderiza a visualização do carrinho
      $carrinho = $session->get('carrinho');
      return view('public/carrinho', ['carrinho' => $carrinho]);
   }

   public function checkout(string $slug = null): string|RedirectResponse
   {
      $session = session();
      $carrinho = $session->get('carrinho');

      // Se não houver carrinho ou não houver itens no carrinho
      if (!$carrinho || empty($carrinho['itens'])) {
         $slugs = $this->session->get('slug');
         return redirect()->to("evento/$slugs")->with('erro', 'Seu carrinho está vazio. Escolha um ingresso.');
      }

      $data['event'] = ['slug' => $slug];
      return view('public/checkout', $data);
   }

   public function removerItem(): RedirectResponse
   {
      $session = session();
      $idVariacao = $this->request->getPost('id_variacao');

      $carrinho = $session->get('carrinho') ?? [];
      $participantes = $session->get('participantes') ?? [];

      // Remove o item do carrinho
      if (isset($carrinho['itens'][$idVariacao])) {
         unset($carrinho['itens'][$idVariacao]);

         // Recalcula total
         $novoTotal = 0;
         foreach ($carrinho['itens'] as $item) {
            $novoTotal += $item['subtotal'];
         }
         $carrinho['total'] = $novoTotal;
      }

      // Remove participantes que não têm mais variação válida
      $participantesFiltrados = array_filter($participantes, function ($p) use ($carrinho) {
         return isset($p['idVariacao']) && isset($carrinho['itens'][$p['idVariacao']]);
      });

      // Reindexa
      $participantesFiltrados = array_values($participantesFiltrados);

      $session->set('carrinho', $carrinho);
      $session->set('participantes', $participantesFiltrados);

      return redirect()->back()->with('msg', 'Item e participantes atualizados com sucesso.');
   }


   public function checkoutFinalizar()
   {
      $client = $this->request->getPost();
      $carrinho = $this->session->get('carrinho');
      $participantes = $this->session->get('participantes');

      if(empty($participantes)){
         return redirect()->to('login');
      }

      if(empty($carrinho)){
         return redirect()->to('login');
      }

      $modelUser = new UsuarioModel();

      try {
         $verificaDados = $modelUser->verificarOuCriarCliente(esc($client), $carrinho);
      } catch (RandomException|\ReflectionException $e) {
         log_message('error', 'File: ' . __FILE__ . ' - Line: ' . __LINE__ . ' - Error: ' . $e->getMessage());
         return redirect()->to('login');
      }

      $modelOrder = new PedidoModel();

      // Total do pedido (obtido do carrinho)
      $totalPedido = $carrinho['total'];
      $eventoId = $carrinho['evento_id'];  // ID do evento
      $metodoPagamento = $client['metodo_pagamento'];  // Méthodo de pagamento selecionado

      try {
         // Cria o pedido no banco e obtém o ID do pedido
         $orderId = $modelOrder->createOrder(
            $verificaDados['cliente']['id'],  // ID do cliente
            $eventoId,       // ID do evento
            $totalPedido,    // Total do pedido
            $metodoPagamento // Méthodo de pagamento
         );

      } catch (\Exception $e) {
         try {
            $orderId = $modelOrder->createOrder(
               $verificaDados['cliente']['id'],  // ID do cliente
               $eventoId,       // ID do evento
               $totalPedido,    // Total do pedido
               $metodoPagamento // Méthodo de pagamento
            );
         } catch (\Exception $e) {
            log_message('error', 'File: ' . __FILE__ . ' - Line: ' . __LINE__ . ' - Error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
         }
      }

      $modelItemPedido = new ItensPedidoModel(); // certifique-se que existe
      $modelItemPedido->cadastrarItens($carrinho, $orderId);

      $modelParticipantes = new ParticipanteModel();
      try {
         $modelParticipantes->cadastrarParticipantesEIngressos($participantes, $orderId);
      } catch (\JsonException $e) {
         log_message('error', 'File: ' . __FILE__ . ' - Line: ' . __LINE__ . ' - Error: ' . $e->getMessage());
         return $this->response->setJSON(['error' => $e->getMessage()]);
      }

      $pagamento = $this->processarPagamento($client, $carrinho, $orderId);


      $data = [
         //'session' => $this->session->get('data'),
         //'vDados' => $verificaDados,
         'pix' => $pagamento,
         //'idPedido' => $orderId,
         //'client' => $client,
         //'carrinho' => $carrinho,
         //'participantes' => $participantes,
      ];

      return $this->response->setJSON($data);
     /* echo "<pre>";
      print_r($data);*/
   }


   protected function getCredenciaisEmpresa(int $eventoId): array
   {
      $eventoModel = new EventosModel();
      $evento = $eventoModel->find($eventoId);

      if (!$evento) {
         throw new \RuntimeException("Evento não encontrado.");
      }

      $empresaId = $evento['empresa_id'];

      $gatewayModel = new EmpresaGatewaysModel();
      $credenciais = $gatewayModel->where([
         'empresa_id' => $empresaId,
         'gateway' => 'mercadopago',
         'ativo' => 1
      ])->first();

      if (!$credenciais) {
         throw new \RuntimeException("Credenciais do Mercado Pago não encontradas para a empresa.");
      }

      if(!empty($credenciais['sandbox'])){
         return [
            'access_token' => $credenciais['access_token_test'],
            'public_key' => $credenciais['public_key_test'] ?? null,
            'empresa_id' => $empresaId,
         ];
      }

      return [
         'access_token' => $credenciais['access_token'],
         'public_key' => $credenciais['public_key'] ?? null,
         'empresa_id' => $empresaId,
      ];
   }


   /**
    * Realiza o processamento do pagamento baseado no método de pagamento informado pelo cliente.
    *
    * @param array $client Dados do cliente, incluindo nome, CPF, email, endereço e método de pagamento.
    * @param array $carrinho Informações do carrinho, como ID do evento, valor total e detalhes do produto.
    * @param int $idPedido ID único associado ao pedido a ser processado.
    * @return bool|ResponseInterface Retorna true se o pagamento for processado com sucesso, ou um objeto JSON com mensagem de erro e código HTTP em caso de falha.
    */
   private function processarPagamento(array $client, array $carrinho, int $idPedido): bool|\CodeIgniter\HTTP\ResponseInterface
   {
      log_message('info', 'Iniciando processamento de pagamento para o pedido ID: ' . $idPedido);
      $eventoId = (int) $carrinho['evento_id'];
      try {
         switch ($client['metodo_pagamento']) {
            case 'pix':
               log_message('info', 'Processando pagamento via PIX para o pedido ID: ' . $idPedido);
               return (new PagamentoService())->processarPixMercadoPago($client, $carrinho, $idPedido, $this->getCredenciaisEmpresa($eventoId));
               //return $this->processPixMp($client, $carrinho, $idPedido);

            case 'credit_card':
               log_message('info', 'Processando pagamento via cartão de crédito para o pedido ID: ' . $idPedido);
               return $this->cartaoCredito($client, $carrinho, $idPedido);

            case 'boleto':
               log_message('info', 'Processando pagamento via boleto para o pedido ID: ' . $idPedido);
               return $this->boleto($client, $carrinho, $idPedido);

            default:
               log_message('error', 'Método de pagamento inválido para o pedido ID: ' . $idPedido);
               return $this->response->setJSON([
                  'status' => 'erro',
                  'mensagem' => 'Método de pagamento inválido.'
               ])->setStatusCode(400);
         }

      } catch (Exception $e) {
         log_message('error', 'Erro ao processar pagamento para o pedido ID: ' . $idPedido . '. Mensagem: ' . $e->getMessage());
         return $this->response->setJSON([
            'status' => 'erro',
            'mensagem' => $e->getMessage()
         ])->setStatusCode(500);
      }
   }

   /**
    * Processa o pagamento via PIX utilizando o gateway MercadoPago.
    *
    * @param array $client Informações do cliente, incluindo nome, CPF, email e endereço.
    * @param array $carrinho Detalhes do carrinho, como ID do evento, valor total e título do evento.
    * @param int $idPedido ID único do pedido a ser processado.
    * @return bool Retorna true se o pagamento for processado com sucesso, ou false em caso de falha.
    */
   public function processPixMp(array $client, array $carrinho, int $idPedido): bool
   {
      $eventoId = (int) $carrinho['evento_id'];
      $total = (float) $carrinho['total'];

      // Buscar credenciais via evento → empresa → gateway
      $credenciais = $this->getCredenciaisEmpresa($eventoId);

      $empresaId = $credenciais['empresa_id'];
      $gateway   = 'mercadopago';
      $tipo      = 'pix';

      $pedido = [
         'valor'     => $carrinho['total'],
         'descricao' => $carrinho['evento_titulo'],
      ];
      try{
         $nome = strtok($client['nome'], ' ') ?: '';
         $sobrenome = trim(strstr($client['nome'], ' ', false)) ? ltrim(trim(strstr($client['nome'], ' ', false))) : '';

         $cliente = [
            'cpf' => preg_replace('/\D/', '', $client['cpf']),
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $client['email'],
            'cep' => preg_replace('/\D/', '', $client['cep']),
            'logradouro' => $client['rua'],
            'numero' => $client['numero'],
            'bairro' => $client['bairro'],
            'cidade' => $client['cidade'],
            'uf' => $client['uf'],
         ];

         $mp = new MPProcessor();
         $resposta = $mp->processar($tipo, $empresaId, $pedido, $cliente, $gateway);


         $modelTrans = new TransacoesModel();
         $modelTrans->salvarTransacao($idPedido, $empresaId, $resposta);


         return true;
      }catch (\Exception $e){
         log_message('error', 'Erro ao processar pagamento para o pedido ID: ' . $idPedido . '. Mensagem: ' . $e->getMessage());
      return false;
      }
   }





   private function cartaoCredito(array $client, array $carrinho, int $idPedido): true
   {
      return true;
   }
   private function boleto(array $client, array $carrinho, int $idPedido): true
   {
      return true;
   }
}
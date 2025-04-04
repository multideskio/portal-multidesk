<?php

namespace App\Controllers;

use App\Models\EventosModel;
use CodeIgniter\HTTP\RedirectResponse;

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
                  'nome'        => $variacaoEncontrada['nome'],
                  'descricao'   => $variacaoEncontrada['descricao'],
                  'preco'       => $variacaoEncontrada['preco'],
                  'quantidade'  => $quantidade,
                  'subtotal'    => $subtotal
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
            'idEvento'    => $input['idEvento'][$i],
            'idVariacao'  => $input['idVariacao'][$i],
            'nome'        => $nome,
            'email'       => $input['email'][$i],
            'telefone'    => $input['telefone'][$i],
            'extras'      => $extras // array associativo com os campos extras
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


   public function teste()
   {
      echo "<pre>";
      print_r($_POST);
      print_r($this->session->get('carrinho'));
      print_r($this->session->get('participantes'));

   }
}
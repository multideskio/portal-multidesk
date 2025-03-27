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

      // Criação do modelo
      $modelEvento = new EventosModel();

      $data['evento'] = $modelEvento->getEventosSlug($slug);

      // Renderiza a view com os dados
      return view('public/evento', $data);
   }

   public function participantes($slug = null): string|RedirectResponse
   {
      // Configurações básicas (como título e descrição da página)
      $data['titulo'] = 'Evento';
      $data['descricao'] = 'Adicione participantes';
      $data['slug'] = $slug;
      // Criação do modelo
      $modelEvento = new EventosModel();
      $data['evento'] = $modelEvento->getEventosSlug($slug);
      $session = session();
      // Inicializa o carrinho na sessão, caso ele ainda não exista
      if (!$session->has('carrinho')) {
         $session->set('carrinho', []);
      }
      // Verifica se o méthodo da requisição é POST
      if ($this->request->getMethod() === 'POST') {
         $input = $this->request->getPost();
         $total = $input['precoVariacao'] * $input['qtd'];
         $js = [
            'total' => $total,
            'method' => $this->request->getMethod(),
            'data' => $input,
         ];
         $session->set('carrinho', $js);
         $data['carrinho'] = $session->get('carrinho');
         return view('public/participante', $data);
      }
      return redirect()->back()->with('error', 'Escolha uma variação de ingresso');
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

   /**
    * @TODO IMPLANTAR LOGICA DE PARTICIPANTES - CRIAR TABELA, MODEL E LÓGICA, LEMBRAR QUE O INGRESSO É SEPARADO DO PARTICIPANTE
    */
   public function confirmParticipante($slug = null)
   {

      $input = $this->request->getPost();

      $session = session();

      $session->set('participantes', $input);

      $data['carrinho'] = $session->get('carrinho');
      $data['participantes'] = $session->get('participantes');

      foreach ($input['idVariacao'] as $key => $value) {
         $values[$key] = [
            'idVariacao' => $value,
            'idEvento' => $input['idEvento'][$key],
            'nome' => $input['nome'][$key],
            'email' => $input['email'][$key],
            'telefone' => $input['telefone'][$key],
         ];
      }

      echo "<pre>";
      print_r($values);
      echo "</pre>";
   }

   public function checkout(): string|RedirectResponse
   {

      return view('public/confirmar');
   }
}
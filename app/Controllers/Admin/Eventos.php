<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventosModel;
use App\Models\VariacoesModel;

class Eventos extends BaseController
{
   public function index(): string
   {
      $data['page'] = 'Eventos';
      $data['titulo'] = 'Eventos';
      $data['descricao'] = 'Visão geral dos eventos';
      return view('admin/eventos/home', $data);
   }

   public function novo(): string
   {
      $data['page'] = 'Eventos';
      $data['titulo'] = 'Novo evento';
      $data['descricao'] = 'Cadastro de novo evento';
      return view('admin/eventos/novo', $data);
   }

   public function lista(): string
   {
      $data['page'] = 'Eventos';
      $data['titulo'] = 'Lista de eventos';
      $data['descricao'] = 'Lista de eventos cadastrados';

      $modelEventos = new EventosModel();
      $modelVariacoes = new VariacoesModel();

      // Implement pagination for the list of events
      $perPage = 10; // Number of items per page

      $currentPage = $this->request->getGet('page') ?? 1; // Get the current page or default to 1
      $orderBy = $this->request->getPost('order') === 'ASC' ? 'ASC' : 'DESC';

      $builde = $modelEventos->orderBy('id', $orderBy);

      $data['total'] = $builde->countAllResults(); // Total events count
      $data['eventos'] = $builde->paginate($perPage, 'default', $currentPage); // Paginate events
      $data['pager'] = $builde->pager; // Get the pagination links

      $data['variacoesModel'] = $modelVariacoes;

      return view('admin/eventos/lista', $data);
   }

   public function editar($id = null): string
   {
      $data['page'] = 'Eventos';
      $data['titulo'] = 'Editar evento';
      $data['descricao'] = 'Editar evento';
      return view('admin/eventos/editar', $data);
   }

   public function participantes($id = null): string
   {
      $data['page'] = 'Eventos';
      $data['titulo'] = 'Participantes';
      $data['descricao'] = 'Lista de participantes';
      return view('admin/eventos/participantes', $data);
   }
}
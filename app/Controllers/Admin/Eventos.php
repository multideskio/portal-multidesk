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

      $page = $this->request->getGet('page') ?? 1 ;
      $data['eventos'] = $modelEventos->getEventosPaginate($page);

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
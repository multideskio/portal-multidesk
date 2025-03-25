<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Cursos extends BaseController
{
   public function index(): string
   {
      $data['titulo'] = 'Cursos';
      $data['descricao'] = 'Visão geral dos cursos';
      return view('admin/cursos/home', $data);
   }
   public function novo(): string
   {
      $data['titulo'] = 'Novo curso';
      $data['descricao'] = 'Cadastro de novo curso';
      return view('admin/cursos/novo', $data);
   }
   public function lista(): string
   {
      $data['titulo'] = 'Lista de cursos';
      $data['descricao'] = 'Lista de cursos cadastrados';
      return view('admin/cursos/lista', $data);
   }
   public function editar($id = null): string
   {
      $data['titulo'] = 'Editar curso';
      $data['descricao'] = 'Editar curso';
      return view('admin/cursos/editar', $data);
   }
   public function participantes($id = null): string{
      $data['titulo'] = 'Participantes';
       $data['descricao'] = 'Lista de participantes';
       return view('admin/cursos/participantes', $data);
   }
}
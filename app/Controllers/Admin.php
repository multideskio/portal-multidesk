<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
   public function index(): string
   {
      $data['titulo'] = 'Dashboard';
      $data['descricao'] = 'Acampanhe sua plataforma';
      return view('admin/dashboard/home', $data);
   }
    public function indexTest(): ResponseInterface
    {
       $data = $this->session->get('data');
       
       $cache = service('cache');

       if (!empty($cache)) {
          $cache->save('data', $data, 300);
       }

       return $this->response->setJSON([
          'status' => 'success',
          'message' => 'Data has been cached successfully.',
          'data' => $cache->get('data')
       ]);
    }
}

<?php

namespace App\Controllers\Clientes;

use App\Controllers\BaseController;

class Home extends BaseController
{
   public function index(): string
   {
      return view('clientes/dashboard/home');
   }
}
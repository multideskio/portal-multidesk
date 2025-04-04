<?php

namespace App\Controllers\Admin\V1;

use App\Gateway\Sicred\GatewaySicred;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;


class Sicred extends ResourceController
{
   use ResponseTrait;
   protected GatewaySicred $gatewaySicred;
   public function __construct(){
      $this->gatewaySicred = new GatewaySicred();
   }

   public function index(): ResponseInterface{
      return $this->respond(['gateway' => 'SICRED']);
   }
}

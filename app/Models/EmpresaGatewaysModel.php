<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class EmpresaGatewaysModel extends Model
{
    protected $table            = 'empresa_gateways';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
   protected $allowedFields = [
      'empresa_id',
      'sandbox',
      'url_sandbox',
      'url_producao',
      'gateway',
      'public_key',
      'access_token',
      'public_key_test',
      'access_token_test',
      'ativo'
   ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

   public function getCredenciaisAtivas(int $empresaId, string $gateway): ?array
   {
      $gateway = $this->where(['empresa_id' => $empresaId, 'gateway' => $gateway, 'ativo' => 1])->findAll();
      if (empty($gateway)) {
         throw new RuntimeException('Gateway não encontrado.');
      }
      $gateway = $gateway[0];  // Pegando a primeira linha da consulta
      return [
         'access_token' => $gateway['sandbox'] ? $gateway['access_token_test'] : $gateway['access_token'],
         'public_key' => $gateway['sandbox'] ? $gateway['public_key_test'] : $gateway['public_key'],
         'sandbox' => (bool) $gateway['sandbox'],
         'url_sandbox' => $gateway['url_sandbox'],
         'url_producao' => $gateway['url_producao']
      ];
   }
}

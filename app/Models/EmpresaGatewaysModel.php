<?php

namespace App\Models;

use CodeIgniter\Model;

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
      'access_token',
      'public_key',
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
      return $this->where([
         'empresa_id' => $empresaId,
         'gateway' => $gateway,
         'ativo' => 1
      ])->first();
   }
}

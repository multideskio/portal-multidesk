<?php

namespace App\Models;

use CodeIgniter\Model;

class ItensPedidoModel extends Model
{
    protected $table            = 'itens_pedido';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
   protected $allowedFields = [
      'pedido_id',
      'variacao_evento_id',
      'quantidade',
      'preco_unitario',
      'subtotal',
      'created_at',
      'updated_at',
      'deleted_at',
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


    public function cadastrarItens($carrinho, $orderId): bool|int
    {
       $itemData = [];
       foreach ($carrinho['itens'] as $item) {
          $itemData[] = [
             'pedido_id' => $orderId,
             'variacao_evento_id' => $item['id_variacao'],
             'quantidade' => $item['quantidade'],
             'preco_unitario' => $item['preco'],
             'subtotal' => $item['subtotal'],
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s'),
          ];
       }
       try {
          $this->where('pedido_id', $orderId)->delete(); // limpa antes
          return $this->insertBatch($itemData);
       } catch (\ReflectionException $e) {
          log_message('error', $e->getMessage());
          return false;
       }
    }
}

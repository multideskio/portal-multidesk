<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class PedidoModel extends Model
{
   protected $table = 'pedidos';
   protected $primaryKey = 'id';
   protected $useAutoIncrement = true;
   protected $returnType = 'array';
   protected $useSoftDeletes = true;
   protected $protectFields = true;
   protected $allowedFields = [
      'cliente_id',
      'evento_id',
      'status',
      'total',
      'metodo_pagamento',
      'slug'
   ];

   protected bool $allowEmptyInserts = false;
   protected bool $updateOnlyChanged = true;

   protected array $casts = [];
   protected array $castHandlers = [];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat = 'datetime';
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';

   // Validation
   protected $validationRules = [];
   protected $validationMessages = [];
   protected $skipValidation = false;
   protected $cleanValidationRules = true;

   // Callbacks
   protected $allowCallbacks = true;
   protected $beforeInsert = ['beforeInsert'];
   protected $afterInsert = [];
   protected $beforeUpdate = [];
   protected $afterUpdate = [];
   protected $beforeFind = [];
   protected $afterFind = [];
   protected $beforeDelete = [];
   protected $afterDelete = [];

   public function atualizarStatus(int $idPedido, string $status): bool
   {
      return $this->update($idPedido, [
         'status' => $status,
         'updated_at' => date('Y-m-d H:i:s') // se tiver esse campo na tabela
      ]);
   }


   protected function beforeInsert($data): array{
      $data['data']['slug'] = Uuid::uuid4()->toString();
      return $data;
   }

   // Méthodo para criar um novo pedido
   public function createOrder($clienteId, $eventoId, $total, $metodoPagamento = null): false|int|string
   {
      $session = session();
      $data = [
         'cliente_id' => $clienteId,
         'evento_id' => $eventoId,
         'status' => 'aguardando',
         'total' => $total,
         'metodo_pagamento' => $metodoPagamento,
      ];

      try {
         if ($sessionId = $session->get('idPedidoCache')) {
            $data['id'] = $sessionId;
            $num = $this->where('id', $sessionId)->countAllResults();
            if ($num === 0) {
               return $this->insertOrder($data, $session);
            }
            return $this->updateOrder($sessionId, $data) ? $sessionId : false;
         }
         return $this->insertOrder($data, $session);
      } catch (\Exception $e) {
         log_message('error', sprintf('[%s:%d] %s', __FILE__, __LINE__, $e->getMessage()));
      }

      return false;
   }

   private function updateOrder($id, array $data): bool
   {
      try {
         return $this->update($id, $data);
      } catch (\ReflectionException $e) {
         log_message('error', $e->getMessage());
         return false;
      }
   }

   private function insertOrder(array $data, $session): false|int|string
   {
      try {
         if ($this->insert($data)) {
            $session->remove('idPedidoCache');
            $insertedId = $this->getInsertID();
            $session->set('idPedidoCache', $insertedId);
            return $insertedId;
         }
      } catch (\ReflectionException $e) {
         log_message('error', $e->getMessage());
      }

      return false;
   }

   // Méthodo para atualizar o status do pedido
   public function updateOrderStatus($orderId, $status): bool
   {
      $data = [
         'status' => $status,
         'updated_at' => date('Y-m-d H:i:s'),
      ];

      try {
         return $this->update($orderId, $data);
      } catch (\ReflectionException $e) {
         log_message('error', $e->getMessage());
         return false;
      }  // Atualiza o status do pedido
   }

   // Méthodo para buscar um pedido com as informações de cliente e evento
   public function getOrderDetails($orderId): object|array|null
   {
      return $this->asArray()
         ->select('pedidos.*, usuarios.nome AS cliente_nome, eventos.nome AS evento_nome')
         ->join('usuarios', 'usuarios.id = pedidos.cliente_id')
         ->join('eventos', 'eventos.id = pedidos.evento_id')
         ->where('pedidos.id', $orderId)
         ->first();
   }
}

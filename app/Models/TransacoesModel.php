<?php

namespace App\Models;

use CodeIgniter\Model;
use JsonException;
use MercadoPago\Resources\Payment;
use ReflectionException;

class TransacoesModel extends Model
{
    protected $table            = 'transacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
       'pedido_id',
       'empresa_id',
       'gateway',
       'referencia_gateway',
       'status',
       'valor',
       'moeda',
       'tipo_pagamento',
       'detalhes_pagamento',
       'payload',
       'tentativa_webhook',
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

   public function salvarTransacao(int $idPedido, int $empresaId, Payment $payment): bool
   {
      try {
         log_message('info', 'Iniciando salvamento da transação para o pedido ID: ' . $idPedido);

         $result = $this->insert([
            'pedido_id' => $idPedido,
            'empresa_id' => $empresaId,
            'gateway' => 'mercadopago',
            'referencia_gateway' => $payment->id,
            'status' => $payment->status, // Ex: approved, pending
            'valor' => $payment->transaction_amount,
            'moeda' => $payment->currency_id ?? 'BRL',
            'tipo_pagamento' => $payment->payment_type_id ?? 'pix',
            'detalhes_pagamento' => json_encode($payment->point_of_interaction ?? [], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'payload' => json_encode($payment, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
         ]);

         if ($result) {
            log_message('info', 'Transação salva com sucesso para o pedido ID: ' . $idPedido);
         } else {
            log_message('warning', 'Falha ao salvar a transação para o pedido ID: ' . $idPedido);
         }

         return $result;
      } catch (ReflectionException|JsonException $e) {
         log_message('error', 'Erro ao salvar a transação para o pedido ID: ' . $idPedido . '. Mensagem: ' . $e->getMessage());
         return false;
      }
   }

}

<?php

namespace App\Gateway\Mp;

use App\Models\EmpresaGatewaysModel as GatewayCredentialModel;
use Exception;
use RuntimeException;

class MPProcessor
{
   protected GatewayCredentialModel $gatewayModel;

   public function __construct()
   {
      $this->gatewayModel = new GatewayCredentialModel();
   }

   /**
    * Processa o pagamento com base no tipo (pix, card, etc)
    * @throws RuntimeException
    */
   public function processar(string $tipo, int $empresaId, array $pedido, array $cliente): \MercadoPago\Resources\Payment
   {
      // Validação do pedido e cliente
      if (!isset($pedido['valor']) || $pedido['valor'] <= 0) {
         throw new RuntimeException("Valor do pedido inválido.");
      }

      if (empty($cliente['cpf']) || empty($cliente['email'])) {
         throw new RuntimeException("Dados do cliente incompletos.");
      }

      // Busca as credenciais ativas para o gateway
      $credenciais = $this->gatewayModel->getCredenciaisAtivas($empresaId, 'mercadopago');

      if (!$credenciais) {
         throw new RuntimeException("Credenciais do Mercado Pago não encontradas ou inativas.");
      }

      $accessToken = $credenciais['access_token'];

      // Processamento com base no tipo de pagamento
      switch ($tipo) {
         case 'pix':
            return (new PixHandler($accessToken))->gerarPagamento($pedido, $cliente);

         case 'card':
            // Futuro: implementar CardHandler
            throw new RuntimeException("Pagamento com cartão ainda não implementado.");

         default:
            throw new RuntimeException("Tipo de pagamento inválido.");
      }
   }
}

<?php

namespace App\Gateway\Mp;

use App\Models\EmpresaGatewaysModel as GatewayCredentialModel;
use Exception;
use JsonException;
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
    * @throws JsonException
    */
   public function processar(string $tipo, int $empresaId, array $pedido, array $cliente, $gateway): array
   {
      $credenciais = $this->gatewayModel->getCredenciaisAtivas($empresaId, $gateway);

      if (!$credenciais) {
         throw new RuntimeException("Credenciais do Mercado Pago não encontradas ou inativas.");
      }

      $accessToken = $credenciais['access_token'];

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

<?php

namespace App\Gateway\Mp;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use Exception;
use JsonException;

class PixHandler
{
   protected CURLRequest $client;

   public function __construct(string $accessToken, bool $sandbox = true, ?string $urlSandbox = null, ?string $urlProducao = null)
   {
      $baseUrl = $sandbox
         ? ($urlSandbox ?? 'https://api.mercadopago.com/')
         : ($urlProducao ?? 'https://api.mercadopago.com/');

      $this->initializeClient($baseUrl, $accessToken);
   }

   private function initializeClient(string $baseUrl, string $accessToken): void
   {
      $this->client = Services::curlrequest([
         'baseURI' => $baseUrl,
         'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
         ]
      ]);
   }

   /**
    * @throws JsonException
    */
   public function gerarPagamento(array $pedido, array $cliente): array
   {
      $payload = $this->preparePayload($pedido, $cliente);

      try {
         $response = $this->client->post('v1/payments', ['json' => $payload]);
         return $this->processResponse($response, $payload['external_reference']);
      } catch (\Throwable $e) {
         throw new Exception("Erro ao gerar pagamento PIX: " . $e->getMessage(), 0, $e);
      }
   }

   private function preparePayload(array $pedido, array $cliente): array
   {
      return [
         "transaction_amount" => floatval($pedido['valor']),
         "description" => $pedido['descricao'] ?? 'Pagamento via Pix',
         "payment_method_id" => "pix",
         "notification_url" => $pedido['notification_url'] ?? 'https://hook.multidesk.io/webhook/c6f721c2-5a95-4c25-87f8-93f3f33986ed',
         "external_reference" => $pedido['referencia'] ?? uniqid('ref_', true),
         "payer" => [
            "email" => $cliente['email'],
            "first_name" => $cliente['nome'] ?? '',
            "last_name" => $cliente['sobrenome'] ?? '',
            "identification" => [
               "type" => "CPF",
               "number" => $cliente['cpf']
            ]
         ]
      ];
   }

   private function processResponse($response, string $externalReference): array
   {
      $json = json_decode($response->getBody(), true);

      if (!isset($json['id'])) {
         throw new Exception("Erro inesperado ao gerar Pix. Resposta: " . json_encode($json));
      }

      $transactionData = $json['point_of_interaction']['transaction_data'] ?? [];

      return [
         'payment_id' => $json['id'],
         'qr_code' => $transactionData['qr_code'] ?? null,
         'qr_code_base64' => $transactionData['qr_code_base64'] ?? null,
         'ticket_url' => $transactionData['ticket_url'] ?? null,
         'external_reference' => $externalReference,
         'status' => $json['status']
      ];
   }
}

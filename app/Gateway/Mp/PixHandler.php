<?php

namespace App\Gateway\Mp;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use Exception;
use JsonException;
use RuntimeException;
use Throwable;

class PixHandler
{
   protected CURLRequest $client;

   public function __construct(string $accessToken, bool $sandbox = true, ?string $urlSandbox = null, ?string $urlProducao = null)
   {
      $baseUrl = $sandbox
         ? ($urlSandbox ?? 'https://api.mercadopago.com/')
         : ($urlProducao ?? 'https://api.mercadopago.com/');

      $this->client = Services::curlrequest([
         'baseURI' => $baseUrl,
         'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type'  => 'application/json'
         ]
      ]);
   }

   /**
    * @throws JsonException
    */
   public function gerarPagamento(array $pedido, array $cliente): array
   {
      $payload = [
         "transaction_amount" => floatval($pedido['valor']),
         "description" => $pedido['descricao'] ?? 'Pagamento via Pix',
         "payment_method_id" => "pix",
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

      try {
         $response = $this->client->post('v1/payments', [
            'json' => $payload
         ]);
      } catch (Throwable $e) {
         throw new RuntimeException("Erro ao gerar pagamento PIX: " . $e->getMessage());
      }

      $json = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

      if (!isset($json['id'])) {
         throw new RuntimeException("Erro inesperado ao gerar Pix. Resposta: " . json_encode($json));
      }

      return [
         'payment_id' => $json['id'],
         'qr_code' => $json['point_of_interaction']['transaction_data']['qr_code'],
         'qr_code_base64' => $json['point_of_interaction']['transaction_data']['qr_code_base64'],
         'status' => $json['status']
      ];
   }
}

<?php

namespace App\Gateway\Mp;

use Exception;
use JsonException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\Resources\Payment;
use RuntimeException;

class PixHandler
{
   protected PaymentClient $client;

   public function __construct(string $accessToken)
   {
      $this->initializeClient($accessToken);
   }

   private function initializeClient(string $accessToken): void
   {
      MercadoPagoConfig::setAccessToken($accessToken);
      $this->client = new PaymentClient();
   }

   public function gerarPagamento(array $pedido, array $cliente): Payment
   {
      $idempotencyKey = uniqid('mp_', true);

      $options = $this->createRequestOptions($idempotencyKey);

      try {
         $paymentData = $this->preparePaymentData($pedido, $cliente);
         return $this->client->create($paymentData, $options);
      } catch (MPApiException $e) {
         $this->handleApiException($e);
      } catch (Exception $e) {
         $this->handleGeneralException($e);
      }
   }

   private function createRequestOptions(string $idempotencyKey): RequestOptions
   {
      $options = new RequestOptions();
      $options->setCustomHeaders(['X-Idempotency-Key: ' . $idempotencyKey]);
      return $options;
   }

   private function preparePaymentData(array $pedido, array $cliente): array
   {
      return [
         "transaction_amount" => (float)($pedido['valor'] ?? 0),
         "payment_method_id" => "pix",
         "description" => $pedido['descricao'] ?? 'Pagamento via Pix',
         "notification_url" => $pedido['notification_url'] ?? 'https://seusite.com/webhook/mercadopago',
         "external_reference" => $pedido['referencia'] ?? uniqid('ref_', true),
         "payer" => $this->preparePayerData($cliente)
      ];
   }

   private function preparePayerData(array $cliente): array
   {
      return [
         "email" => $cliente['email'],
         "first_name" => $cliente['nome'] ?? '',
         "last_name" => $cliente['sobrenome'] ?? '',
         "identification" => [
            "type" => "CPF",
            "number" => $cliente['cpf']
         ]
      ];
   }

   /**
    * @throws JsonException
    */
   private function handleApiException(MPApiException $exception): void
   {
      throw new RuntimeException("Erro ao criar pagamento Pix: " . json_encode($exception->getApiResponse()->getContent(), JSON_THROW_ON_ERROR));
   }

   private function handleGeneralException(Exception $exception): void
   {
      throw new RuntimeException("Erro ao criar pagamento Pix: " . $exception->getMessage());
   }
}

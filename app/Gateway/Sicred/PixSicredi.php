<?php


namespace App\Gateway\Sicred;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use JsonException;

class PixSicredi
{
   public const urlH = 'https://api-pix-h.sicredi.com.br';
   public const urlP = 'https://api-pix.sicredi.com.br';

   public string $url;
   public mixed $client_id;
   public mixed $client_secret;
   public string $authorization;
   public mixed $token;
   public mixed $crt_file;
   public mixed $key_file;
   public mixed $pass;
   public mixed $header;
   public mixed $parth;
   public mixed $fields;

   public function __construct($dados)
   {

      if ((int)$dados["producao"] === 1) {
         $this->url = self::urlP;
      } else {
         $this->url = self::urlH;
      }

      $this->client_id = $dados["client_id"];
      $this->client_secret = $dados["client_secret"];

      $this->crt_file = $dados["crt_file"];
      $this->key_file = $dados["key_file"];
      $this->pass = $dados["pass"];

      $this->authorization = base64_encode($this->client_id . ":" . $this->client_secret);
   }

   public function Request($method): ?string
   {
      $client = Services::curlrequest([
         'baseURI' => $this->url,
         'verify' => false, // Disable SSL verification for simplicity
         'cert' => $this->crt_file,
         'ssl_key' => [$this->key_file, $this->pass],
      ]);

      $options = [
         'headers' => $this->header,
         'body' => $this->fields,
         'http_errors' => false,
      ];

      return $client->request($method, $this->parth, $options)->getBody();
   }

   public function accessToken(): ?string
   {

      $this->parth = '/oauth/token?grant_type=client_credentials&scope=cob.write+cob.read+webhook.read+webhook.write';
      $this->header = [
         'Accept' => 'application/json',
         'Content-Type' => 'application/json',
         'Authorization' => 'Basic ' . $this->authorization . ' '
      ];
      return $this->Request("POST");
   }

   /**
    * @throws JsonException
    */
   public function updateWebhook($url, $chave): ?string
   {

      $this->parth = '/api/v2/webhook/' . $chave;
      $this->header = ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $this->token . ''];
      $this->fields = json_encode(["webhookUrl" => $url], JSON_THROW_ON_ERROR);
      return $this->Request("PUT");
   }

   public function getUrlWebhook($chave): ?string
   {
      $this->parth = '/api/v2/webhook/' . $chave;
      $this->header = ['Authorization' => 'Bearer ' . $this->token];
      return $this->Request("GET");
   }

   public function deleteUrlWebhook($chave): ?string
   {
      $this->parth = '/api/v2/webhook/' . $chave;
      $this->header = ['Authorization' => 'Bearer ' . $this->token];
      return $this->Request("DELETE");
   }

   /**
    * @throws JsonException
    */
   public function criarCobranca($data): ?string
   {
      $this->fields = json_encode($data, JSON_THROW_ON_ERROR);
      $this->parth = '/api/v2/cob';
      $this->header = ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $this->token . ''];

      return $this->Request("POST");
   }

   public function dadosDeCobranca($id): ?string
   {
      $this->parth = '/api/v2/cob/' . $id;
      $this->header = ['Authorization' => 'Bearer ' . $this->token . ''];
      return $this->Request("GET");
   }
}
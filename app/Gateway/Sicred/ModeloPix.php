<?php


namespace App\Gateway\Sicred;


class ModeloPix
{
   const urlH = 'https://api-pix-h.sicredi.com.br';
   const urlP = 'https://api-pix.sicredi.com.br';

   public  $url;
   public  $client_id;
   public  $client_secret;
   public  $authorization;
   public  $token;
   public  $crt_file;
   public  $key_file;
   public  $pass;
   public $header;
   public $parth;
   public $fields;

   public function __construct($dados)
   {

      if ((int) $dados["producao"] == 1) {
         $this->url = self::urlP;
      } else {
         $this->url = self::urlH;
      }

      $this->client_id 		= $dados["client_id"];
      $this->client_secret 	= $dados["client_secret"];

      $this->crt_file = $dados["crt_file"];
      $this->key_file = $dados["key_file"];
      $this->pass     = $dados["pass"];

      $this->authorization = base64_encode($this->client_id . ":" . $this->client_secret);
   }

   public function Request($method)
   {

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $this->url .  $this->parth);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $this->fields);
      curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0');
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSLCERT, $this->crt_file);
      curl_setopt($curl, CURLOPT_SSLKEY, $this->key_file);
      curl_setopt($curl, CURLOPT_SSLKEYPASSWD, $this->pass);
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
   }



   public function accessToken()
   {

      $this->parth  = '/oauth/token?grant_type=client_credentials&scope=cob.write+cob.read+webhook.read+webhook.write';
      $this->header = [
         'Accept: application/json',
         'Content-Type: application/json',
         'Authorization: Basic ' . $this->authorization . ' '
      ];
      $response     = $this->Request("POST");
      return $response;
   }



   public function updateWebhook($url, $chave)
   {

      $this->parth  =  '/api/v2/webhook/' . $chave;
      $this->header =  ['Content-Type: application/json', 'Authorization: Bearer ' . $this->token . ''];
      $this->fields =  json_encode(["webhookUrl" => $url]);
      $response     =  $this->Request("PUT");
      return $response;
   }



   public function getUrlWebhook($chave)
   {
      $this->parth  =  '/api/v2/webhook/' . $chave;
      $this->header =  ['Authorization: Bearer ' . $this->token . ''];
      $response     =  $this->Request("GET");
      return $response;
   }


   public function deleteUrlWebhook($chave)
   {
      $this->parth  =  '/api/v2/webhook/' . $chave;
      $this->header =  ['Authorization: Bearer ' . $this->token . ''];
      $response     =  $this->Request("DELETE");
      return $response;
   }



   public function criarCobranca($data)
   {
      $this->fields = json_encode($data);
      $this->parth  =  '/api/v2/cob';
      $this->header =  ['Content-Type: application/json', 'Authorization: Bearer ' . $this->token . ''];

      $response     =  $this->Request("POST");
      return $response;
   }


   public function dadosDeCobranca($id)
   {
      $this->parth  =  '/api/v2/cob/' . $id;
      $this->header =  ['Authorization: Bearer ' . $this->token . ''];
      $response     =  $this->Request("GET");
      return $response;
   }
}
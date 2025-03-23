<?php

namespace App\Libraries;

use JsonException;
use RuntimeException;

class GoogleMapsLibrarie
{
   /**
    * Méthodo principal para calcular a rota entre dois pontos.
    *
    * @param string $origem Endereço ou coordenadas de origem
    * @param string $destino Endereço ou coordenadas de destino
    * @param string $modo Modo de transporte (driving, walking, bicycling, transit)
    * @return array
    * @throws JsonException
    */
   public function calcularRota(string $origem, string $destino, string $modo = 'driving'): array
   {
      // Obtém a URL completa da requisição
      $url = $this->montarUrl($origem, $destino, $modo);

      // Faz a requisição para a API Google Maps Directions
      $data = $this->fazerRequisicao($url);

      // Processa a resposta e retorna os dados da rota
      return $this->processarResposta($data);
   }

   /**
    * Monta a URL de requisição para a API Directions.
    *
    * @param string $origem Endereço ou coordenadas de origem
    * @param string $destino Endereço ou coordenadas de destino
    * @param string $modo Modo de transporte
    * @param string $idioma Idioma da resposta da API (padrão: pt-BR)
    * @return string URL completa para a requisição
    */
   private function montarUrl(string $origem, string $destino, string $modo, string $idioma = 'pt-BR'): string
   {
      // Obtém a API Key do arquivo de configuração
      $apiKey = env('GOOGLE_MAPS_API_KEY');
      if (empty($apiKey)) {
         throw new RuntimeException('Chave da API do Google Maps não encontrada.');
      }

      // Monta e retorna a URL formatada com o idioma
      return sprintf(
         'https://maps.googleapis.com/maps/api/directions/json?origin=%s&destination=%s&mode=%s&language=%s&key=%s',
         urlencode($origem),
         urlencode($destino),
         $modo,
         $idioma,
         $apiKey
      );
   }

   /**
    * Gera um link para abrir a rota no Google Maps.
    *
    * @param string $origem Endereço ou coordenadas de origem
    * @param string $destino Endereço ou coordenadas de destino
    * @param string $modo Modo de transporte (driving, walking, bicycling, transit)
    * @return string URL para abrir a rota no Google Maps
    */
   public function gerarLinkMapa(string $origem, string $destino, string $modo = 'driving'): string
   {
      // Gera a URL para abrir no navegador
      return sprintf(
         'https://www.google.com/maps/dir/?api=1&origin=%s&destination=%s&travelmode=%s',
         urlencode($origem),
         urlencode($destino),
         $modo
      );
   }

   /**
    * Faz a requisição para a URL da API e retorna os dados em formato de array.
    *
    * @param string $url URL completa para a requisição
    * @return array Dados retornados pela API
    * @throws JsonException
    */
   private function fazerRequisicao(string $url): array
   {
      // Faz a requisição usando a biblioteca HTTP do CodeIgniter
      $client = \Config\Services::curlrequest();
      $response = $client->get($url);

      if ($response->getStatusCode() !== 200) {
         throw new RuntimeException('Erro ao se conectar com a API do Google Maps.');
      }

      // Decodifica e retorna os dados em JSON
      return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
   }

   /**
    * Processa a resposta da API e extrai as informações necessárias da rota.
    *
    * @param array $data Dados da resposta da API
    * @return array Informações processadas ou uma mensagem de erro
    */
   private function processarResposta(array $data): array
   {
      // Verifica se a API retornou status OK
      if ($data['status'] !== 'OK') {
         return [
            'status' => 'error',
            'message' => $data['error_message'] ?? 'Erro ao calcular a rota.',
         ];
      }

      // Extrai detalhes da rota
      $rota = $data['routes'][0] ?? null;
      if (!$rota) {
         return [
            'status' => 'error',
            'message' => 'Nenhuma rota encontrada entre a origem e o destino.',
         ];
      }

      // Retorna detalhes processados da rota
      return [
         'status' => 'success',
         'rota' => [
            'distancia' => $rota['legs'][0]['distance']['text'] ?? null,
            'duracao'   => $rota['legs'][0]['duration']['text'] ?? null,
            'instrucoes' => array_map(static function ($step) {
               return strip_tags($step['html_instructions'] ?? '');
            }, $rota['legs'][0]['steps'] ?? []),
         ],
      ];
   }
}
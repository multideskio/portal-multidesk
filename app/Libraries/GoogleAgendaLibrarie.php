<?php

namespace App\Libraries;

use App\Models\UsuarioModel;
use Google_Service_Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use RuntimeException;

class GoogleAgendaLibrarie
{
   private $client;

   /**
    * Configura o cliente do Google com as definições iniciais
    */
   public function __construct()
   {
      $this->client = new Google_Client();
      $this->client->setClientId(env('GOOGLE_ID'));               // ID do Cliente
      $this->client->setClientSecret(env('GOOGLE_SECRET'));       // Chave do Cliente
      $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));  // URL de Callback
      $this->client->setAccessType('offline');                   // Garante o uso do refresh token
      $this->client->setPrompt('consent');                       // Força pedido de consentimento
      $this->client->addScope(Google_Service_Calendar::CALENDAR); // Escopo necessário para o Google Calendar
   }

   /**
    * Recupera e configura o cliente Google para o usuário especificado
    *
    * @param int $userId ID do usuário no sistema
    * @return Google_Client
    * @throws \JsonException
    */
   public function getGoogleClient(int $userId): Google_Client
   {
      // Obtém o usuário e verifica se existe um refresh_token válido
      $user = $this->getUserById($userId);

      if (!$user || empty($user['refresh_token'])) {
         throw new RuntimeException('Usuário não possui um refresh token válido.');
      }

      $token = $this->verifyNewToken($userId);

      // Configura o cliente com o refresh_token
      $this->client->setAccessToken($token);

      // Renova o access_token caso esteja expirado
      if ($this->client->isAccessTokenExpired()) {
         try {
            // Usa o refresh_token para obter um novo token
            $token = $this->client->fetchAccessTokenWithRefreshToken($user['refresh_token']);
            if (!empty($token['access_token'])) {
               // Atualiza o refresh_token no banco, se necessário
               $this->updateUserRefreshToken($userId, $token['refresh_token'] ?? $user['refresh_token']);
            } else {
               throw new RuntimeException('Falha ao renovar o access token.');
            }
         } catch (Google_Service_Exception $e) {
            log_message('error', 'Erro ao renovar o token: ' . $e->getMessage());
            throw new RuntimeException('Token inválido ou revogado. Por favor, solicite que o usuário autentique novamente.');
         }
      }

      return $this->client;
   }

   /**
    * Cria um evento no Google Calendar
    *
    * @param int $userId        ID do usuário no sistema
    * @param string $titulo     Título do evento
    * @param string $descricao  Descrição do evento
    * @param string $local      Local do evento
    * @param string $dataInicio Data e hora inicial no formato ISO 8601
    * @param string $dataFim    Data e hora final no formato ISO 8601
    * @return array
    */
   public function criarEventoGoogle(int $userId, string $titulo, string $descricao, string $local, string $dataInicio, string $dataFim): array
   {
      // Configurar o cliente do Google para o usuário
      $client = $this->getGoogleClient($userId);

      // Inicializa o serviço do Google Calendar
      $service = new Google_Service_Calendar($client);

      // Cria o evento
      $event = new Google_Service_Calendar_Event([
         'summary'     => $titulo,
         'description' => $descricao,
         'location'    => $local,
         'start'       => [
            'dateTime' => $dataInicio,
            'timeZone' => 'America/Sao_Paulo',
         ],
         'end' => [
            'dateTime' => $dataFim,
            'timeZone' => 'America/Sao_Paulo',
         ],
      ]);

      try {
         // Adiciona o evento à agenda principal
         $calendarId = 'primary'; // Agenda padrão do usuário
         $createdEvent = $service->events->insert($calendarId, $event);

         return [
            'status' => 'success',
            'data'   => $createdEvent,
            'link'   => $createdEvent->htmlLink,
         ];
      } catch (Google_Service_Exception $e) {
         log_message('error', 'Erro ao criar evento no Google Calendar: ' . $e->getMessage());
         return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
         ];
      }
   }

   /**
    * Lista eventos do Google Calendar
    *
    * @param int $userId      ID do usuário no sistema
    * @param string $calendarId ID da Agenda a ser consultada (default: primary)
    * @return array
    */
   public function listarEventos(int $userId, string $calendarId = 'primary'): array
   {
      // Recuperar o cliente Google
      $client = $this->getGoogleClient($userId);

      // Inicializa o serviço do Google Calendar
      $service = new Google_Service_Calendar($client);

      try {
         // Obtém a lista de eventos
         $events = $service->events->listEvents($calendarId);

         return [
            'status' => 'success',
            'data'   => $events->getItems(),
         ];
      } catch (Google_Service_Exception $e) {
         log_message('error', 'Erro ao listar eventos no Google Calendar: ' . $e->getMessage());
         return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
         ];
      }
   }

   /**
    * Obtém um usuário pelo ID
    *
    * @param int $userId ID do usuário no sistema
    * @return array Dados do usuário
    */
   protected function getUserById(int $userId): array
   {
      return (new UsuarioModel())->find($userId);
   }

   /**
    * Atualiza o refresh token de um usuário no banco de dados
    *
    * @param int $userId ID do usuário no sistema
    * @param string $refreshToken Refresh Token obtido do Google
    * @return void
    */
   protected function updateUserRefreshToken(int $userId, string $refreshToken): void
   {
      try {
         (new UsuarioModel())->update($userId, ['refresh_token' => $refreshToken]);
      } catch (\ReflectionException $e) {
         log_message('error', 'Erro ao atualizar refresh token: ' . $e->getMessage());
         throw new RuntimeException('Falha ao atualizar o refresh token do usuário.');
      }
   }

   public function verifyNewToken($id): array
   {
      $user = $this->getUserById($id);
      return $this->client->fetchAccessTokenWithRefreshToken($user['refresh_token']);
   }
}
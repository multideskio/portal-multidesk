<?php

namespace App\Controllers;

use App\Gateway\Mp\MPProcessor;
use App\Libraries\GoogleAgendaLibrarie;
use App\Libraries\GoogleMapsLibrarie;
use App\Models\EmpresaGatewaysModel;
use JsonException;

class Home extends BaseController
{
   public function teste(): ?\CodeIgniter\HTTP\ResponseInterface
   {
      $empresaId = 1;
      $gateway   = 'mp';
      $tipo      = 'pix';

      $pedido = [
         'valor'     => 5,
         'descricao' => 'Ingresso para o Evento X'
      ];

      $cliente = [
         'cpf'       => '12345678909',
         'nome'      => 'João',
         'sobrenome' => 'Silva',
         'email'     => 'joao@email.com'
      ];

      try {
         $mp = new MPProcessor();
         $resposta = $mp->processar($tipo, $empresaId, $pedido, $cliente, $gateway);

         return $this->response->setJSON($resposta);
      } catch (\Throwable $e) {
         return $this->response->setJSON([
            'erro' => $e->getMessage()
         ])->setStatusCode(500);
      }
   }


//      $googleMapsLibrary = new GoogleMapsLibrarie();
//
//      // Configuração das origens e destinos
//      $origem = 'Sen. Canedo - GO, 75264-107';
//      $destino = 'Av. T-7, 1361 - St. Bueno, Goiânia - GO, 74210-265';
//
//      // Calcula a rota
//      $response = $googleMapsLibrary->calcularRota($origem, $destino, 'driving');
//
//      // Verifica o resultado
//      if ($response['status'] === 'success') {
//         echo "Distância: " . $response['rota']['distancia'] . "<br>";
//         echo "Duração: " . $response['rota']['duracao'] . "<br>";
//         echo "Instruções da rota: " . "<br>";
//         foreach ($response['rota']['instrucoes'] as $passo) {
//            echo "- " . $passo . "<br>";
//         }
//      } else {
//         echo "Erro: " . $response['message'];
//      }
//
//      $modo = 'driving'; // Pode ser: driving, walking, bicycling, transit
//
//      // Gera o link do mapa
//      $linkMapa = $googleMapsLibrary->gerarLinkMapa($origem, $destino, $modo);
//
//      echo "Abra o mapa aqui: " . $linkMapa;
//       $googleLibrary = new GoogleAgendaLibrarie();
//       $response = $googleLibrary->listarEventos(3);
//       if ($response['status'] === 'success') {
//          foreach ($response['data'] as $event) {
//             if ($event->getStart() && method_exists($event->getStart(), 'getDateTime')) {
//                $start = $event->getStart()->getDateTime();
//             } elseif ($event->getStart()) {
//                $start = $event->getStart()->getDate();
//             } else {
//                $start = 'Data indisponível';
//             }
//             $link = $event->getHtmlLink();
//             echo "<a href='{$link}' target='_blank'>{$event->getSummary()}</a> - " . $start . "<br>";
//          }
//       } else {
//          echo "Erro ao listar eventos: " . $response['message'];
//       }
//       $response = $googleLibrary->criarEventoGoogle(
//          2,
//          'Teste de Integração',
//          'Evento criado para testar a integração.',
//          'Local de teste',
//          '2023-11-20T10:00:00-03:00',
//          '2023-11-20T11:00:00-03:00'
//       );
//
//       if ($response['status'] === 'success') {
//          echo "Evento criado com sucesso! Acesse: <a href='" . $response['link'] . "' target='_blank'>" . $response['link'] . "</a>";
//       } else {
//          echo "Erro ao criar evento: " . $response['message'];
//       }

//   }
}

<?php

namespace App\Controllers;

use App\Gateway\Mp\MPProcessor;
use App\Libraries\GoogleAgendaLibrarie;
use App\Libraries\GoogleMapsLibrarie;
use App\Models\EmpresaGatewaysModel;
use App\Services\S3Services;
use JsonException;

class Home extends BaseController
{
   public function teste(){
      
      if($this->request->getMethod() === 'POST'){
         $s3 = new S3Services();

         if (($file = $this->request->getFile('image')) && $file->isValid() && !$file->hasMoved()) {
            $newName = 'qrcode/cliente/1/'.$file->getRandomName();
            $send = $s3->saveFile('empresa-2', $file->getTempName(), $newName);

//         echo "<pre>";
//            print_r($send);
//            echo "</pre>";

            echo $send['@metadata']['effectiveUri'];
         }
         
      }

      echo '<form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" accept="image/*">
         <button type="submit">Enviar</button>
      </form>';
   }
   public function teste00(): ?\CodeIgniter\HTTP\ResponseInterface
   {
      $empresaId = 1;
      $gateway   = 'mercadopago';
      $tipo      = 'pix';

      $pedido = [
         'valor'     => 0.05,
         'descricao' => 'Ingresso para o Evento X'
      ];

      $cliente = [
         'cpf' => '19119119100',
         'nome' => 'Test',
         'sobrenome' => 'User',
         'email' => 'payer@email.com',
         'cep' => '06233200',
         'logradouro' => 'Av. das Nações Unidas',
         'numero' => '3003',
         'bairro' => 'Bonfim',
         'cidade' => 'Osasco',
         'uf' => 'SP'
      ];


      try {
         $mp = new MPProcessor();
         $resposta = $mp->processar($tipo, $empresaId, $pedido, $cliente, $gateway);

         log_message('debug', 'Resposta do gateway: ' . json_encode($resposta, JSON_THROW_ON_ERROR));

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

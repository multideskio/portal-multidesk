<?php

namespace App\Libraries;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeLibrarie
{
   public function gerarQrCode(string $uuid, string $conteudo, string $participanteId): string
   {
      $qrDir = FCPATH . 'assets/qrcodes/'.$participanteId.'/';

      if (!is_dir($qrDir) && !mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
         throw new \RuntimeException(sprintf('Directory "%s" was not created', $qrDir));
      }

      $filePath = $qrDir . $uuid . '.png';

      $qrCode = new QrCode(
         data: $conteudo,
         encoding: new Encoding('UTF-8'),
         errorCorrectionLevel: ErrorCorrectionLevel::Low,
         size: 300,
         margin: 10,
         roundBlockSizeMode: RoundBlockSizeMode::Margin,
         foregroundColor: new Color(0, 0, 0),
         backgroundColor: new Color(255, 255, 255)
      );

      $writer = new PngWriter();
      $result = $writer->write($qrCode);
      $result->saveToFile($filePath);

      return 'assets/qrcodes/'.$participanteId.'/' . $uuid . '.png';
   }

   public function apagarPastaComConteudo(string $path): void
   {
      if (!is_dir($path)) {
         log_message('error', "O caminho '$path' não é um diretório ou não existe.");
         return;
      }

      $files = array_diff(scandir($path), ['.', '..']);

      foreach ($files as $file) {
         $filePath = $path . DIRECTORY_SEPARATOR . $file;
         if (is_dir($filePath)) {
            log_message('debug', "Entrando no diretório: '$filePath'");
            $this->apagarPastaComConteudo($filePath); // chamada recursiva
         } else {
            if (!unlink($filePath)) {
               log_message('error', "Falha ao deletar o arquivo: '$filePath'");
            } else {
               log_message('debug', "Arquivo deletado: '$filePath'");
            }
         }
      }

      if (!rmdir($path)) {
         log_message('error', "Falha ao deletar o diretório: '$path'");
      } else {
         log_message('debug', "Diretório deletado: '$path'");
      }
   }

}
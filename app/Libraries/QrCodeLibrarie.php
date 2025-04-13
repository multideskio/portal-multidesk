<?php

namespace App\Libraries;

use App\Services\S3Services;
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
      // Define o diretório onde os QR Codes serão armazenados
      $qrDir = FCPATH . 'assets/qrcodes/' . $participanteId . '/';

      // Verifica se o diretório existe, caso contrário tenta criá-lo
      if (!is_dir($qrDir) && !mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
         throw new \RuntimeException(sprintf('Directory "%s" was not created', $qrDir)); // Exceção caso o diretório não possa ser criado
      }

      // Define o caminho completo do arquivo de saída para o QR Code
      $filePath = $qrDir . $uuid . '.png';

      // Cria uma instância de QR Code com as configurações especificadas
      $qrCode = new QrCode(
         data: $conteudo, // Conteúdo do QR Code
         encoding: new Encoding('UTF-8'), // Codificação do texto
         errorCorrectionLevel: ErrorCorrectionLevel::Low, // Nível de correção de erro
         size: 300, // Tamanho do QR Code
         margin: 10, // Margem ao redor do QR Code
         roundBlockSizeMode: RoundBlockSizeMode::Margin, // Ajusta tamanho dos blocos ao estilo de margem
         foregroundColor: new Color(0, 0, 0), // Cor do código (preto)
         backgroundColor: new Color(255, 255, 255) // Cor de fundo (branco)
      );

      // Escreve o QR Code em um arquivo PNG
      $writer = new PngWriter();
      $result = $writer->write($qrCode);
      $result->saveToFile($filePath); // Salva o arquivo no caminho especificado

      // Retorna o caminho relativo do arquivo gerado
      return 'assets/qrcodes/' . $participanteId . '/' . $uuid . '.png';
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

   public function gerarQrCodeEEnviarParaS3(string $uuid, string $conteudo, string $participanteId, int $empresaId): ?string
   {
      $bucket = 'empresa-' . $empresaId;
      $key = 'qrcodes/' . $participanteId . '/' . $uuid . '.png';

      // Geração do QR Code local temporário
      $qrDir = WRITEPATH . 'tmp_qrcodes/';
      if (!is_dir($qrDir) && !mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
         throw new \RuntimeException(sprintf('Directory "%s" was not created', $qrDir));
      }

      $filePath = $qrDir . $uuid . '.png';

      try {
         // Criação do QR Code
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

         // Envia para S3
         $s3 = new S3Services();
         $upload = $s3->saveFile($bucket, $filePath, $key);

         log_message('info', $upload);

         $nameArquivo = $bucket . '/' . $key;
         if ($upload) {
            return $nameArquivo; // Caminho/key no S3 (você pode montar a URL pública com base nisso)
         }

         log_message('error', "Erro ao enviar QR code $uuid para S3.");
         return null;
      } catch (\Throwable $e) {
         log_message('error', "Erro ao gerar/enviar QR: " . $e->getMessage());
         return null;
      } finally {
         if (file_exists($filePath)) {
            unlink($filePath);
         }
      }
   }

}
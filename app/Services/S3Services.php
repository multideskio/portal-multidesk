<?php

namespace App\Libraries;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\S3\S3Client;
use CodeIgniter\Files\File;
use Exception;

//TODO: Add Log when have the time

/**
 * Class for managing interactions with S3 buckets.
 *
 * Provides methods to ensure bucket existence, upload objects, download objects,
 * save files to S3, and retrieve files from S3. Handles AWS S3-specific operations and errors.
 */
class S3Services
{
   protected S3Client $s3;

   /**
    * Initializes a new instance of the class and configures the S3 client.
    *
    * Sets up an S3 client with the necessary credentials, region, endpoint, and settings.
    * Retrieves configuration values from environment variables.
    *
    * @return void This constructor does not return a value.
    */
   public function __construct()
   {
      // Configura o cliente S3 com as credenciais e configurações fornecidas
      $this->s3 = new S3Client([
         // Especifica a versão do cliente S3 a ser usada
         'version' => getenv('S3_VERSION'),
         // Define a região onde os recursos S3 estão localizados
         'region' => getenv('S3_REGION'),
         // Configura o ponto de acesso (endpoint) do S3
         'endpoint' => getenv('S3_ENDPOINT'),
         // Define se o endpoint deve ser utilizado no estilo de path
         'use_path_style_endpoint' => filter_var(getenv('S3_USE_PATH_STYLE_ENDPOINT'), FILTER_VALIDATE_BOOLEAN),
         // Configura as credenciais de acesso ao S3
         'credentials' => [
            // Chave de acesso do S3
            'key' => getenv('S3_KEY'),
            // Segredo de acesso do S3
            'secret' => getenv('S3_SECRET'),
         ],
      ]);
   }

   /**
    * Ensures that the specified S3 bucket exists, creating it if necessary.
    *
    * @param string $bucket The name of the S3 bucket to check or create.
    *
    * @throws AwsException If an AWS-specific error occurs that is not related to the bucket not existing.
    */
   protected function ensureBucketExists(string $bucket): void
   {
      try {
         // Verifica se o bucket existe tentando recuperar seus metadados.
         $this->s3->headBucket(['Bucket' => $bucket]);
      } catch (AwsException $e) {
         // Se o bucket não for encontrado ou não existir, trata o erro criando o bucket.
         if ($e->getAwsErrorCode() === 'NotFound' || $e->getAwsErrorCode() === 'NoSuchBucket') {
            // Cria o bucket.
            $this->s3->createBucket(['Bucket' => $bucket]);
            // Aguarda até que o bucket seja confirmado como existente.
            $this->s3->waitUntil('BucketExists', ['Bucket' => $bucket]);
         } else {
            // Lança a exceção para qualquer outro tipo de erro.
            log_message('error', 'Error creating bucket: ' . $e->getAwsErrorCode());
            throw $e;
         }
      }
   }

   protected function uploadObject(string $bucket, string $key, mixed $body, string $contentType = null): ?Result
   {
      try {
         // Garante que o bucket existe, criando-o se necessário
         $this->ensureBucketExists($bucket);

         // Configura os parâmetros para o upload do objeto
         $params = [
            'Bucket' => $bucket, // Define o nome do bucket
            'Key' => $key,    // Define a chave do objeto (nome/armazenação)
            'Body' => $body,   // Define o conteúdo do objeto
         ];

         // Caso o tipo de conteúdo seja fornecido, adiciona aos parâmetros
         if ($contentType) {
            $params['ContentType'] = $contentType; // Especifica o tipo MIME do objeto
         }

         // Realiza o upload do objeto para o bucket no serviço S3
         return $this->s3->putObject($params);
      } catch (AwsException $e) {
         // Retorna null se ocorrer uma exceção do AWS S3
         log_message('error', 'Error uploading object to S3: ' . $e->getAwsErrorCode());
         return null;
      }
   }

   /**
    * Downloads an object from the specified S3 bucket and saves it locally.
    *
    * @param string $bucket The name of the S3 bucket from which the object will be downloaded.
    * @param string $key The key (name or location) of the object within the bucket.
    * @param string $saveAs The local file path where the downloaded object will be saved.
    *
    * @return string|null Returns the content of the object body on success, or null if an error occurs.
    */
   public function downloadObject(string $bucket, string $key, string $saveAs): ?string
   {
      try {
         // Tenta obter o objeto do bucket S3 especificado usando o nome do bucket, a chave e o caminho de salvamento local.
         $result = $this->s3->getObject([
            'Bucket' => $bucket, // Define o nome do bucket onde o objeto está localizado.
            'Key' => $key,       // Define a chave (nome ou caminho) do objeto dentro do bucket.
            'SaveAs' => $saveAs  // Define o caminho local onde o objeto será salvo.
         ]);

         // Retorna o conteúdo do corpo do objeto recuperado.
         return $result['Body'];
      } catch (AwsException $e) {
         // Registra uma mensagem de erro no log caso ocorra um problema ao tentar baixar o objeto do S3.
         log_message('error', 'Error downloading object from S3: ' . $e->getAwsErrorCode());

         // Retorna null em caso de falha.
         return null;
      }
   }

   /**
    * Saves a file to the specified S3 bucket.
    *
    * @param string $bucket The name of the S3 bucket where the file will be saved.
    * @param string $filePath The path of the file to be uploaded.
    * @param string $key The key (name or location) for the file within the bucket.
    *
    * @return Result|null Returns an instance of \Aws\Result on successful upload, or null if an error occurs.
    */
   public function saveFile(string $bucket, string $filePath, string $key): ?Result
   {
      try {
         // Cria uma instância do arquivo usando a classe File do CodeIgniter
         $file = new File($filePath);

         // Obtém o conteúdo do arquivo
         $fileContent = $file->openFile()->fread($file->getSize());

         // Detecta o tipo MIME automaticamente usando o método getMimeType
         $mimeType = $file->getMimeType();

         // Faz o upload do arquivo para o S3
         return $this->uploadObject($bucket, $key, $fileContent, $mimeType);
      } catch (Exception $e) {
         log_message('error', 'Error uploading file to S3: ' . $e->getMessage());
         return null;
      }
   }

   /**
    * Retrieves a file from the specified S3 bucket.
    *
    * @param string $bucket The name of the S3 bucket where the file is stored.
    * @param string $key The key (path or identifier) of the file within the bucket.
    *
    * @return mixed|null Returns the file content on success, or null if an error occurs.
    */
   public function getFile(string $bucket, string $key): mixed
   {
      try {
         // Tenta recuperar um objeto do bucket S3 especificado usando a chave fornecida
         $result = $this->s3->getObject([
            'Bucket' => $bucket, // Especifica o nome do bucket
            'Key' => $key       // Especifica a chave do objeto (caminho/identificador dentro do bucket)
         ]);

         // Retorna o conteúdo do objeto recuperado do S3
         return $result['Body'];
      } catch (AwsException $e) {
         // Registra uma mensagem de erro no log caso ocorra uma exceção AWS ao tentar obter o objeto
         log_message('error', 'Erro ao baixar arquivo do S3: ' . $e->getAwsErrorCode());

         // Retorna null caso ocorra algum erro
         return null;
      }
   }
}

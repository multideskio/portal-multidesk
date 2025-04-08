<?php

namespace App\Controllers\Admin\V1;

use App\Models\EventosModel;
use App\Models\VariacoesModel;
use App\Services\S3Services;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Eventos extends ResourceController
{
   use ResponseTrait;

   protected UuidInterface $uuid;
   protected EventosModel $eventosModel;
   protected VariacoesModel $variacoesModel;
   protected ?object $session;

   public function __construct()
   {
      $this->eventosModel = new EventosModel();
      $this->variacoesModel = new VariacoesModel();
      $this->uuid = Uuid::uuid4();
      $this->session = session();
   }

   public function index()
   {
//      $this->eventosModel->select('eventos.*, variacoes_eventos.titulo as nome_variacao');
//      $this->eventosModel->join('variacoes_eventos', 'variacoes_eventos.evento_id = eventos.id', 'left');
//      $this->eventosModel->orderBy('eventos.id', 'DESC');
//      $eventos = $this->eventosModel->findAll();
//      if (!$eventos) {
//         return $this->failNotFound();
//      }
      $eventos = $this->eventosModel->findAll();
      return $this->respond($eventos);
   }

   public function show($id = null)
   {
      //
      $this->eventosModel->select('eventos.*, variacoes.titulo as nome_variacao');
      $this->eventosModel->join('variacoes_eventos as variacoes', 'variacoes.evento_id = eventos.id', 'left');
      $this->eventosModel->orderBy('eventos.id', 'DESC');
      $this->eventosModel->where('eventos.id', $id);
      $eventos = $this->eventosModel->find();

      if (!$eventos) {
         return $this->failNotFound();
      }
      return $this->respond($eventos);
   }

   /**
    * Cria um novo evento e suas variações no banco de dados.
    *
    * O méthodo utiliza transações para garantir a consistência dos dados durante a inserção.
    * Inicialmente, captura os dados do evento principal e cria um registro na tabela associada.
    * Em seguida, processa as variações do evento, armazenando-as em lote no banco de dados.
    * Caso ocorra algum erro, a transação é revertida e uma resposta de erro é retornada.
    *
    * @return ResponseInterface O resultado da operação, contendo os dados do evento e suas variações ou uma mensagem de erro.
    */
   public function create(): ResponseInterface
   {
      $db = \Config\Database::connect();
      try {
         $db->transStart();
         $input = $this->request->getPost();
         // Dados do evento principal
         $dataEvento = [
            'empresa_id' => $this->session->get('data')['empresa'], // ID da empresa associada
            'slug' => $this->uuid->toString(), // Geração de UUID para o evento
            'titulo' => $input['titulo'], // Título do evento
            'descricao' => $input['description'], // Descrição do evento
            'endereco' => $input['endereco'], // Endereço do evento
            'status' => $input['status_curso'], // Status do evento
            'categoria' => $input['categoria_id'], // Categoria do evento
            'data_inicio' => $input['start_vendas'], // Data de início das vendas
            'data_fim' => $input['end_vendas'], // Data de término das vendas
         ];
         $id = $this->eventosModel->insert($dataEvento); // Inserção do evento no banco de dados
         $dataVariacao = [];
         foreach ($input['titulo_variacao'] as $item => $variacao) {
            // Dados das variações do evento
            $dataVariacao[] = [
               'evento_id' => $id, // ID do evento associado
               'ativo' => $input['ativo'][$item] ?? false,
               'titulo' => $variacao, // Título da variação
               'descricao' => $input['desc_variacao'][$item], // Descrição da variação
               'minimo' => $input['num_min'][$item], // Quantidade mínima
               'maximo' => $input['num_max'][$item], // Quantidade máxima
               'data_inicio' => $input['date_var_start'][$item], // Data de início da variação
               'data_fim' => $input['date_var_end'][$item], // Data de término da variação
               'valor' => $input['valor'][$item], // Valor da variação
               'quantidade' => $input['quantidade'][$item], // Quantidade disponível
            ];
         }
         $this->variacoesModel->insertBatch($dataVariacao); // Inserção das variações em batch
         $db->transComplete();

         $base64 = $this->request->getPost('cover_image_base64');
         if (!empty($base64)) {
            $empresaId = $this->session->get('data')['empresa']; // Obtém o ID da empresa da sessão
            $eventoId = $id; // ID do evento recém-criado
            $bucket = 'empresa-' . $empresaId; // Define o bucket do S3 com base no ID da empresa
            $key = 'evento/' . $eventoId . '/' . uniqid('capa_', true) . '.jpg'; // Define a chave do objeto no bucket
            // Salvar a imagem base64 temporariamente no servidor para envio ao S3
            $data = explode(',', $base64); // Divide o base64 para obter apenas os dados da imagem
            $imageData = base64_decode($data[1]); // Decodifica os dados base64 para o formato binário da imagem
            $tmpPath = WRITEPATH . 'uploads/' . uniqid('tmp_capa_', true) . '.jpg'; // Caminho temporário para armazenar a imagem
            file_put_contents($tmpPath, $imageData); // Salva os dados binários no arquivo temporário
            $S3 = new S3Services(); // Instancia o serviço S3
            $result = $S3->saveFile($bucket, $tmpPath, $key); // Envia o arquivo temporário ao S3
            $nameFile = '/' . $bucket . '/' . $key; // Caminho completo da imagem no bucket
            $this->eventosModel->update($eventoId, ['capa' => $nameFile]); // Atualiza o evento no banco com o caminho da capa
         }
         //return $this->respond([$dataEvento, $dataVariacao]); // Retorna os dados inseridos
         return redirect()->back()->with('success', 'Evento criado com sucesso!');
      } catch (Exception $e) {
         $db->transRollback(); // Rollback em caso de erro
         //return $this->fail($e->getMessage()); // Retorna o erro
         return redirect()->back()->with('error', 'Erro ao criar evento! <br>' . (string)$e->getMessage());
      }
   }

   /**
    * @param $id
    * @return ResponseInterface|string|void
    * todo MVP
    */
   public function edit($id = null)
   {
      //
      $data = $this->request->getRawInput();
      return $this->respond($data);
   }

   /**
    * @param $id
    * @return ResponseInterface|string|void
    * todo MVP
    */
   public function update($id = null)
   {
      $data = $this->request->getRawInput();
      return $this->respond($data);
   }

   /**
    * @param $id
    * @return \CodeIgniter\HTTP\RedirectResponse|ResponseInterface|string|void
    * todo MVP
    */
   public function delete($id = null)
   {
      $this->eventosModel->delete($id);
      return redirect()->back()->with('sucess', 'Evento deletado com sucesso');
   }
}

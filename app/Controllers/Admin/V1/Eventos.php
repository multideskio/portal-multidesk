<?php

namespace App\Controllers\Admin\V1;

use App\Models\EventosModel;
use App\Models\VariacoesModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
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
         //return $this->respond([$dataEvento, $dataVariacao]); // Retorna os dados inseridos
         return redirect()->back()->with('success', 'Evento criado com sucesso!');
      } catch (Exception $e) {
         $db->transRollback(); // Rollback em caso de erro
         //return $this->fail($e->getMessage()); // Retorna o erro
         return redirect()->back()->with('error', 'Erro ao criar evento! <br>' . (string)$e->getMessage());
      }
   }

   public function edit($id = null)
   {
      //
      $data = $this->request->getRawInput();
      return $this->respond($data);
   }

   public function update($id = null)
   {
      $data = $this->request->getRawInput();
      return $this->respond($data);
   }

   public function delete($id = null)
   {
      $this->eventosModel->delete($id);
      return redirect()->back()->with('sucess', 'Evento deletado com sucesso');
   }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EventosModel extends Model
{
   protected $table = 'eventos';
   protected $primaryKey = 'id';
   protected $useAutoIncrement = true;
   protected $returnType = 'array';
   protected $useSoftDeletes = true;
   protected $protectFields = true;
   protected $allowedFields = [
      'empresa_id',
      'slug',
      'titulo',
      'descricao',
      'endereco',
      'campos',
      'meta',
      'status',
      'data_inicio',
      'data_fim',
      'categoria',
   ];

   protected bool $allowEmptyInserts = false;
   protected bool $updateOnlyChanged = true;

   protected array $casts = [];
   protected array $castHandlers = [];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat = 'datetime';
   protected $createdField = 'created_at';
   protected $updatedField = 'updated_at';
   protected $deletedField = 'deleted_at';

   // Validation
   protected $validationRules = [];
   protected $validationMessages = [];
   protected $skipValidation = false;
   protected $cleanValidationRules = true;

   // Callbacks
   protected $allowCallbacks = true;
   protected $beforeInsert = [];
   protected $afterInsert = [];
   protected $beforeUpdate = [];
   protected $afterUpdate = [];
   protected $beforeFind = [];
   protected $afterFind = [];
   protected $beforeDelete = [];
   protected $afterDelete = [];

   protected function beforeInsert($data): array
   {
      $data['data'] = esc($data['data']);
      return $data;
   }


   public function getEventosSlug($slug = false): array
   {
      $resultados = $this
         ->select('eventos.*, variacoes_eventos.id as variacao_id')
         ->select('variacoes_eventos.titulo as variacao_nome, variacoes_eventos.valor as variacao_preco, variacoes_eventos.descricao as variacao_descricao, variacoes_eventos.quantidade as quantidade, variacoes_eventos.minimo as minimo, variacoes_eventos.maximo as maximo, variacoes_eventos.data_fim as encerra, variacoes_eventos.ativo as ativo')
         ->select('empresas.nome as empresa_nome, empresas.cnpj as empresa_cnpj')
         ->where('eventos.slug', $slug)
         ->join('variacoes_eventos', 'variacoes_eventos.evento_id = eventos.id', 'left')
         ->join('empresas', 'empresas.id = eventos.empresa_id', 'left')
         ->findAll();

      return $this->organizarEventos($resultados);
   }

   public function getEventosEmpresa($empresaId = false): array
   {
      $resultados = $this
         ->select('eventos.*, variacoes_eventos.id as variacao_id, variacoes_eventos.titulo as variacao_nome, variacoes_eventos.valor as variacao_preco')
         ->where('eventos.empresa_id', $empresaId)
         ->join('variacoes_eventos', 'variacoes_eventos.evento_id = eventos.id', 'left')
         ->findAll();

      return $this->organizarEventos($resultados);
   }

   private function organizarEventos(array $resultados): array
   {
      $eventosAgrupados = [];
      foreach ($resultados as $evento) {
         $eventoId = $evento['id'];

         if (!isset($eventosAgrupados[$eventoId])) {
            $eventosAgrupados[$eventoId] = [
               'id' => $evento['id'],
               'titulo' => $evento['titulo'],
               'descricao' => $evento['descricao'],
               'slug' => $evento['slug'],
               'empresa_id' => $evento['empresa_id'] ?? "",
               'empresa_nome' => $evento['empresa_nome'] ?? "",
               'empresa_cnpj' => $evento['empresa_cnpj'] ?? "",
               'variacoes' => [],
            ];
         }

         if (!empty($evento['variacao_id'])) {
            $eventosAgrupados[$eventoId]['variacoes'][] = [
               'id' => $evento['variacao_id'],
               'nome' => $evento['variacao_nome'],
               'preco' => $evento['variacao_preco'],
               'descricao' => $evento['variacao_descricao'] ?? "",
               'quantidade' => $evento['quantidade'] ?? "",
               'minimo' => $evento['minimo'] ?? "",
               'maximo' => $evento['maximo'] ?? "",
               'encerra' => $evento['encerra'] ?? "",
               'status' => $evento['ativo'] ?? "",
            ];
         }
      }

      return array_values($eventosAgrupados); // Return all grouped events
   }

   public function getEventosPaginate($page = null, $order = null): array
   {
      $session = session();
      $perPage = 10; // Number of items per page
      $currentPage = $page ?? 1; // Get the current page or default to 1
      $orderBy = $order === 'ASC' ? 'ASC' : 'DESC';

      $resultados = $this
         ->select('eventos.*, variacoes_eventos.id as variacao_id, variacoes_eventos.titulo as variacao_nome, variacoes_eventos.valor as variacao_preco')
         ->join('variacoes_eventos', 'variacoes_eventos.evento_id = eventos.id', 'left')
         ->orderBy('eventos.id', $orderBy)
         ->where('eventos.empresa_id', $session->get('data')['empresa'])
         ->paginate($perPage, 'default', $currentPage);

      return [
         'eventos' => $this->organizarEventos($resultados),
         'pager' => $this->pager // Get the pagination links
      ];
   }
}

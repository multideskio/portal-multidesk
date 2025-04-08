<?php

namespace App\Models;

use App\Libraries\QrCodeLibrarie;
use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;


class ParticipanteModel extends Model
{
    protected $table            = 'participantes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
   protected $allowedFields = [
      'pedido_id',
      'variacao_evento_id',
      'nome',
      'email',
      'telefone',
      'created_at',
      'updated_at',
      'deleted_at',
   ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


   public function cadastrarParticipantesEIngressos(array $listaParticipantes, int $pedidoId): bool
   {
      $dataIngresso = []; // ← Corrigido
      $modelIngresso = new IngressosParticipantesModel();
      $qrLibrary = new QrCodeLibrarie();

      // Apaga os ingressos antigos do pedido (1x só)
      $modelIngresso->where('pedido_id', $pedidoId)->delete();

      foreach ($listaParticipantes as $p) {
         // Verificar se o participante já existe pelo email
         $participante = $this->where('email', $p['email'])->first();

         if ($participante) {
            $participanteId = $participante['id'];
         } else {
            // Criar novo participante
            $dataParticipante = [
               'nome' => $p['nome'],
               'email' => $p['email'],
               'telefone' => $p['telefone'],
               'cpf' => $p['cpf'] ?? null,
               'created_at' => date('Y-m-d H:i:s'),
               'updated_at' => date('Y-m-d H:i:s')
            ];
            $participanteId = $this->insert($dataParticipante, true); // retorna o ID
         }

         // Gerar UUID e QR code
         $uuid = Uuid::uuid4()->toString();
         $url = base_url('admin/verifica/' . $uuid);

         // Limpa QR codes antigos do participante
//         $fileRemove = FCPATH . '/assets/qrcodes/' . $participanteId."_".$pedidoId . '/';
//         $qrLibrary->apagarPastaComConteudo($fileRemove);

         //$qrPath = $qrLibrary->gerarQrCode($uuid, $url, $participanteId."_".$pedidoId);

         $qrPath = $qrLibrary->gerarQrCodeEEnviarParaS3($uuid, $url, 'participante/'.$participanteId."/".$pedidoId, $pedidoId );

         // Monta dados do ingresso
         $dataIngresso[] = [
            'participante_id' => $participanteId,
            'pedido_id' => $pedidoId,
            'variacao_evento_id' => $p['idVariacao'],
            'uuid' => $uuid,
            'qr_code_path' => $qrPath,
            'pago' => 0,
            'liberado' => 0,
            'verificado' => 0,
            'extras' => json_encode($p['extras'] ?? [], JSON_THROW_ON_ERROR),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
         ];
      }

      // Insere todos os ingressos de uma vez
      $modelIngresso->insertBatch($dataIngresso);
      return true;
   }
}

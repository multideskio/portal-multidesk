<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIngressosParticipantes extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();

      $this->forge->addField([
         'id' => [
            'type' => 'SERIAL'
         ],
         'participante_id' => [
            'type' => 'INTEGER',
         ],
         'pedido_id' => [
            'type' => 'INTEGER',
         ],
         'variacao_evento_id' => [
            'type' => 'INTEGER',
         ],
         'uuid' => [
            'type' => 'CHAR',
            'constraint' => 36,
            'unique' => true,
         ],
         'qr_code_path' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
         ],
         'pago' => [
            'type' => 'SMALLINT',
            'default' => 0,
            'comment' => '0 = não pago, 1 = pago'
         ],
         'liberado' => [
            'type' => 'SMALLINT',
            'default' => 0,
            'comment' => '0 = não liberado, 1 = liberado para uso'
         ],
         'verificado' => [
            'type' => 'SMALLINT',
            'default' => 0,
            'comment' => '0 = não validado na entrada, 1 = validado na entrada'
         ],
         'extras' => [
            'type' => 'JSONB',
            'null' => true,
            'comment' => 'Campos personalizados definidos pelo produtor do evento'
         ],
         'created_at' => [
            'type' => 'TIMESTAMP',
            'null' => true,
         ],
         'updated_at' => [
            'type' => 'TIMESTAMP',
            'null' => true,
         ],
         'deleted_at' => [
            'type' => 'TIMESTAMP',
            'null' => true,
         ]
      ]);

      $this->forge->addKey('id', true);
      $this->forge->addForeignKey('participante_id', 'participantes', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('variacao_evento_id', 'variacoes_eventos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('ingressos_participantes', true);

      $db->enableForeignKeyChecks();

   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('ingressos_participantes', true);
      $db->enableForeignKeyChecks();
   }
}

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
             'type' => 'INT',
             'unsigned' => true,
             'auto_increment' => true,
          ],
          'participante_id' => [
             'type' => 'INT',
             'unsigned' => true,
          ],
          'pedido_id' => [
             'type' => 'INT',
             'unsigned' => true,
          ],
          'variacao_evento_id' => [
             'type' => 'INT',
             'unsigned' => true,
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
             'type' => 'TINYINT',
             'constraint' => 1,
             'default' => 0,
             'comment' => '0 = não pago, 1 = pago'
          ],
          'liberado' => [
             'type' => 'TINYINT',
             'constraint' => 1,
             'default' => 0,
             'comment' => '0 = não liberado, 1 = liberado para uso'
          ],
          'verificado' => [
             'type' => 'TINYINT',
             'constraint' => 1,
             'default' => 0,
             'comment' => '0 = não validado na entrada, 1 = validado na entrada'
          ],
          'extras' => [
             'type' => 'JSON',
             'null' => true,
             'comment' => 'Campos personalizados definidos pelo produtor do evento'
          ],
          'created_at' => [
             'type' => 'DATETIME',
             'null' => true,
          ],
          'updated_at' => [
             'type' => 'DATETIME',
             'null' => true,
          ],
          'deleted_at' => [
             'type' => 'DATETIME',
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

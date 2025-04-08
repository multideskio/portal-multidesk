<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransacao extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();

      $this->forge->addField([
         'id' => [
            'type' => 'BIGINT',
            'auto_increment' => true
         ],
         'pedido_id' => [
            'type' => 'int',
            'unsigned' => true,
         ],
         'empresa_id' => [
            'type' => 'int',
            'unsigned' => true,
         ],
         'gateway' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
         ],
         'referencia_gateway' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
         ],
         'status' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
         ],
         'valor' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
         ],
         'moeda' => [
            'type' => 'VARCHAR',
            'constraint' => 10,
         ],
         'tipo_pagamento' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
         ],
         'detalhes_pagamento' => [
            'type' => 'JSON',
            'null' => true,
         ],
         'payload' => [
            'type' => 'JSON',
            'null' => true,
         ],
         'tentativa_webhook' => [
            'type' => 'TINYINT',
            'default' => 0,
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
      $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('transacoes', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('transacoes', true);
      $db->enableForeignKeyChecks();
   }
}

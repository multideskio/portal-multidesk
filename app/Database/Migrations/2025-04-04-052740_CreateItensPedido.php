<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItensPedido extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->addField([
         'id' => [
            'type' => 'SERIAL',
         ],
         'pedido_id' => [
            'type' => 'INTEGER',
         ],
         'variacao_evento_id' => [
            'type' => 'INTEGER',
         ],
         'quantidade' => [
            'type' => 'INTEGER',
         ],
         'preco_unitario' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
         ],
         'subtotal' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
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
      $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('variacao_evento_id', 'variacoes_eventos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('itens_pedido', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('itens_pedido');
      $db->enableForeignKeyChecks();
   }
}

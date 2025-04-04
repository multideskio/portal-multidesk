<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidos extends Migration
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
         'cliente_id' => [
            'type' => 'INT',
            'unsigned' => true,
         ],
         'evento_id' => [
            'type' => 'INT',
            'unsigned' => true,
         ],
         'status' => [
            'type' => 'ENUM',
            'constraint' => ['aguardando', 'pago', 'cancelado'],
            'default' => 'aguardando',
         ],
         'total' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
         ],
         'metodo_pagamento' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => true
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
      $this->forge->addForeignKey('cliente_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('evento_id', 'eventos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('pedidos', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('pedidos');
      $db->enableForeignKeyChecks();
   }
}

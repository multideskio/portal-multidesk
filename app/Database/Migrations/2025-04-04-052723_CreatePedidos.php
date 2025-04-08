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
            'type' => 'SERIAL',
         ],
         'cliente_id' => [
            'type' => 'INTEGER',
            'unsigned' => true,
         ],
         'evento_id' => [
            'type' => 'INTEGER',
            'unsigned' => true,
         ],
         'status' => [
            'type' => 'VARCHAR',
            'constraint' => 20,
            'default' => 'aguardando',
         ],
         'total' => [
            'type' => 'NUMERIC',
            'constraint' => '10,2',
         ],
         'metodo_pagamento' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
            'null' => true
         ],
         'slug' => [
            'type' => 'UUID',
            'null' => false,
            'unique' => true,
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
      $this->forge->addForeignKey('cliente_id', 'clientes', 'id', 'CASCADE', 'CASCADE');
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

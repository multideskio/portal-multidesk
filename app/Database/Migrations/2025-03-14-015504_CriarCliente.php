<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarCliente extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();

      $this->forge->addField([
         'id' => [
            'type' => 'SERIAL',
         ],
//         'empresa_id' => [
//            'type' => 'INT',
//         ],
         'usuario_id' => [
            'type' => 'INTEGER',
         ],
         'nome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'sobrenome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'cpf' => [
            'type' => 'VARCHAR',
            'constraint' => 30,
            'null' => true,
         ],
         'email' => [
            'type' => 'VARCHAR',
            'constraint' => 80
         ],
         'telefone' => [
            'type' => 'VARCHAR',
            'constraint' => 30,
         ],
         'endereco' => [
            'type' => 'JSONB',
            'null' => true,
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
      //$this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
      $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('clientes', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('clientes', true);
      $db->enableForeignKeyChecks();
   }
}

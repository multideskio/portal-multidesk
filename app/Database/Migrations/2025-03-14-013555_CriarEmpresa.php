<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarEmpresa extends Migration
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
         'nome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'cnpj' => [
            'type' => 'VARCHAR',
            'constraint' => 30,
            'null' => true,
         ],
         'email' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
            'null' => true,
         ],
         'telefone' => [
            'type' => 'VARCHAR',
            'constraint' => 30,
            'null' => true,
         ],
         'endereco' => [
            'type' => 'JSON',
            'null' => true,
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
      $this->forge->createTable('empresas', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('empresas', true);
      $db->enableForeignKeyChecks();
   }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarEventos extends Migration
{
   public function up()
   {
      //
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->addField([
         'id' => [
            'type' => 'INT',
            'unsigned' => true,
            'auto_increment' => true,
         ],
         'empresa_id' => [
            'type' => 'INT',
            'unsigned' => true,
         ],
         'slug' => [
            'type' => 'CHAR',
            'constraint' => 36,
            'unique' => true
         ],
         'titulo' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'descricao' => [
            'type' => 'TEXT',
            'null' => true,
         ],
         'endereco' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
         ],
         'campos' => [
            'type' => 'JSON',
            'null' => true,
         ],
         'capa' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
         ],
         'meta' => [
            'type' => 'JSON',
            'null' => true,
         ],
         'status' => [
            'type' => 'ENUM',
            'constraint' => ['publicado', 'rascunho', 'cancelado'],
            'default' => 'rascunho'
         ],
         'categoria' => [
            'type' => 'ENUM',
            'constraint' => ['evento', 'palestra', 'curso', 'outro'],
         ],
         'data_inicio' => [
            'type' => 'DATETIME',
            'null' => true,
         ],
         'data_fim' => [
            'type' => 'DATETIME',
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
      $this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('eventos', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      //
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('eventos', true);
      $db->enableForeignKeyChecks();
   }
}

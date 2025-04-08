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
            'type' => 'SERIAL',
         ],
         'empresa_id' => [
            'type' => 'INTEGER',
         ],
         'slug' => [
            'type' => 'UUID',
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
            'type' => 'VARCHAR',
            'constraint' => 20,
            'default' => 'rascunho' // opções: 'publicado', 'rascunho', 'cancelado'
         ],
         'categoria' => [
            'type' => 'VARCHAR',
            'constraint' => 20, // opções: 'evento', 'palestra', 'curso', 'outro'
         ],
         'data_inicio' => [
            'type' => 'TIMESTAMP',
            'null' => true,
         ],
         'data_fim' => [
            'type' => 'TIMESTAMP',
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

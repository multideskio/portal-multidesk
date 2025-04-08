<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarVariacoesEventos extends Migration
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
         'evento_id' => [
            'type' => 'INTEGER',
         ],
         'titulo' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'descricao' => [
            'type' => 'TEXT',
            'null' => true,
         ],
         'valor' => [
            'type' => 'NUMERIC',
            'constraint' => '10,2',
         ],
         'quantidade' => [
            'type' => 'INTEGER',
            'COMMENT' => 'Quantidade de ingressos disponiveis'
         ],
         'minimo' => [
            'type' => 'INTEGER',
            'COMMENT' => 'Quantidade minima de ingressos para um cliente'
         ],
         'maximo' => [
            'type' => 'INTEGER',
            'COMMENT' => 'Quantidade maxima de ingressos para um cliente'
         ],
         'ativo' => [
            'type' => 'SMALLINT',
            'constraint' => 1,
            'default' => 1,
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
      $this->forge->addForeignKey('evento_id', 'eventos', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('variacoes_eventos', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      //
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('variacoes_eventos', true);
      $db->enableForeignKeyChecks();
   }
}

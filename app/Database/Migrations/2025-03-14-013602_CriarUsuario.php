<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarUsuario extends Migration
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
         'nome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
            'null' => true,
         ],
         'sobrenome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
            'null' => true,
         ],
         'telefone' => [
            'type' => 'VARCHAR',
            'constraint' => 30,
            'null' => true,
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
         'senha' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
         ],
         'token' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
         ],
         'code' => [
            'type' => 'VARCHAR',
            'constraint' => 6,
         ],
         'verificado' => [
            'type' => 'SMALLINT',
            'constraint' => 1,
            'default' => 0,
         ],
         'foto' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
         ],
         'roles' => [
            'type' => 'JSONB',
            'null' => true,
            'comment' => 'Modulos que deveram ser bloqueados no acesso do usuario, se não houve modulos bloqueados, todo o sistema é liberado.'
         ],
         'google_id' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
         ],
         'refresh_token' => [
            'type' => 'TEXT',
            'null' => true,
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
      $this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('usuarios', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('usuarios', true);
      $db->enableForeignKeyChecks();
   }
}

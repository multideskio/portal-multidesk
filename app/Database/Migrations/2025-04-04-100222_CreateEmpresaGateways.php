<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmpresaGateways extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->addField([
         'id' => [
            'type' => 'INT',
            'auto_increment' => true,
            'unsigned' => true
         ],
         'empresa_id' => [
            'type' => 'INT',
            'unsigned' => true
         ],
         'sandbox' => [
            'type' => 'BOOLEAN',
            'default' => false,
            'comment' => 'Usado para testes'
         ],
         'url_sandbox' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
         ],
         'url_producao' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
            'null' => true,
         ],
         'gateway' => [
            'type' => 'VARCHAR',
            'constraint' => 50
         ], // exemplo: mp, pagarme, stripe
         'access_token' => [
            'type' => 'TEXT',
            'null' => true
         ],
         'public_key' => [
            'type' => 'TEXT',
            'null' => true
         ],
         'ativo' => [
            'type' => 'BOOLEAN',
            'default' => false
         ],
         'created_at' => [
            'type' => 'DATETIME',
            'null' => true
         ],
         'updated_at' => [
            'type' => 'DATETIME',
            'null' => true
         ],
         'deleted_at' => [
            'type' => 'DATETIME',
            'null' => true
         ],
      ]);
      $this->forge->addKey('id', true);
      $this->forge->addForeignKey('empresa_id', 'empresas', 'id', 'CASCADE', 'CASCADE');
      $this->forge->createTable('empresa_gateways', true);
      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('empresa_gateways', true);
      $db->enableForeignKeyChecks();
   }
}

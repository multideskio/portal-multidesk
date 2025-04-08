<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParticipantes extends Migration
{
   public function up()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();

      $this->forge->addField([
         'id' => [
            'type' => 'SERIAL',
         ],
         'nome' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
         ],
         'email' => [
            'type' => 'VARCHAR',
            'constraint' => 80,
            'unique' => true
         ],
         'telefone' => [
            'type' => 'VARCHAR',
            'constraint' => 20,
            'null' => true,
         ],
         'cpf' => [
            'type' => 'VARCHAR',
            'constraint' => 14,
            'null' => true,
            'default' => null,
            'comment' => 'Opcional, não utilizado como identificador único'
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
      $this->forge->createTable('participantes', true);

      $db->enableForeignKeyChecks();
   }

   public function down()
   {
      $db = db_connect();
      $db->disableForeignKeyChecks();
      $this->forge->dropTable('participantes');
      $db->enableForeignKeyChecks();
   }
}

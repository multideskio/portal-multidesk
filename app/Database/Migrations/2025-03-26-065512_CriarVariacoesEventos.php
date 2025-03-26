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
               'type' => 'INT',
               'unsigned' => true,
               'auto_increment' => true,
           ],
           'evento_id' => [
               'type' => 'INT',
               'unsigned' => true,
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
             'type' => 'DECIMAL',
             'constraint' => '10,2',
          ],
          'quantidade' => [
             'type' => 'INT',
             'constraint' => 11,
             'COMMENT' => 'Quantidade de ingressos disponiveis'
          ],
          'minimo' => [
             'type' => 'INT',
             'constraint' => 11,
             'COMMENT' => 'Quantidade minima de ingressos para um cliente'
          ],
          'maximo' => [
             'type' => 'INT',
             'constraint' => 11,
             'COMMENT' => 'Quantidade maxima de ingressos para um cliente'
          ],
          'ativo' => [
             'type' => 'TINYINT',
             'constraint' => 1,
             'default' => 1,
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

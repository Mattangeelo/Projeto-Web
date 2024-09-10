<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        // Criação da tabela 'pedidos'
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'concluido', 'cancelado'],
                'default' => 'pendente',
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'default' => null,
            ],
            'deletado_em' => [  // Adicione esta linha para soft deletes
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pedidos');

        // Ajusta os valores padrão após a tabela ser criada
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE pedidos MODIFY criado_em DATETIME DEFAULT CURRENT_TIMESTAMP");
        $db->query("ALTER TABLE pedidos MODIFY atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('pedidos');
    }
}

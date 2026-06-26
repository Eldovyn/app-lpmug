<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriode extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'periode_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'periode_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
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
            ],
        ]);
        $this->forge->addKey('periode_id', true);
        $this->forge->createTable('tbl_periode');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_periode');
    }
}

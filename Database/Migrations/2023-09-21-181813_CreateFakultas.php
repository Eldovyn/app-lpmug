<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFakultas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'fakultas_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fakultas_name' => [
                'type'       => 'TEXT',
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
        $this->forge->addKey('fakultas_id', true);
        $this->forge->createTable('tbl_fakultas');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_fakultas');
    }
}

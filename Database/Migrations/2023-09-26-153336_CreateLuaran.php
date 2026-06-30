<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLuaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'luaran_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'luaran_name' => [
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
        $this->forge->addKey('luaran_id', true);
        $this->forge->createTable('tbl_luaran');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_luaran');
    }
}

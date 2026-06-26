<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUniversitas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'universitas_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'universitas_name' => [
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
        $this->forge->addKey('universitas_id', true);
        $this->forge->createTable('tbl_universitas');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_universitas');
    }
}

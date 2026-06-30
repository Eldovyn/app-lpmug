<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTopik extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'topik_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'topik_name' => [
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
        $this->forge->addKey('topik_id', true);
        $this->forge->createTable('tbl_topik');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_topik');
    }
}

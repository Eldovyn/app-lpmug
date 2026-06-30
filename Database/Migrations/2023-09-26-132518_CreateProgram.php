<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgram extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'program_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'program_name' => [
                'type'       => 'TEXT',
            ],
            'topik_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
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
        $this->forge->addKey('program_id', true);
        $this->forge->addForeignKey('topik_id', 'tbl_topik', 'topik_id');
        $this->forge->createTable('tbl_program');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_program');
    }
}

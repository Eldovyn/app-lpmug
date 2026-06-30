<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubprogram extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'subprogram_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'subprogram_name' => [
                'type'       => 'TEXT',
            ],
            'program_id' => [
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
        $this->forge->addKey('subprogram_id', true);
        $this->forge->addForeignKey('program_id', 'tbl_program', 'program_id');
        $this->forge->createTable('tbl_subprogram');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_subprogram');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFungsional extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'fungsional_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'fungsional_name' => [
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
        $this->forge->addKey('fungsional_id', true);
        $this->forge->createTable('tbl_fungsional');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_fungsional');
    }
}

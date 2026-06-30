<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePesan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pesan_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pesan_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'subject' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pesan' => [
                'type' => 'TEXT',
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
            ],
        ]);
        $this->forge->addKey('pesan_id', true);
        $this->forge->createTable('tbl_pesan');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_pesan');
    }
}

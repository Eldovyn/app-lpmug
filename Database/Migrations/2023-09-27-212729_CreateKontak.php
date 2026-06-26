<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKontak extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kontak_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kontak_name' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
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
        $this->forge->addKey('kontak_id', true);
        $this->forge->createTable('tbl_kontak');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_kontak');
    }
}

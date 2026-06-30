<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJurusan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'jurusan_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jurusan_name' => [
                'type'       => 'TEXT',
            ],
            'fakultas_id' => [
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
        $this->forge->addKey('jurusan_id', true);
        $this->forge->addForeignKey('fakultas_id', 'tbl_fakultas', 'fakultas_id');
        $this->forge->createTable('tbl_jurusan');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_jurusan');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStruktur extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'struktur_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gambar' => [
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
        $this->forge->addKey('struktur_id', true);
        $this->forge->createTable('tbl_struktur');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_struktur');
    }
}

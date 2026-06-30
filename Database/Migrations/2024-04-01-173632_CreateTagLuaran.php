<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTagLuaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tag_luaran_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'laporan_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
            ],
            'luaran_id' => [
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
        $this->forge->addKey('tag_luaran_id', true);
        $this->forge->addForeignKey('laporan_id', 'tbl_laporan', 'laporan_id');
        $this->forge->addForeignKey('luaran_id', 'tbl_luaran', 'luaran_id');
        $this->forge->createTable('tbl_tag_luaran');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_tag_luaran');
    }
}

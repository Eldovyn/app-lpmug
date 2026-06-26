<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbdimas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'laporan_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ketua_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'null'           => true,
            ],
            'kota_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'null'           => true,
            ],
            'mitra_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'null'           => true,
            ],
            'subprogram_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'null'           => true,
            ],
            'periode_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'null'           => true,
            ],
            'range_dana' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'proposal' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'laporan' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'link_luaran' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'verifikasi' => [
                'type'           => 'INT',
                'constraint'     => 20,
                'null'           => true,
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
        $this->forge->addKey('laporan_id', true);
        $this->forge->addForeignKey('ketua_id', 'tbl_users', 'user_id');
        $this->forge->addForeignKey('kota_id', 'tbl_kota', 'kota_id');
        $this->forge->addForeignKey('mitra_id', 'tbl_users', 'user_id');
        $this->forge->addForeignKey('subprogram_id', 'tbl_subprogram', 'subprogram_id');
        $this->forge->addForeignKey('periode_id', 'tbl_periode', 'periode_id');
        $this->forge->createTable('tbl_laporan');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_laporan');
    }
}

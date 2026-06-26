<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblHibah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'ketua_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'approved', 'rejected'],
                'default' => 'draft',
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'anggaran' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pesan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'proposal_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            // New columns for flag system
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
                'comment' => 'User yang mengupload hibah',
            ],
            'posisi_dosen' => [
                'type' => 'ENUM',
                'constraint' => ['ketua', 'anggota'],
                'default' => 'ketua',
            ],
            'verification_status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'submitted', 'approved', 'rejected'],
                'default' => 'draft',
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'verified_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'verification_notes' => [
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
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('ketua_id');
        $this->forge->addKey('verification_status');
        $this->forge->addForeignKey('user_id', 'tbl_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ketua_id', 'tbl_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('verified_by', 'tbl_users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_hibah');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_hibah');
    }
}
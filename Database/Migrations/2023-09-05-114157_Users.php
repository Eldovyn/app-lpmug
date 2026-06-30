<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'sinta_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nidn' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'gelar_dpn' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'gelar_blkng' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'universitas' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'jurusan_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
            ],
            'jbtn_fgsnl' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'photo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => '2',
            ],
            'timestamp' => [
                'type' => 'DATE'
            ],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('jurusan_id', 'tbl_jurusan', 'jurusan_id');
        $this->forge->createTable('tbl_users');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_users');
    }
}

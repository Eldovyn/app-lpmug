<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sinta_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'nidn' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'user_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gelar_dpn' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'gelar_blkng' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'universitas_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'jurusan_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'jurusan_nya' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'fungsional_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'password' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kontak' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'photo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'default' => '1',
            ],
            'kota_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kebutuhan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'role_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
            ],
            'spm' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'skm' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            // Flag columns
            'flag_status' => [
                'type' => 'ENUM',
                'constraint' => ['inactive', 'pending', 'active'],
                'default' => 'inactive',
            ],
            'flag_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'flag_verified_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'flag_notes' => [
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
        
        $this->forge->addKey('user_id', true);
        $this->forge->addKey('role_id');
        $this->forge->addKey('flag_status');
        $this->forge->createTable('tbl_users');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_users');
    }
}
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHibahAnggota extends Migration
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
            'hibah_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
            ],
            'anggota_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
            ],
            'posisi' => [
                'type' => "ENUM('ketua', 'anggota')",
                'default' => 'anggota',
                'null' => false,
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
        $this->forge->addKey(['hibah_id', 'anggota_id'], false, true); // composite unique key
        $this->forge->createTable('hibah_anggota');

        // Add foreign keys
        $this->forge->addForeignKey('hibah_id', 'tbl_hibah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('anggota_id', 'tbl_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('hibah_anggota');
    }

    public function down()
    {
        $this->forge->dropForeignKey('hibah_anggota', 'hibah_anggota_hibah_id_foreign');
        $this->forge->dropForeignKey('hibah_anggota', 'hibah_anggota_anggota_id_foreign');
        $this->forge->dropTable('hibah_anggota');
    }
}

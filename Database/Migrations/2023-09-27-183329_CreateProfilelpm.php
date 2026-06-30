<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfilelpm extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'profilelpm_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'status' => [
                'type'           => 'INT',
                'constraint'     => 5,
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
        $this->forge->addKey('profilelpm_id', true);
        $this->forge->createTable('tbl_profilelpm');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_profilelpm');
    }
}

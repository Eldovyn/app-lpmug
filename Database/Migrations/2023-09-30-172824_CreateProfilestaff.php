<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfilestaff extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'profilestaff_id' => [
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
        $this->forge->addKey('profilestaff_id', true);
        $this->forge->createTable('tbl_profilestaff');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_profilestaff');
    }
}

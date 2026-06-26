<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKota extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kota_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kota_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'provinsi_id' => [
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
        $this->forge->addKey('kota_id', true);
        $this->forge->addForeignKey('provinsi_id', 'tbl_provinsi', 'provinsi_id');
        $this->forge->createTable('tbl_kota');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_kota');
    }
}

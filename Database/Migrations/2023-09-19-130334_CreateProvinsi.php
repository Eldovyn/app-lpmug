<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProvinsi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'provinsi_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'provinsi_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
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
        $this->forge->addKey('provinsi_id', true);
        $this->forge->createTable('tbl_provinsi');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_provinsi');
    }
}

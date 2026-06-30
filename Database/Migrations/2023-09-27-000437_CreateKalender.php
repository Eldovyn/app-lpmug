<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKalender extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kalender_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kegiatan' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'waktu' => [
                'type'       => 'TEXT',
                'null' => true,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
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
        $this->forge->addKey('kalender_id', true);
        $this->forge->createTable('tbl_kalender');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_kalender');
    }
}

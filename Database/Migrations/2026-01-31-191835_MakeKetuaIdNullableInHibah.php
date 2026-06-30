<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeKetuaIdNullableInHibah extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('tbl_hibah', [
            'ketua_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true, // Make it nullable
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('tbl_hibah', [
            'ketua_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false, // Make it not nullable again
            ],
        ]);
    }
}

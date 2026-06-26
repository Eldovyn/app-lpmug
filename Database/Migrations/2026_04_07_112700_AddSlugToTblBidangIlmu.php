<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToTblBidangIlmu extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'unique'     => true,
                'after'      => 'nama',
            ],
        ];
        
        $this->forge->addColumn('tbl_bidang_ilmu', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_bidang_ilmu', 'slug');
    }
}

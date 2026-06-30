<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTblLaporanAddBidangIlmuId extends Migration
{
    public function up()
    {
        // Add column bidang_ilmu_id to tbl_laporan
        $fields = [
            'bidang_ilmu_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];
        
        $this->forge->addColumn('tbl_laporan', $fields);
        
        // Add foreign key constraint (optional, can be added later if needed)
        // $this->forge->addForeignKey('bidang_ilmu_id', 'tbl_bidang_ilmu', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_laporan', 'bidang_ilmu_id');
    }
}


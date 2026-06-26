<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyBidangIlmuToLaporan extends Migration
{
    public function up()
    {
        // Add foreign key constraint to tbl_laporan.bidang_ilmu_id
        $this->forge->addForeignKey('bidang_ilmu_id', 'tbl_bidang_ilmu', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('tbl_laporan', 'bidang_ilmu_id');
    }
}


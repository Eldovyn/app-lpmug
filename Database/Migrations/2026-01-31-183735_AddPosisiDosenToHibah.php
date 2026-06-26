<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPosisiDosenToHibah extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_hibah', [
            'posisi_dosen' => [
                'type' => "ENUM('ketua', 'anggota')",
                'null' => true,
                'after' => 'proposal_file',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_hibah', 'posisi_dosen');
    }
}

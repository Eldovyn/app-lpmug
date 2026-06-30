<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Akses extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'role_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'role_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('role_id', true);
        $this->forge->createTable('tbl_role');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_role');
    }
}

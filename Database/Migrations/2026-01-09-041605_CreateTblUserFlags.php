<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTblUserFlags extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => false,
            ],
            'hibah_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'flag_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'flag_value' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['request', 'approve', 'reject'],
                'null' => true,
            ],
            'action_by' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ],
            'notes' => [
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
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addKey('hibah_id');
        $this->forge->addForeignKey('user_id', 'tbl_users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hibah_id', 'tbl_hibah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('action_by', 'tbl_users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('tbl_user_flags');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_user_flags');
    }
}
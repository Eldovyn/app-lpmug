<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlagColumnsToTblUsers extends Migration
{
    public function up()
    {
        // Check if columns exist before adding
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('tbl_users');
        $fieldNames = array_column($fields, 'name');
        
        // Add flag_status column if not exists
        if (!in_array('flag_status', $fieldNames)) {
            $this->forge->addColumn('tbl_users', [
                'flag_status' => [
                    'type' => 'ENUM',
                    'constraint' => ['inactive', 'pending', 'active'],
                    'default' => 'inactive',
                    'after' => 'skm'
                ],
            ]);
        }
        
        // Add flag_verified_at column if not exists
        if (!in_array('flag_verified_at', $fieldNames)) {
            $this->forge->addColumn('tbl_users', [
                'flag_verified_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'flag_status'
                ],
            ]);
        }
        
        // Add flag_verified_by column if not exists
        if (!in_array('flag_verified_by', $fieldNames)) {
            $this->forge->addColumn('tbl_users', [
                'flag_verified_by' => [
                    'type' => 'BIGINT',
                    'constraint' => 20,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'flag_verified_at'
                ],
            ]);
        }
        
        // Add flag_notes column if not exists
        if (!in_array('flag_notes', $fieldNames)) {
            $this->forge->addColumn('tbl_users', [
                'flag_notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'flag_verified_by'
                ],
            ]);
        }
        
        // Add index for flag_status
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE tbl_users ADD INDEX idx_flag_status (flag_status)');
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('tbl_users', 'flag_status');
        $this->forge->dropColumn('tbl_users', 'flag_verified_at');
        $this->forge->dropColumn('tbl_users', 'flag_verified_by');
        $this->forge->dropColumn('tbl_users', 'flag_notes');
        
        // Remove index
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE tbl_users DROP INDEX idx_flag_status');
    }
}

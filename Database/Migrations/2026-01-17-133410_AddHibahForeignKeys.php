<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHibahForeignKeys extends Migration
{
    public function up()
    {
        // Check if foreign keys already exist before adding them
        $db = \Config\Database::connect();
        $foreignKeys = $db->getForeignKeyData('tbl_hibah');

        $existingConstraints = [];
        foreach ($foreignKeys as $fk) {
            $existingConstraints[] = $fk->constraint_name;
        }

        // Add foreign key constraints for tbl_hibah only if they don't exist
        if (!in_array('tbl_hibah_ketua_id_foreign', $existingConstraints)) {
            $this->forge->addForeignKey('ketua_id', 'tbl_users', 'user_id', 'CASCADE', 'SET NULL');
        }
        if (!in_array('tbl_hibah_user_id_foreign', $existingConstraints)) {
            $this->forge->addForeignKey('user_id', 'tbl_users', 'user_id', 'CASCADE', 'CASCADE');
        }
        if (!in_array('tbl_hibah_verified_by_foreign', $existingConstraints)) {
            $this->forge->addForeignKey('verified_by', 'tbl_users', 'user_id', 'CASCADE', 'SET NULL');
        }

        // Process the foreign keys
        $this->forge->processIndexes('tbl_hibah');
    }

    public function down()
    {
        // Drop foreign key constraints
        $this->forge->dropForeignKey('tbl_hibah', 'tbl_hibah_ketua_id_foreign');
        $this->forge->dropForeignKey('tbl_hibah', 'tbl_hibah_user_id_foreign');
        $this->forge->dropForeignKey('tbl_hibah', 'tbl_hibah_verified_by_foreign');
    }
}

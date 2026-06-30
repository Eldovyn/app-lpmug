<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableHibahAnggota extends Migration
{
    public function up()
    {
        $this->db->query('
            CREATE TABLE IF NOT EXISTS `tbl_hibah_anggota` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `hibah_id` INT NOT NULL,
                `anggota_id` INT NOT NULL,
                `anggota_nama` VARCHAR(255) NULL,
                `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_hibah_id` (`hibah_id`),
                INDEX `idx_anggota_id` (`anggota_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `tbl_hibah_anggota`');
    }
}

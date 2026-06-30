<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        // Add performance indexes on foreign keys and commonly filtered columns
        // We catch exceptions in case an index already exists
        
        $db = \Config\Database::connect();
        
        // tbl_laporan indexes
        try {
            $db->query("ALTER TABLE tbl_laporan ADD INDEX idx_laporan_ketua (ketua_id)");
        } catch (\Exception $e) {}
        
        try {
            $db->query("ALTER TABLE tbl_laporan ADD INDEX idx_laporan_mitra (mitra_id)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan ADD INDEX idx_laporan_periode (periode_id)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan ADD INDEX idx_laporan_verifikasi (verifikasi)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan ADD INDEX idx_laporan_ketua_verifikasi (ketua_id, verifikasi)");
        } catch (\Exception $e) {}

        // tbl_tags indexes
        try {
            $db->query("ALTER TABLE tbl_tags ADD INDEX idx_tags_laporan (laporan_id)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tags ADD INDEX idx_tags_anggota (anggota_id)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tags ADD INDEX idx_tags_laporan_anggota (laporan_id, anggota_id)");
        } catch (\Exception $e) {}

        // tbl_tag_luaran indexes
        try {
            $db->query("ALTER TABLE tbl_tag_luaran ADD INDEX idx_tag_luaran_laporan (laporan_id)");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tag_luaran ADD INDEX idx_tag_luaran_luaran (luaran_id)");
        } catch (\Exception $e) {}
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        try {
            $db->query("ALTER TABLE tbl_laporan DROP INDEX idx_laporan_ketua");
        } catch (\Exception $e) {}
        
        try {
            $db->query("ALTER TABLE tbl_laporan DROP INDEX idx_laporan_mitra");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan DROP INDEX idx_laporan_periode");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan DROP INDEX idx_laporan_verifikasi");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_laporan DROP INDEX idx_laporan_ketua_verifikasi");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tags DROP INDEX idx_tags_laporan");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tags DROP INDEX idx_tags_anggota");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tags DROP INDEX idx_tags_laporan_anggota");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tag_luaran DROP INDEX idx_tag_luaran_laporan");
        } catch (\Exception $e) {}

        try {
            $db->query("ALTER TABLE tbl_tag_luaran DROP INDEX idx_tag_luaran_luaran");
        } catch (\Exception $e) {}
    }
}

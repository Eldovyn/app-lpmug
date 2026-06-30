<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BidangIlmuSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Ilmu Pengetahuan Alam (IPA) & Matematika: Mencakup Matematika, Fisika, Biologi, Astronomi, Geofisika, dan Meteorologi.',
                'slug' => 'ipa-matematika',
            ],
            [
                'nama' => 'Ilmu Teknik & Rekayasa: Fokus pada aplikasi teknologi, rekayasa sipil, mesin, serta perangkat lunak.',
                'slug' => 'teknik-rekayasa',
            ],
            [
                'nama' => 'Ilmu Kesehatan & Kedokteran: Meliputi Ilmu Kedokteran, Kedokteran Gigi, Farmasi, Gizi, dan Kesehatan Lingkungan.',
                'slug' => 'kesehatan-kedokteran',
            ],
            [
                'nama' => 'Ilmu Sosial, Humaniora, & Seni: Meliputi Sosiologi, Hukum, Antropologi, Seni, dan Desain.',
                'slug' => 'sosial-humaniora-seni',
            ],
            [
                'nama' => 'Ilmu Pertanian & Tanaman: Meliputi Ilmu Tanah, Hortikultura, dan Budidaya Perkebunan.',
                'slug' => 'pertanian-tanaman',
            ],
        ];

        // Insert with ignore to avoid duplicates
        $this->db->table('tbl_bidang_ilmu')->insertBatch($data);
    }
}


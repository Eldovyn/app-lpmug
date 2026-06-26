<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use \App\Filters\LoginFilter;
use \App\Filters\FilterAdmin;
use \App\Filters\CsrfAutoInjectFilter;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, string>
     * @phpstan-var array<string, class-string>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'isLoggedIn'    => LoginFilter::class,
        'filterAdmin'   => FilterAdmin::class,
        'csrfAutoInject'=> CsrfAutoInjectFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, array<string>>
     * @phpstan-var array<string, list<string>>|array<string, array<string, array<string, string>>>
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf',
            // 'invalidchars',
            // 'isLoggedIn' => [
            //     'except' => ['/', 'login' ,'login/*']
            // ],
        ],
        'after' => [
            // 'isLoggedIn' => [
            //     'except' => [
            //         'dashboard',
            //         'hak_akses',
            //         'hak_akses/*',
            //         'provinsi',
            //         'provinsi/*',
            //         'kota',
            //         'kota/*',
            //         'periode',
            //         'periode/*',
            //         'universitas',
            //         'universitas/*',
            //         'fungsional',
            //         'fungsional/*',
            //         'fakultas',
            //         'fakultas/*',
            //         'jurusan',
            //         'jurusan/*',
            //         'topik',
            //         'topik/*',
            //         'program',
            //         'program/*',
            //         'subprogram',
            //         'subprogram/*',
            //         'pengguna',
            //         'pengguna/*',
            //         'dosen',
            //         'dosen/*',
            //         'mitra',
            //         'mitra/*',
            //     ]
            // ],
            'toolbar',
            // 'honeypot',
            'secureheaders',
            'csrfAutoInject',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [
        'isLoggedIn' => ['before' => 
            [
                'dashboard',
                'profile_user',
                'profile_user/*',
                'profile_user_admin',
                'profile_user_admin/*',
                'profile_user_staff',
                'profile_user_staff/*',
                'profile_user_dosen',
                'profile_user_dosen/*',
                'profile_user_mitra',
                'profile_user_mitra/*',
                'ubah_password',
                'ubah_password/*',
                'ubah_password_admin',
                'ubah_password_admin/*',
                'ubah_password_staff',
                'ubah_password_staff/*',
                'ubah_password_dosen',
                'ubah_password_dosen/*',
                'ubah_password_mitra',
                'ubah_password_mitra/*',
                'hak_akses',
                'hak_akses/*',
                'provinsi',
                'provinsi/*',
                'kota',
                'kota/*',
                'periode',
                'periode/*',
                'universitas',
                'universitas/*',
                'fungsional',
                'fungsional/*',
                'fakultas',
                'fakultas/*',
                'jurusan',
                'jurusan/*',
                'topik',
                'topik/*',
                'program',
                'program/*',
                'subprogram',
                'subprogram/*',
                'pengguna',
                'pengguna/*',
                'dosen',
                'dosen/*',
                // 'mitra',
                // 'mitra/*',
                'listdosen',
                'listdosen/*',
                'listmitra',
                'listmitra/*',
                'profilelpm',
                'profilelpm/*',
                'profilestaff',
                'profilestaff/*',
                'struktur',
                'struktur/*',
                'galeri',
                'galeri/*',
                'kontak',
                'kontak/*',
                'pesan',
                'pesan/*',
                'abdimas',
                'abdimas/*',
                'pelaksanaan',
                'pelaksanaan/*',
                'monev',
                'monev/*',
                'monevadmin',
                'monevadmin/*',
                'rekapan',
                'rekapan/*',
                'pelaporan',
                'pelaporan/*',
                'pengesahan/',
                'pengesahan/*',
                'hibah',
                'hibah/*',
                'sertifikat',
                'sertifikat/*',
            ]
        ],
    ];
}

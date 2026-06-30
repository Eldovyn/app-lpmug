<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    /* Premium Light Sidebar Override */
    body:not(.sidebar-mini) .main-sidebar {
        width: 260px !important;
    }

    body.sidebar-mini .main-sidebar {
        width: 65px !important;
        overflow: visible !important;
        position: absolute !important;
    }

    body.sidebar-mini .main-content {
        padding-left: 105px !important; /* 65 + 40 */
    }
    
    .main-sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        background: #ffffff !important;
        box-shadow: 4px 0 24px rgba(0,0,0,0.04) !important;
        overflow: hidden;
        z-index: 8800;
        font-family: 'Inter', sans-serif !important;
        border-right: 1px solid #f1f5f9;
        transition: width 0.3s;
    }

    .main-sidebar #sidebar-wrapper {
        position: relative;
        height: 100%;
        padding-top: 75px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Scrollbar styling */
    .main-sidebar #sidebar-wrapper::-webkit-scrollbar { width: 5px; }
    .main-sidebar #sidebar-wrapper::-webkit-scrollbar-track { background: transparent; }
    .main-sidebar #sidebar-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .main-sidebar #sidebar-wrapper:hover::-webkit-scrollbar-thumb { background: #94a3b8; }

    .main-sidebar .sidebar-brand {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 75px;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #f1f5f9;
    }

    body.sidebar-mini .main-sidebar .sidebar-brand {
        display: none !important;
    }
    
    .main-sidebar .sidebar-brand a {
        font-weight: 800;
        font-size: 22px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 0.5px;
        text-decoration: none;
    }

    .main-sidebar .sidebar-brand-sm a {
        font-weight: 800;
        font-size: 18px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-decoration: none;
    }

    /* Menu Headers */
    .main-sidebar .sidebar-menu li.menu-header {
        padding: 20px 20px 10px 20px !important;
        color: #94a3b8 !important; /* slate-400 */
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase !important;
        background: transparent !important;
    }

    body.sidebar-mini .main-sidebar .sidebar-menu li.menu-header {
        display: none !important;
    }

    /* Menu Links */
    .main-sidebar .sidebar-menu > li > a {
        color: #475569 !important; /* slate-600 */
        font-weight: 500 !important;
        font-size: 14px !important;
        padding: 12px 20px !important;
        height: auto !important;
        display: flex !important;
        align-items: center !important;
        transition: all 0.25s ease !important;
        margin: 4px 12px !important;
        border-radius: 8px !important;
    }

    .main-sidebar .sidebar-menu > li > a i {
        color: #94a3b8 !important; /* slate-400 */
        font-size: 16px !important;
        width: 28px !important;
        text-align: left !important;
        transition: all 0.25s ease !important;
    }

    /* Hover State */
    body:not(.sidebar-mini) .main-sidebar .sidebar-menu > li > a:hover {
        background: #f8fafc !important; /* slate-50 */
        color: #1e293b !important; /* slate-800 */
        transform: translateX(4px);
    }
    body:not(.sidebar-mini) .main-sidebar .sidebar-menu > li > a:hover i {
        color: #6366f1 !important; /* indigo-500 */
    }

    /* Active State */
    body:not(.sidebar-mini) .main-sidebar .sidebar-menu > li.active > a {
        background: linear-gradient(90deg, #eef2ff 0%, #ffffff 100%) !important;
        color: #6366f1 !important;
        font-weight: 600 !important;
        border-left: 3px solid #6366f1 !important;
        border-radius: 0 8px 8px 0 !important;
        margin-left: 0 !important;
        padding-left: 29px !important; /* 32 - 3 border */
        box-shadow: 2px 0 8px rgba(99,102,241,0.05) !important;
    }
    body:not(.sidebar-mini) .main-sidebar .sidebar-menu > li.active > a i {
        color: #6366f1 !important;
    }

    /* Dropdown Menus */
    .main-sidebar .sidebar-menu > li > a.has-dropdown::after {
        content: '\f105' !important;
        font-family: 'Font Awesome 5 Free' !important;
        font-weight: 900 !important;
        color: #cbd5e1 !important;
        border: none !important;
        top: 50% !important;
        right: 15px !important;
        transform: translateY(-50%) !important;
        transition: transform 0.3s ease !important;
    }
    .main-sidebar .sidebar-menu > li.active > a.has-dropdown::after {
        transform: translateY(-50%) rotate(90deg) !important;
        color: #6366f1 !important;
    }

    .main-sidebar .sidebar-menu li ul.dropdown-menu {
        background: #ffffff !important;
        padding: 5px 0 !important;
        border: none !important;
        margin: 0 !important;
    }

    .main-sidebar .sidebar-menu li ul.dropdown-menu li a {
        color: #64748b !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        padding: 10px 20px 10px 50px !important;
        height: auto !important;
        transition: all 0.2s ease !important;
    }

    .main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover {
        color: #1e293b !important;
        background: transparent !important;
        transform: translateX(4px);
    }
    
    .main-sidebar .sidebar-menu li ul.dropdown-menu li.active > a {
        color: #6366f1 !important;
        font-weight: 600 !important;
        background: transparent !important;
    }

    .main-sidebar .sidebar-menu li ul.dropdown-menu li a::before {
        content: '' !important;
        position: absolute !important;
        left: 32px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 6px !important;
        height: 6px !important;
        border-radius: 50% !important;
        border: 2px solid #cbd5e1 !important;
        background: transparent !important;
        transition: all 0.2s ease !important;
    }
    .main-sidebar .sidebar-menu li ul.dropdown-menu li a:hover::before {
        border-color: #6366f1 !important;
    }
    .main-sidebar .sidebar-menu li ul.dropdown-menu li.active > a::before {
        background: #6366f1 !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 8px rgba(99,102,241,0.2) !important;
    }

    /* Logout Button in Sidebar */
    .main-sidebar .hide-sidebar-mini #logout {
        background: #fff1f2 !important; /* rose-50 */
        color: #f43f5e !important;
        border: 1px solid #ffe4e6 !important; /* rose-100 */
        font-family: 'Inter', sans-serif !important;
        font-weight: 600 !important;
        box-shadow: none !important;
        transition: all 0.3s ease !important;
        border-radius: 10px !important;
    }
    .main-sidebar .hide-sidebar-mini #logout:hover {
        background: #f43f5e !important;
        color: #ffffff !important;
        border-color: #f43f5e !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2) !important;
    }
</style>


<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif
$allowed = ['id', 'en'];
$lang    = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// translasi
$TR = [
    'id' => [
        // headers
        'header_main'      => 'Menu Utama',
        'header_community' => 'Pengabdian Masyarakat',
        'header_settings'  => 'Pengaturan',
        'header_upload'    => 'Upload Dokumen',
        'header_reply'     => 'Surat Balasan',
        'header_account'   => 'Akun & Bantuan',

        // items umum
        'dashboard'        => 'Dashboard',
        'my_profile'       => 'Profile saya',
        'change_password'  => 'Ubah password',
        'user_access'      => 'Akses Pengguna',
        'users'            => 'Data Pengguna',
        'documents'        => 'Dokumen',

        'period'           => 'Periode (ATA/PTA)',
        'lecturers_ug'     => 'List Dosen UG',
        'lecturers'        => 'List Dosen',
        'partners'         => 'List Mitra Abdimas',

        'calendar'         => 'Kalender Pengabdian',

        'lpm_profile'      => 'Profile LPM',
        'staff_profile'    => 'Profile Staff LPM',
        'structure'        => 'Struktur LPM',
        'gallery'          => 'Gallery',

        'faq'              => 'FAQ',

        // groups
        'group_region'      => 'Wilayah',
        'group_affiliation' => 'Afilliasi',
        'group_research'    => 'Penelitian',
        'group_service'     => 'Pengabdian',
        'group_contact'     => 'Kontak',

        // sub items
        'province_list'     => 'List Provinsi',
        'city_list'         => 'List Kota / Kabupaten',

        'university_list'   => 'List Universitas',
        'functional_list'   => 'List Jabatan Fungsional',
        'faculty_list'      => 'List Fakultas',
        'department_list'   => 'List Jurusan',

        'topic_list'        => 'List Topik Penelitian',
        'program_list'      => 'List Program',
        'subprogram_list'   => 'List Sub Program',
        'output_list'       => 'List Luaran',

        'service_proposal'       => 'Pengusulan',
        'service_abdimas'        => 'Pengabdian',
        'service_implementation' => 'Pelaksanaan',
        'service_monev'          => 'Penilaian Monev',
        'service_monev_short'    => 'MonEv',
        'service_reporting'      => 'Pelaporan',
        'service_recap'          => 'Rekapan Abdimas',
        'service_recap_base'     => 'Rekapan',

        'recap_base_label'       => 'Rekapan',
        'recap_smt_label'        => 'Rekapan Smt',

        'contact_personal'       => 'Contact Personal',
        'inbox_messages'         => 'Pesan Masuk',

        'upload_spm'             => 'Upload SPM',
        'upload_skm'             => 'Upload SKM',
        'reply_letter'           => 'Surat Balasan',

        'guide_video'            => 'Video Panduan',

        // status labels
        'status_all'        => 'Semua',
        'status_processing' => 'Diproses',
        'status_revised'    => 'Direvisi',
        'status_approved'   => 'Disetujui',
        'status_rejected'   => 'Ditolak',

        // hibah
        'hibah'             => 'Verifikasi Hibah',
        'hibah_verification' => 'Verifikasi Hibah',
        'hibah_label'       => 'Hibah',
        'hibah_upload'      => 'Upload Hibah',
        'hibah_my'          => 'Hibah Saya',
        'active_flags'      => 'Akun Flag Aktif',
        
        // sertifikat
        'sertifikat'        => 'Sertifikat',

        // logout
        'logout'            => 'Keluar',
    ],

    'en' => [
        // headers
        'header_main'      => 'Main Menu',
        'header_community' => 'Community Service',
        'header_settings'  => 'Settings',
        'header_upload'    => 'Document Upload',
        'header_reply'     => 'Reply Letters',
        'header_account'   => 'Account & Help',

        // items umum
        'dashboard'        => 'Dashboard',
        'my_profile'       => 'My Profile',
        'change_password'  => 'Change Password',
        'user_access'      => 'User Access',
        'users'            => 'Users',
        'documents'        => 'Documents',

        'period'           => 'Period (ATA/PTA)',
        'lecturers_ug'     => 'UG Lecturers List',
        'lecturers'        => 'Lecturers List',
        'partners'         => 'Community Partners',

        'calendar'         => 'Community Calendar',

        'lpm_profile'      => 'LPM Profile',
        'staff_profile'    => 'LPM Staff Profile',
        'structure'        => 'LPM Structure',
        'gallery'          => 'Gallery',

        'faq'              => 'FAQ',

        // groups
        'group_region'      => 'Region',
        'group_affiliation' => 'Affiliation',
        'group_research'    => 'Research',
        'group_service'     => 'Community Service',
        'group_contact'     => 'Contacts',

        // sub items
        'province_list'     => 'Provinces',
        'city_list'         => 'Cities / Regencies',

        'university_list'   => 'Universities',
        'functional_list'   => 'Functional Positions',
        'faculty_list'      => 'Faculties',
        'department_list'   => 'Departments',

        'topic_list'        => 'Research Topics',
        'program_list'      => 'Programs',
        'subprogram_list'   => 'Sub Programs',
        'output_list'       => 'Outputs',

        'service_proposal'       => 'Proposal',
        'service_abdimas'        => 'Community Service',
        'service_implementation' => 'Implementation',
        'service_monev'          => 'Monev Assessment',
        'service_monev_short'    => 'M&E',
        'service_reporting'      => 'Reporting',
        'service_recap'          => 'Community Recap',
        'service_recap_base'     => 'Recap',

        'recap_base_label'       => 'Recap',
        'recap_smt_label'        => 'Sem. Recap',

        'contact_personal'       => 'Personal Contacts',
        'inbox_messages'         => 'Inbox Messages',

        'upload_spm'             => 'Upload SPM',
        'upload_skm'             => 'Upload SKM',
        'reply_letter'           => 'Reply Letter',

        'guide_video'            => 'Guide Video',

        'status_all'        => 'All',
        'status_processing' => 'Processing',
        'status_revised'    => 'Revised',
        'status_approved'   => 'Approved',
        'status_rejected'   => 'Rejected',

        'hibah'             => 'Grant Verification',
        'hibah_verification' => 'Grant Verification',
        'hibah_label'       => 'Grant',
        'hibah_upload'      => 'Upload Grant',
        'hibah_my'          => 'My Grants',
        'active_flags'      => 'Active Flag Accounts',

        'logout'            => 'Logout',
    ],
];

// closure translasi
$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= site_url('dashboard'); ?>">LPM UG</a>
        </div>

        <ul class="sidebar-menu">

            <!-- ============ SUPERIOR (role_id 1) ============ -->
            <?php if (userLogin()->role_id == 1): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('hak_akses'); ?>"><i class="fab fa-superpowers"></i> <span><?= esc($t('user_access')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('pengguna'); ?>"><i class="fas fa-chalkboard-teacher"></i> <span><?= esc($t('users')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_community')) ?></li>
                <li><a class="nav-link" href="<?= site_url('periode'); ?>"><i class="fas fa-clock"></i> <span><?= esc($t('period')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('dosen'); ?>"><i class="fas fa-users"></i> <span><?= esc($t('lecturers_ug')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('mitra'); ?>"><i class="fas fa-store-alt"></i> <span><?= esc($t('partners')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('hibah/verification-list'); ?>"><i class="fas fa-check-circle"></i> <span><?= esc($t('hibah_verification')) ?></span></a></li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marked-alt"></i><span><?= esc($t('group_region')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('provinsi'); ?>"><?= esc($t('province_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('kota'); ?>"><?= esc($t('city_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-book-reader"></i><span><?= esc($t('group_affiliation')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('universitas'); ?>"><?= esc($t('university_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('fungsional'); ?>"><?= esc($t('functional_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('fakultas'); ?>"><?= esc($t('faculty_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('jurusan'); ?>"><?= esc($t('department_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-list"></i><span><?= esc($t('group_research')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('topik'); ?>"><?= esc($t('topic_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('program'); ?>"><?= esc($t('program_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('subprogram'); ?>"><?= esc($t('subprogram_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('luaran'); ?>"><?= esc($t('output_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard-list"></i><span><?= esc($t('group_service')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('abdimas'); ?>"><?= esc($t('service_abdimas')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('pelaksanaan'); ?>"><?= esc($t('service_implementation')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('monev'); ?>"><?= esc($t('service_monev')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan'); ?>"><?= esc($t('service_recap')) ?></a></li>
                    </ul>
                </li>

                <li><a class="nav-link" href="<?= site_url('kalender'); ?>"><i class="far fa-calendar"></i> <span><?= esc($t('calendar')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_settings')) ?></li>
                <li><a class="nav-link" href="<?= site_url('profilelpm'); ?>"><i class="fas fa-laptop-house"></i> <span><?= esc($t('lpm_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profilestaff'); ?>"><i class="fas fa-user"></i> <span><?= esc($t('staff_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('struktur'); ?>"><i class="fas fa-sitemap"></i> <span><?= esc($t('structure')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('galeri'); ?>"><i class="fas fa-photo-video"></i> <span><?= esc($t('gallery')) ?></span></a></li>
            <?php endif; ?>

            <!-- ============ ADMIN (role_id 2) ============ -->
            <?php if (userLogin()->role_id == 2): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user_admin/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password_admin/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('pengguna'); ?>"><i class="fas fa-chalkboard-teacher"></i> <span><?= esc($t('users')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('dokumen'); ?>"><i class="fas fa-file-alt"></i> <span><?= esc($t('documents')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_community')) ?></li>
                <li><a class="nav-link" href="<?= site_url('periode'); ?>"><i class="fas fa-clock"></i> <span><?= esc($t('period')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('dosen'); ?>"><i class="fas fa-users"></i> <span><?= esc($t('lecturers_ug')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('mitra'); ?>"><i class="fas fa-store-alt"></i> <span><?= esc($t('partners')) ?></span></a></li>
                
                <!-- HIBAH (ADMIN) -->
                <li><a class="nav-link" href="<?= site_url('hibah/verification-list'); ?>"><i class="fas fa-check-circle"></i> <span><?= esc($t('hibah')) ?></span></a></li>
              
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marked-alt"></i><span><?= esc($t('group_region')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('provinsi'); ?>"><?= esc($t('province_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('kota'); ?>"><?= esc($t('city_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-book-reader"></i><span><?= esc($t('group_affiliation')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('universitas'); ?>"><?= esc($t('university_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('fungsional'); ?>"><?= esc($t('functional_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('fakultas'); ?>"><?= esc($t('faculty_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('jurusan'); ?>"><?= esc($t('department_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-list"></i><span><?= esc($t('group_research')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('topik'); ?>"><?= esc($t('topic_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('program'); ?>"><?= esc($t('program_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('subprogram'); ?>"><?= esc($t('subprogram_list')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('luaran'); ?>"><?= esc($t('output_list')) ?></a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard-list"></i><span><?= esc($t('group_service')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('rekapan'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-dark pl-1"><?= esc($t('status_all')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/proses'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-primary pl-1"><?= esc($t('status_processing')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/revisi'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-warning pl-1"><?= esc($t('status_revised')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/setuju'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-success pl-1"><?= esc($t('status_approved')) ?></b></a></li>
                    </ul>
                </li>

                <!-- Rekapan Semester - Dropdown Menu -->
                <?php
                $currentUri = service('uri')->getPath();
                $isDataSemester = strpos($currentUri, 'data_semester') !== false;
                $currentSemester = service('request')->getGet('semester');
                $semesterParam = $currentSemester !== null && $currentSemester !== '' ? (string)$currentSemester : '';
                ?>
                <li class="nav-item dropdown<?= $isDataSemester ? ' active' : '' ?>">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-calendar-alt"></i><span><?= esc($t('recap_smt_label')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link<?= $isDataSemester && $semesterParam === '' ? ' active' : '' ?>" href="<?= site_url('data_semester'); ?>"><?= esc($t('status_all')) ?></a></li>
                        <?php
                        $periodeModel = new \App\Models\PeriodeModel();
                        foreach ($periodeModel->getAllPeriodes() as $p): ?>
                            <li><a class="nav-link<?= $isDataSemester && $semesterParam === (string)$p->periode_id ? ' active' : '' ?>" href="<?= site_url('data_semester?semester=' . $p->periode_id); ?>"><?= esc($p->periode_name) ?> <?= esc($p->tahun_ajaran ?? '') ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <li><a class="nav-link" href="<?= site_url('kalender'); ?>"><i class="far fa-calendar"></i> <span><?= esc($t('calendar')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_settings')) ?></li>
                <li><a class="nav-link" href="<?= site_url('profilelpm'); ?>"><i class="fas fa-laptop-house"></i> <span><?= esc($t('lpm_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profilestaff'); ?>"><i class="fas fa-user"></i> <span><?= esc($t('staff_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('struktur'); ?>"><i class="fas fa-sitemap"></i> <span><?= esc($t('structure')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('galeri'); ?>"><i class="fas fa-photo-video"></i> <span><?= esc($t('gallery')) ?></span></a></li>
            <?php endif; ?>

            <!-- ============ REVIEWER (role_id 3) ============ -->
            <?php if (userLogin()->role_id == 3): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user_staff/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password_staff/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_community')) ?></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard-list"></i><span><?= esc($t('group_service')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('rekapan'); ?>"><?= esc($t('service_recap_base')) ?><b class="text-dark pl-1"><?= esc($t('status_all')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/proses'); ?>"><?= esc($t('service_recap_base')) ?><b class="text-primary pl-1"><?= esc($t('status_processing')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/revisi'); ?>"><?= esc($t('service_recap_base')) ?><b class="text-warning pl-1"><?= esc($t('status_revised')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/setuju'); ?>"><?= esc($t('service_recap_base')) ?><b class="text-success pl-1"><?= esc($t('status_approved')) ?></b></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- ============ DOSEN (role_id 4) ============ -->
            <?php if (userLogin()->role_id == 4): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user_dosen/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password_dosen/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_community')) ?></li>
                <li><a class="nav-link" href="<?= site_url('listdosen'); ?>"><i class="fas fa-users"></i> <span><?= esc($t('lecturers')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('mitra'); ?>"><i class="fas fa-store-alt"></i> <span><?= esc($t('partners')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('hibah/myHibah'); ?>"><i class="fas fa-folder-open"></i> <span><?= esc($t('hibah_label')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('sertifikat'); ?>"><i class="fas fa-certificate"></i> <span><?= esc($t('sertifikat')) ?></span></a></li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard-list"></i><span><?= esc($t('group_service')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('abdimas'); ?>"><?= esc($t('service_proposal')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('pelaksanaan'); ?>"><?= esc($t('service_implementation')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('monev'); ?>"><?= esc($t('service_monev_short')) ?></a></li>
                        <li><a class="nav-link" href="<?= site_url('pelaporan'); ?>"><?= esc($t('service_reporting')) ?></a></li>
                    </ul>
                </li>

                <li>
                    <a class="nav-link" target="_blank" href="https://youtu.be/RUWRs9dZEYU?si=e7czl18X_hZbTySa">
                        <i class="fab fa-youtube"></i> <span><?= esc($t('guide_video')) ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ============ MITRA (role_id 5) ============ -->
            <?php if (userLogin()->role_id == 5): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user_mitra/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password_mitra/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_upload')) ?></li>
                <li><a class="nav-link" href="<?= site_url('mitra/upload-spm/' . userLogin()->user_id); ?>"><i class="fas fa-file-upload"></i> <span><?= esc($t('upload_spm')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('mitra/upload-skm/' . userLogin()->user_id); ?>"><i class="fas fa-file-signature"></i> <span><?= esc($t('upload_skm')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_reply')) ?></li>
                <li>
                    <a class="nav-link" href="<?= site_url('mitra/surat-balasan'); ?>">
                        <i class="fas fa-file-pdf"></i> <span><?= esc($t('reply_letter')) ?></span>
                        <?php
                        $laporanModel     = new \App\Models\LaporanModel();
                        $laporans         = $laporanModel->getLaporanByMitra(userLogin()->user_id);
                        $countWithSurat   = 0;
                        $countCanGenerate = 0;
                        foreach ($laporans as $lap) {
                            if (! empty($lap->surat_balasan_path) && file_exists(WRITEPATH . 'berkas/' . $lap->surat_balasan_path)) {
                                $countWithSurat++;
                            } elseif (! empty($lap->judul_kegiatan) && ! empty($lap->ketua_id)) {
                                $countCanGenerate++;
                            }
                        }
                        ?>
                        <?php if ($countWithSurat > 0): ?>
                            <span class="badge badge-success"><?= $countWithSurat ?></span>
                        <?php elseif ($countCanGenerate > 0): ?>
                            <span class="badge badge-warning"><?= $countCanGenerate ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (userLogin()->role_id == 6): ?>
                <li class="menu-header"><?= esc($t('header_main')) ?></li>
                <li><a class="nav-link" href="<?= site_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span><?= esc($t('dashboard')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('profile_user_staff/update/' . userLogin()->user_id); ?>"><i class="far fa-user"></i> <span><?= esc($t('my_profile')) ?></span></a></li>
                <li><a class="nav-link" href="<?= site_url('ubah_password_staff/update/' . userLogin()->user_id); ?>"><i class="fas fa-cog"></i> <span><?= esc($t('change_password')) ?></span></a></li>

                <li class="menu-header"><?= esc($t('header_community')) ?></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard-list"></i><span><?= esc($t('group_service')) ?></span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= site_url('rekapan'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-dark pl-1"><?= esc($t('status_all')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/proses'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-primary pl-1"><?= esc($t('status_processing')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/revisi'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-warning pl-1"><?= esc($t('status_revised')) ?></b></a></li>
                        <li><a class="nav-link" href="<?= site_url('rekapan/setuju'); ?>"><?= esc($t('recap_base_label')) ?> <b class="text-success pl-1"><?= esc($t('status_approved')) ?></b></a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li class="menu-header"><?= esc($t('header_account')) ?></li>
            <li>
                <a class="nav-link" href="<?= site_url('faq'); ?>">
                    <i class="fas fa-question-circle"></i>
                    <span><?= esc($t('faq')) ?></span>
                </a>
            </li>
            <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                <a href="<?= site_url('auth/logout'); ?>" id="logout" class="btn btn-lg btn-block btn-icon-split">
                    <i class="fas fa-sign-out-alt"></i> <?= esc($t('logout')) ?>
                </a>
            </div>

        </ul>
    </aside>
</div>

<?php

return [

    [
        'label' => 'Home',
        'route' => 'dashboard',
        'icon' => 'home',
        'roles' => ['admin', 'kepsek', 'pegawai', 'user', 'superadmin', 'waka'],
    ],
    [
        'label' => 'Absen Siswa',
        'route' => 'absensiswa',
        'icon' => 'arrow-down-on-square',
        'roles' => ['admin', 'kepsek', 'pegawai', 'waka'],
    ],
    [
        'label' => 'Registerasi Kartu',
        'route' => 'registerasi',
        'icon' => 'credit-card',
        'roles' => ['admin'],
    ],

   

    [
        'label' => 'Account',
        'icon' => 'user-circle',
        'roles' => ['admin', 'superadmin'],
        'children' => [
            [
                'label' => 'Admin',
                'route' => 'admin',
                'roles' => ['superadmin'],
            ],
            [
                'label' => 'Pegawai',
                'route' => 'pegawai',
                'roles' => ['admin'],
            ],
            [
                'label' => 'User',
                'route' => 'user',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Wali Kelas',
                'route' => 'walikelas',
                'roles' => ['admin'],
            ],

           
        ],
    ],
    [
        'label' => 'Data Siswa',
        'icon' => 'users',
        'roles' => ['admin'],
        'children' => [
            [
                'label' => 'Import',
                'route' => 'import',
                'icon' => 'arrow-up-on-square-stack',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Siswa',
                'route' => 'siswa',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Rombel',
                'route' => 'rombel',
                'roles' => ['admin'],
            ],

           
        ],
    ],
    [
        'label' => 'Pengaturan',
        'icon' => 'wrench-screwdriver',
        'roles' => ['admin', 'superadmin'],
        'children' => [
            [
                'label' => 'Perangkat',
                'route' => 'perangkat',
                'roles' => ['admin'],
            ],

            [
                'label' => 'Jam Operasional',
                'route' => 'jamoperasional',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Instansi',
                'route' => 'instansi',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Semester',
                'route' => 'semester',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Desain Kartu',
                'route' => 'desainkartu',
                'roles' => ['admin','superadmin'],
            ],
        ],
    ],
    [
        'label' => 'Cetak Kartu',
        'route' => 'cetakkartu',
        'icon' => 'credit-card',
        'roles' => ['superadmin'],
    ],
    
    [
        'label' => 'Cetak Laporan',
        'icon' => 'clipboard-document',
        'roles' => ['kepsek', 'admin','waka'],
        'children' => [
            [
                'label' => 'Absen Siswa',
                'route' => 'cetak.absensiswa',
                'roles' => ['kepsek', 'admin','waka'],
            ],
           
        ],
    ],

    [
        'label' => 'Download Aplikasi',
        'route' => 'pwa.download',
        'icon' => 'device-phone-mobile',
        'roles' => ['admin', 'pegawai', 'user', 'waka', 'kepsek'],
    ],

];
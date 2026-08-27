<?php

return [

    [
        'label' => 'Home',
        'route' => 'dashboard',
        'icon' => 'home',
        'roles' => ['admin', 'kepsek', 'pegawai', 'user', 'superadmin', 'waka', 'tu'],
    ],
    [
        'label' => 'Absen Siswa',
        'route' => 'absensiswa',
        'icon' => 'arrow-down-on-square',
        'roles' => ['admin', 'kepsek', 'pegawai', 'waka', 'tu'],
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
        'roles' => ['admin', 'superadmin', 'tu'],
        'children' => [
            [
                'label' => 'Admin',
                'route' => 'admin',
                'roles' => ['superadmin'],
            ],
            [
                'label' => 'Pegawai',
                'route' => 'pegawai',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'User',
                'route' => 'user',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Wali Kelas',
                'route' => 'walikelas',
                'roles' => ['admin', 'tu'],
            ],

           
        ],
    ],
    [
        'label' => 'Data Siswa',
        'icon' => 'users',
        'roles' => ['admin', 'tu'],
        'children' => [
            [
                'label' => 'Import',
                'route' => 'import',
                'icon' => 'arrow-up-on-square-stack',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Siswa',
                'route' => 'siswa',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Rombel',
                'route' => 'rombel',
                'roles' => ['admin', 'tu'],
            ],

           
        ],
    ],
    [
        'label' => 'Pengaturan',
        'icon' => 'wrench-screwdriver',
        'roles' => ['admin', 'superadmin', 'tu'],
        'children' => [
            [
                'label' => 'Perangkat',
                'route' => 'perangkat',
                'roles' => ['admin'],
            ],

            [
                'label' => 'Jam Operasional',
                'route' => 'jamoperasional',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Instansi',
                'route' => 'instansi',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Semester',
                'route' => 'semester',
                'roles' => ['admin', 'tu'],
            ],
            [
                'label' => 'Desain Kartu',
                'route' => 'desainkartu',
                'roles' => ['admin','superadmin', 'tu'],
            ],
        ],
    ],
    [
        'label' => 'Cetak Kartu',
        'route' => 'cetakkartu',
        'icon' => 'credit-card',
        'roles' => ['superadmin', 'admin'],
    ],
    
    [
        'label' => 'Cetak Laporan',
        'icon' => 'clipboard-document',
        'roles' => ['kepsek', 'admin','waka', 'tu'],
        'children' => [
            [
                'label' => 'Absen Siswa',
                'route' => 'cetak.absensiswa',
                'roles' => ['kepsek', 'admin','waka', 'tu'],
            ],
            [
                'label' => 'Data Siswa',
                'route' => 'cetak.laporansiswa',
                'roles' => ['kepsek', 'admin','waka', 'tu'],
            ],
            [
                'label' => 'Data Pegawai',
                'route' => 'cetak.laporanpegawai',
                'roles' => ['kepsek', 'admin','waka', 'tu'],
            ],
           
        ],
    ],

    [
        'label' => 'Download Aplikasi',
        'route' => 'pwa.download',
        'icon' => 'device-phone-mobile',
        'roles' => ['admin', 'pegawai', 'waka', 'kepsek', 'tu'],
    ],

];
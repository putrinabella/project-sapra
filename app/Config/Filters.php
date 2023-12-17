<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
// use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\LoginFilter;
use App\Filters\SuperAdminFilter;
use App\Filters\LaboranFilter;
use App\Filters\AdminItFilter;
use App\Filters\AdminSarpraFilter;

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
        // 'csrf'          => CSRF::class,
        'toolbar'               => DebugToolbar::class,
        'honeypot'              => Honeypot::class,
        'invalidchars'          => InvalidChars::class,
        'secureheaders'         => SecureHeaders::class,
        'isLoggedIn'            => LoginFilter::class,
        'superAdminFilter'      => SuperAdminFilter::class,
        'laboranFilter'         => LaboranFilter::class,
        'adminItFilter'         => AdminItFilter::class,
        'adminSarpraFilter'     => AdminSarpraFilter::class,
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
            // 'isLoggedIn',
            // 'superAdminFilter',
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
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
        'isLoggedIn' => ['before' => [
                '/',
                'home', 
                'home/*',                
                'profileUser/*',                
                'dataLabPeminjaman/*',                
                'pengajuanLabPeminjaman/*',                
                'peminjamanAset/*',                
                'pengajuanPeminjaman/*',                
                'formPengaduanUser/*',                
                'dataPengaduanUser/*',                
                'dataFeedbackUser/*',                
                'profileUser',                
                'dataLabPeminjaman',                
                'pengajuanLabPeminjaman',                
                'peminjamanAset',                
                'pengajuanPeminjaman',                
                'formPengaduanUser',                
                'dataPengaduanUser',                
                'dataFeedbackUser',                
            ]
        ],
        'laboranFilter' => ['before' => [
                'asetLabGeneral/*',  
                'rincianLabAset/*',  
                'pemusnahanLabAset/*',  
                'laboratorium/*',  
                'layananLabAset/*',  
                'layananLabNonAset/*',  
                'dataPeminjaman/*',  
                'requestPeminjaman/*',  
                'manajemenPeminjaman/*',  
            ]
        ],
        'adminItFilter' => ['before' => [
                'asetItGeneral/*',  
                'rincianItAset/*',  
                'pemusnahanItAset/*',  
                'layananAsetIt/*',  
                'website/*',  
                'sosialMedia/*',  
                'aplikasi/*',  
            ]
        ],
        'adminSarpraFilter' => ['before' => [
                'asetGeneral/*',  
                'rincianAset/*',  
                'pemusnahanAset/*',  
                'requestAsetPeminjaman/*',  
                'dataAsetPeminjaman/*',  
                'manajemenAsetPeminjaman/*',  
                'dataNonInventaris/*',  
                'arsipPengaduan/*',  
                'arsipFeedback/*',  
                'saranaLayananAset/*',  
                'saranaLayananNonAset/*',  
                'prasaranaRuangan/*',  
                'prasaranaNonRuangan/*',  
                'profilSekolah/*',  
                'tagihanAir/*',  
                'tagihanListrik/*',  
                'tagihanInternet/*',  
            ]
        ],
        'superAdminFilter' => ['before' => [
                'manajemenUser/*',  
                'identitasSarana/*',  
                'identitasPrasarana/*',  
                'identitasLab/*',  
                'identitasGedung/*',  
                'identitasLantai/*',  
                'statusLayanan/*',  
                'kategoriManajemen/*',  
                'kategoriMep/*',  
                'identitasKelas/*',  
                'dataSiswa/*',  
                'dataPegawai/*',  
                'nonInventaris/*',  
                'pertanyaanPengaduan/*',  
                'pertanyaanFeedback/*',  
                'restoreDatabase/*',  
                'backup/*',  
                'restore/*',  
                'asetGeneral/*',  
            ]
        ],
    ];
}

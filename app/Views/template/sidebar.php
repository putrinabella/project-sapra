<ul class="nav">
    <li class="nav-item nav-category">Main</li>
    <li class="nav-item">
        <a href="<?= site_url() ?>" class="nav-link">
            <i class="link-icon" data-feather="box"></i>
            <span class="link-title">Dashboard</span>
        </a>
    </li>
    <li class="nav-item nav-category">Sarana dan Prasarana</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#sarana" role="button" aria-expanded="false" aria-controls="sarana">
            <i class="link-icon" data-feather="monitor"></i>
            <span class="link-title">Sarana</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="sarana">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('layananAsetSarana') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('layananNonAsetSarana') ?>" class="nav-link">Layanan Non Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('rincianAset') ?>" class="nav-link">Rincian Aset</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#prasarana" role="button" aria-expanded="false" aria-controls="prasarana">
            <i class="link-icon" data-feather="check-circle"></i>
            <span class="link-title">Prasarana</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="prasarana">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('kantin') ?>" class="nav-link">Kantin</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('lapangan') ?>" class="nav-link">Lapangan</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('parkiran') ?>" class="nav-link">Parkiran</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('dataPrasarana') ?>" class="nav-link">Ruangan</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('toilet') ?>" class="nav-link">Toilet</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item nav-category">Laboratorium</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#masterLaboratorium" role="button" aria-expanded="false" aria-controls="masterLaboratorium">
            <i class="link-icon" data-feather="shield"></i>
            <span class="link-title">Master</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="masterLaboratorium">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('manajemenPeminjamanLab') ?>" class="nav-link">Manajemen Peminjaman</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('layananAsetLab') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('layananNonAsetLab') ?>" class="nav-link">Layanan Non Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url() ?>" class="nav-link">Rincian Aset </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('manajemenLab') ?>" class="nav-link">
            <i class="link-icon" data-feather="message-square"></i>
            <span class="link-title">Laboratorium</span>
        </a>
    </li>
    <li class="nav-item nav-category">IT</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#masterIT" role="button" aria-expanded="false" aria-controls="masterIT">
            <i class="link-icon" data-feather="shield"></i>
            <span class="link-title">Master</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="masterIT">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('layananAsetIt') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url() ?>" class="nav-link">Rincian Aset </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('perangkatIt') ?>" class="nav-link">
            <i class="link-icon" data-feather="wifi"></i>
            <span class="link-title">Perangkat IT</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('websiteSosmed') ?>" class="nav-link">
            <i class="link-icon" data-feather="globe"></i>
            <span class="link-title">Website dan Media Sosial</span>
        </a>
    </li>
    <li class="nav-item nav-category">Settings</li>
    <li class="nav-item">
        <a href="<?= site_url('manajemenUser') ?>" class="nav-link">
            <i class="link-icon" data-feather="user"></i>
            <span class="link-title">Manajemen User</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('profilSekolah') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Profil Sekolah</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#identitas" role="button" aria-expanded="false" aria-controls="identitas">
            <i class="link-icon" data-feather="database"></i>
            <span class="link-title">Data Master</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="identitas">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('identitasGedung') ?>" class="nav-link">Identitas Gedung</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('identitasLantai') ?>" class="nav-link">Identitas Lantai</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('identitasPrasarana') ?>" class="nav-link">Identitas Prasarana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('identitasSarana') ?>" class="nav-link">Identitas Sarana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('sumberDana') ?>" class="nav-link">Sumber Dana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('statusLayanan') ?>" class="nav-link">Status Layanan</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('kategoriManajemen') ?>" class="nav-link">Kategori Manajemen </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a href="https://www.nobleui.com/html/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="hash"></i>
            <span class="link-title">Documentation</span>
        </a>
    </li>
</ul>
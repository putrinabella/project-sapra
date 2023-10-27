<ul class="nav">
    <!-- <li class="nav-item nav-category">Main</li>
    <li class="nav-item">
        <a href="<?= site_url('home') ?>" class="nav-link">
            <i class="link-icon" data-feather="box"></i>
            <span class="link-title">Dashboard</span>
        </a>
    </li> -->
    <?php if (session()->get('role') == 'Super Admin') { ?>
    <li class="nav-item nav-category">Sarana</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#layananSaranaUmum" role="button" aria-expanded="false" aria-controls="layananSaranaUmum">
            <i class="link-icon" data-feather="server"></i>
            <span class="link-title">Layanan</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="layananSaranaUmum">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('saranaLayananAset') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('saranaLayananNonAset') ?>" class="nav-link">Layanan Non Aset</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#dataAsetUmum" role="button" aria-expanded="false" aria-controls="dataAsetUmum">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Data Aset</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="dataAsetUmum">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('dataSarana') ?>" class="nav-link">Data General</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('rincianAset') ?>" class="nav-link">Data Rincian</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#pemusnahanAsetUmum" role="button" aria-expanded="false" aria-controls="pemusnahanAsetUmum">
            <i class="link-icon" data-feather="trash"></i>
            <span class="link-title">Pemusnahan Aset</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="pemusnahanAsetUmum">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('pemusnahanAset') ?>" class="nav-link">Pemusnahan Aset</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item nav-category">Prasarana</li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="list"></i>
            <span class="link-title">Ruangan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaNonRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="list"></i>
            <span class="link-title">Non Ruangan</span>
        </a>
    </li>
    <!-- <li class="nav-item nav-category">Prasarana</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#prasarana" role="button" aria-expanded="false" aria-controls="prasarana">
            <i class="link-icon" data-feather="check-circle"></i>
            <span class="link-title">Prasarana</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="prasarana">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('prasaranaRuangan') ?>" class="nav-link">Ruangan</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('prasaranaNonRuangan') ?>" class="nav-link">Non Ruangan</a>
                </li>
            </ul>
        </div>
    </li> -->

    <li class="nav-item nav-category">Laboratorium</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#manajemenLayanan" role="button" aria-expanded="false" aria-controls="manajemenLayanan">
            <i class="link-icon" data-feather="server"></i>
            <span class="link-title">Layanan</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="manajemenLayanan">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('layananLabAset') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('layananLabNonAset') ?>" class="nav-link">Layanan Non Aset</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#manajemenPeminjaman" role="button" aria-expanded="false" aria-controls="manajemenPeminjaman">
            <i class="link-icon" data-feather="user-check"></i>
            <span class="link-title">Peminjaman</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="manajemenPeminjaman">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('dataPeminjaman') ?>" class="nav-link">Data Peminjaman </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('manajemenPeminjaman') ?>" class="nav-link">Manajemen Peminjaman</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('rincianLabAset') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Manajemen Aset</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('laboratorium') ?>" class="nav-link">
            <i class="link-icon" data-feather="message-square"></i>
            <span class="link-title">Laboratorium</span>
        </a>
    </li>

    <li class="nav-item nav-category">IT</li>
    <li class="nav-item">
        <a href="<?= site_url('perangkatIt') ?>" class="nav-link">
            <i class="link-icon" data-feather="wifi"></i>
            <span class="link-title">Perangkat IT</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('layananAsetIt') ?>" class="nav-link">
            <i class="link-icon" data-feather="list"></i>
            <span class="link-title">Layanan Perangkat IT</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('website') ?>" class="nav-link">
            <i class="link-icon" data-feather="link"></i>
            <span class="link-title">Website</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('sosialMedia') ?>" class="nav-link">
            <i class="link-icon" data-feather="globe"></i>
            <span class="link-title">Sosial Media</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('aplikasi') ?>" class="nav-link">
            <i class="link-icon" data-feather="smartphone"></i>
            <span class="link-title">Aplikasi</span>
        </a>
    </li>

    <li class="nav-item nav-category">Profil</li>
    <li class="nav-item">
        <a href="<?= site_url('profilSekolah') ?>" class="nav-link">
            <i class="link-icon" data-feather="home"></i>
            <span class="link-title">Profil Sekolah</span>
        </a>
    </li>

    <li class="nav-item nav-category">Master</li>
    <li class="nav-item">
        <a href="<?= site_url('viewLogs') ?>" class="nav-link">
            <i class="link-icon" data-feather="activity"></i>
            <span class="link-title">User Log</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('manajemenUser') ?>" class="nav-link">
            <i class="link-icon" data-feather="user"></i>
            <span class="link-title">Manajemen User</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#database" role="button" aria-expanded="false" aria-controls="database">
            <i class="link-icon" data-feather="database"></i>
            <span class="link-title">Database</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="database">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('backup') ?>" class="nav-link">Backup</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('restore') ?>" class="nav-link">Restore</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#identitas" role="button" aria-expanded="false" aria-controls="identitas">
            <i class="link-icon" data-feather="list"></i>
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
                    <a href="<?= site_url('identitasLab') ?>" class="nav-link">Identitas Laboratorium</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('identitasSarana') ?>" class="nav-link">Identitas Sarana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('identitasKelas') ?>" class="nav-link">Identitas Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('sumberDana') ?>" class="nav-link">Sumber Dana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('statusLayanan') ?>" class="nav-link">Status Layanan</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('kategoriManajemen') ?>" class="nav-link">Kategori Barang </a>
                </li>
            </ul>
        </div>
    </li>
<?php } ?>

<?php if (session()->get('role') == 'Laboran') { ?>
    <li class="nav-item nav-category">Laboratorium</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#manajemenLayanan" role="button" aria-expanded="false" aria-controls="manajemenLayanan">
            <i class="link-icon" data-feather="server"></i>
            <span class="link-title">Layanan</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="manajemenLayanan">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('layananLabAset') ?>" class="nav-link">Layanan Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('layananLabNonAset') ?>" class="nav-link">Layanan Non Aset</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#manajemenPeminjaman" role="button" aria-expanded="false" aria-controls="manajemenPeminjaman">
            <i class="link-icon" data-feather="user-check"></i>
            <span class="link-title">Peminjaman</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="manajemenPeminjaman">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('dataPeminjaman') ?>" class="nav-link">Data Peminjaman </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('manajemenPeminjaman') ?>" class="nav-link">Manajemen Peminjaman</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('rincianLabAset') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Manajemen Aset</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('laboratorium') ?>" class="nav-link">
            <i class="link-icon" data-feather="message-square"></i>
            <span class="link-title">Laboratorium</span>
        </a>
    </li>
<?php } ?>

</ul>
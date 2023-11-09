<ul class="nav">
    <!-- <li class="nav-item nav-category">Main</li> -->
    <li class="nav-item">
        <a href="<?= site_url('home') ?>" class="nav-link">
            <i class="link-icon" data-feather="box"></i>
            <span class="link-title">Dashboard</span>
        </a>
    </li>
    <?php if (session()->get('role') == 'Super Admin') { ?>
    <li class="nav-item nav-category">Sarana</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#dataAsetUmum" role="button" aria-expanded="false" aria-controls="dataAsetUmum">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Manajemen Aset</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="dataAsetUmum">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('dataSarana') ?>" class="nav-link">Data General</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('rincianAset') ?>" class="nav-link">Data Rincian Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('pemusnahanAset') ?>" class="nav-link">Pemusnahan Aset</a>
                </li>
            </ul>
        </div>
    </li>
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
    <li class="nav-item nav-category">Prasarana</li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Ruangan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaNonRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Non Ruangan</span>
        </a>
    </li>

    <li class="nav-item nav-category">Laboratorium</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#rincianLabAset" role="button" aria-expanded="false" aria-controls="rincianLabAset">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Manajemen Aset</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="rincianLabAset">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('laboratorium') ?>" class="nav-link">Laboratorium</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('rincianLabAset') ?>" class="nav-link">Data Rincian Aset</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('pemusnahanLabAset') ?>" class="nav-link">Pemusnahan Aset</a>
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
        <a class="nav-link" data-bs-toggle="collapse" href="#aplikasiIT" role="button" aria-expanded="false" aria-controls="aplikasiIT">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Platform Digital </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="aplikasiIT">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('aplikasi') ?>" class="nav-link">Aplikasi</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('sosialMedia') ?>" class="nav-link">Sosial Media</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('website') ?>" class="nav-link">Website</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item nav-category">Sekolah</li>
    <li class="nav-item">
        <a href="<?= site_url('profilSekolah') ?>" class="nav-link">
            <i class="link-icon" data-feather="home"></i>
            <span class="link-title">Profil Sekolah</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#tagihan" role="button" aria-expanded="false" aria-controls="tagihan">
            <i class="link-icon" data-feather="file-text"></i>
            <span class="link-title">Tagihan </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="tagihan">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('tagihanAir') ?>" class="nav-link">Tagihan Air</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('tagihanListrik') ?>" class="nav-link">Tagihan Listrik</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('tagihanInternet') ?>" class="nav-link">Tagihan Internet</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item nav-category">Master</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#users" role="button" aria-expanded="false" aria-controls="users">
            <i class="link-icon" data-feather="users"></i>
            <span class="link-title">Manajemen User</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="users">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('manajemenUser') ?>"  class="nav-link">Data User</a>
                </li>
                <li class="nav-item">
                <a href="<?= site_url('viewLogs') ?>" class="nav-link">User Logs</a>
                </li>
            </ul>
        </div>
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
                    <a href="<?= site_url('kategoriManajemen') ?>" class="nav-link">Kategori Barang </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('kategoriMep') ?>" class="nav-link">Kategori MEP </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('kategoriPegawai') ?>" class="nav-link">Kategori Pegawai</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('sumberDana') ?>" class="nav-link">Sumber Dana</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('statusLayanan') ?>" class="nav-link">Status Layanan</a>
                </li>
            </ul>
        </div>
    </li>
<?php } ?>

<?php if (session()->get('role') == 'Laboran') { ?>
    <li class="nav-item nav-category">Laboratorium</li>
    <li class="nav-item">
        <a href="<?= site_url('laboratorium') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Laboratorium</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('rincianLabAset') ?>" class="nav-link">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Manajemen Aset</span>
        </a>
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
<?php } ?>

<?php if (session()->get('role') == 'Admin Sarpra') { ?>
    <li class="nav-item nav-category">Sarana</li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#dataAsetUmum" role="button" aria-expanded="false" aria-controls="dataAsetUmum">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Manajemen Aset</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="dataAsetUmum">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('dataSarana') ?>" class="nav-link">Data General</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('rincianAset') ?>" class="nav-link">Data Rincian Aset</a>
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
    <li class="nav-item nav-category">Prasarana</li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Ruangan</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('prasaranaNonRuangan') ?>" class="nav-link">
            <i class="link-icon" data-feather="bookmark"></i>
            <span class="link-title">Non Ruangan</span>
        </a>
    </li>
<?php } ?>

<?php if (session()->get('role') == 'Admin IT') { ?>
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
        <a class="nav-link" data-bs-toggle="collapse" href="#aplikasiIT" role="button" aria-expanded="false" aria-controls="aplikasiIT">
            <i class="link-icon" data-feather="folder"></i>
            <span class="link-title">Platform Digital </span>
            <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse" id="aplikasiIT">
            <ul class="nav sub-menu">
                <li class="nav-item">
                    <a href="<?= site_url('aplikasi') ?>" class="nav-link">Aplikasi</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('sosialMedia') ?>" class="nav-link">Sosial Media</a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('website') ?>" class="nav-link">Website</a>
                </li>
            </ul>
        </div>
    </li>

<?php } ?>

</ul>
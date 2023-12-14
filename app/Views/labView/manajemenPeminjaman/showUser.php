<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>
    <?= $dataLaboratorium->namaLab; ?> &verbar; SARPRA
</title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('manajemenPeminjaman')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('manajemenPeminjaman/print/'.$dataLaboratorium->idIdentitasLab)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="p-2">Nama Lab</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataLaboratorium->namaLab; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="p-2">Kode Lab</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataLaboratorium->kodeLab; ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <h5 class="p-2">Lokasi Gedung</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataInfoLab->namaGedung;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Lokasi Lantai</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataInfoLab->namaLantai;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Luas</h5>
                        <div class="border rounded-2 p-2">
                            <?= $luasFormatted = number_format($dataLaboratorium->luas, 0, ',', '.'); ?> m&sup2
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>Rincian Aset </h5>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">No.</th>
                                    <th>Nama Aset</th>
                                    <th>Jumlah Aset</th>
                                    <th>Aset Hilang atau Rusak</th>
                                    <th>Aset Dipinjam</th>
                                    <th>Aset Tersedia</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                                <?php foreach ($dataSarana as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td class="text-center">
                                        <?=$key + 1?>
                                    </td>
                                    <td class="text-center" hidden><?=$value->idRincianLabAset?></td>
                                    <td class="text-center"><?=$value->namaSarana?></td>
                                    <td class="text-center"><?=$value->totalSarana?></td>
                                    <td class="text-center">
                                        <?php echo $value->saranaRusak + $value->saranaHilang; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= ($value->saranaDipinjam === null) ? 0 : $value->saranaDipinjam ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            if ($value->jumlahPeminjaman == 0) {
                                                $value->asetTersedia = $value->totalSarana - ($value->saranaRusak + $value->saranaHilang);
                                                echo $value->asetTersedia;
                                            } else {
                                                $value->asetTersedia = $value->totalSarana - ($value->saranaRusak + $value->saranaHilang) - $value->jumlahPeminjaman;
                                                echo $value->asetTersedia;
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($value->asetTersedia > 0) : ?>
                                        <a href="<?= site_url('manajemenPeminjaman/new') ?>" class="btn btn-outline-primary me-2">Ajukan</a>
                                        <a href="#" class="btn btn-outline-primary upload-excel-btn"
                                            data-bs-toggle="modal" data-bs-target="#modalImport"
                                            data-id-identitas-sarana="<?= $value->idIdentitasSarana ?>"
                                            data-kode-lab="<?= $dataLaboratorium->idIdentitasLab ?>"
                                            data-id-aset-tersedia="<?= $value->asetTersedia ?>"
                                            data-id-rincian-lab-aset="<?= $value->idRincianLabAset ?>">Ajukan Peminjaman</a>
                                        <?php else : ?>
                                        <button class="btn btn-outline-primary" disabled>Ajukan Peminjaman</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Data Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url('manajemenPeminjaman/addLoan') ?>" method="POST" enctype="multipart/form-data"
                id="custom-validation">
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <input type="text" class="form-control" id="idIdentitasLab" name="idIdentitasLab" hidden>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="idIdentitasSarana" name="idIdentitasSarana" hidden>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="status" name="status" value="Peminjaman" hidden>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="idRincianLabAset" name="idRincianLabAset" hidden>
                    </div>

                    <div class="mb-3">
                        <label for="asetTersedia" class="form-label">Aset Tersedia</label>
                        <input type="text" class="form-control" id="asetTersedia" name="asetTersedia" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <div class="input-group date datepicker" id="tanggal">
                            <input type="text" class="form-control bg-transparent" name="tanggal" placeholder="Masukkan tanggal" readonly>
                            <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="namaPeminjam" class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam" placeholder="Masukkan nama">
                    </div>
                    <div class="mb-3">
                        <label for="jenisPeminjam" class="form-label">Kelas/Karyawan</label>
                        <select class="form-control myselect2" id="jenisPeminjam" name="jenisPeminjam"
                            onchange="showHideOptions()">
                            <option value="" readonly>Select here</option>
                            <option value="karyawan">Karyawan</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <div class="mb-3" id="karyawanOptions" style="display:none;">
                        <label for="karyawanRole" class="form-label">Role</label>
                        <select class="form-control myselect2" id="karyawanRole" name="asalPeminjam">
                            <option value="guru">Guru</option>
                            <option value="Karyawan">Karyawan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3" id="siswaOptions" style="display:none;">
                        <label for="siswaClass" class="form-label">Kelas</label>
                        <select class="form-control myselect2" id="siswaClass" name="asalPeminjam">
                            <?php foreach($dataIdentitasKelas as $key =>$value): ?>
                            <option value="<?=$value->namaKelas?>"><?=$value->namaKelas?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah">
                        <div id="error-message" style="color: red;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var uploadExcelButtons = document.querySelectorAll('.upload-excel-btn');
        uploadExcelButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                var idIdentitasSarana = button.getAttribute('data-id-identitas-sarana');
                var idIdentitasLab = button.getAttribute('data-kode-lab');
                var asetTersedia = button.getAttribute('data-id-aset-tersedia');
                var idRincianLabAset = button.getAttribute('data-id-rincian-lab-aset');

                document.getElementById('idIdentitasSarana').value = idIdentitasSarana;
                document.getElementById('idIdentitasLab').value = idIdentitasLab;
                document.getElementById('asetTersedia').value = asetTersedia;
                document.getElementById('idRincianLabAset').value = idRincianLabAset;
            });
        });
    });

    document.getElementById('jumlah').addEventListener('input', function () {
        var jumlahValue = parseInt(this.value, 10);
        var asetTersediaValue = parseInt(document.getElementById('asetTersedia').value, 10);
        document.getElementById('error-message').textContent = jumlahValue > asetTersediaValue ? 'Jumlah tidak valid. Jumlah tidak boleh lebih besar dari aset yang tersedia.' : '';
    });

    document.getElementById('custom-validation').addEventListener('submit', function (event) {
        if (document.getElementById('error-message').textContent !== '') {
            event.preventDefault();
        }
    });

    function showHideOptions() {
        var jenisPeminjam = document.getElementById("jenisPeminjam");
        var karyawanOptions = document.getElementById("karyawanOptions");
        var siswaOptions = document.getElementById("siswaOptions");

        karyawanOptions.style.display = "none";
        siswaOptions.style.display = "none";

        if (jenisPeminjam.value === "karyawan") {
            karyawanOptions.style.display = "block";
        } else if (jenisPeminjam.value === "siswa") {
            siswaOptions.style.display = "block";
        }
    }
</script>

<?= $this->endSection(); ?>
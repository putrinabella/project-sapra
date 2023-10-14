<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Peminjaman</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dataPeminjaman/'.$dataDataPeminjaman->idManajemenPeminjaman)?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam"
                                value="<?=$dataDataPeminjaman->namaPeminjam?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Asal Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="asalPeminjam" name="asalPeminjam"
                                value="<?=$dataDataPeminjaman->asalPeminjam?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSarana" name="namaSarana"
                                value="<?=$dataDataPeminjaman->namaSarana?>" readonly>
                            <input type="text" class="form-control" id="idIdentitasSarana" name="idIdentitasSarana"
                                value="<?=$dataDataPeminjaman->idIdentitasSarana?>" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kodeLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaLab" name="namaLab"
                                value="<?=$dataDataPeminjaman->namaLab?>" readonly>
                            <input type="text" class="form-control" id="kodeLab" name="kodeLab"
                                value="<?=$dataDataPeminjaman->kodeLab?>" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="status" name="status">
                                <option value="" hidden>Pilih status</option>
                                <option value="Peminjaman" <?=$dataDataPeminjaman->status == 'Peminjaman' ? 'selected' :
                                    '' ?>>Peminjaman</option>
                                <option value="Pengembalian" <?=$dataDataPeminjaman->status == 'Pengembalian' ?
                                    'selected' : '' ?>>Pengembalian</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label" id="labelPengembalianContainer"
                            style="display: none;">Tanggal</label>
                        <div class="col-sm-9" id="tanggalPengembalianContainer" style="display: none;">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control" id="tanggalPengembalian"
                                    name="tanggalPengembalian"   value="<?=$dataDataPeminjaman->tanggalPengembalian?>">
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Undo</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTanggalPengembalian() {
        var statusSelect = document.getElementById('status');
        var status = statusSelect.value;
        var labelPengembalianContainer = document.getElementById('labelPengembalianContainer');
        var tanggalPengembalianContainer = document.getElementById('tanggalPengembalianContainer');
        var tanggalPengembalianInput = document.getElementById('tanggalPengembalian');

        if (status === 'Pengembalian') {
            labelPengembalianContainer.style.display = 'block';
            tanggalPengembalianContainer.style.display = 'block';
        } else {
            labelPengembalianContainer.style.display = 'none';
            tanggalPengembalianContainer.style.display = 'none';
            
            tanggalPengembalianInput.value = '';
        }
    }

    document.getElementById('status').addEventListener('change', toggleTanggalPengembalian);

    window.addEventListener('load', toggleTanggalPengembalian);
</script>



<?= $this->endSection(); ?>
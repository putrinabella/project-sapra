<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Identitas Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Sarana</h4>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('identitasSarana')?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="kodeSarana" class="col-sm-3 col-form-label">Kode</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeSarana" name="kodeSarana" placeholder="Masukkan kode sarana">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaSarana" class="col-sm-3 col-form-label">Identitas Sarana</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSarana" name="namaSarana" placeholder="Masukkan identitas sarana">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="perangkatIT" class="col-sm-3 col-form-label">Perangkat IT</label>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perangkatIT" name="perangkatIT" onchange="updateLabel()">
                                <label class="form-check-label" for="perangkatIT" id="perangkatITLabel"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasSarana') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateLabel();
    });

    function updateLabel() {
        var checkbox = document.getElementById('perangkatIT');
        var label = document.getElementById('perangkatITLabel');

        if (checkbox.checked) {
            label.innerHTML = 'Sarana merupakan perangkat IT';
        } else {
            label.innerHTML = 'Sarana <span style="color: red;">bukan</span> perangkat IT';
        }
    }
</script>



<?= $this->endSection(); ?>
<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Identitas Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('identitasSarana')?>">Identitas Sarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('identitasSarana')?>" method="post" autocomplete="off"  id="custom-validation">
                    
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
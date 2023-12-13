<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Ruangan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ruangan Laboratorium</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Laboratorium</h4>
    </div>
    <div>
        <form id="searchForm">
            <div class="input-group">
                <button type="button" class="btn border-primary bg-primary text-white" id="searchBtn" >Search</button>
                <input type="text" class="form-control border-primary bg-transparent" id="searchInput"
                    placeholder="Type here">
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1" id="searchResultsContainer">
            <?php foreach ($dataLaboratorium as $key => $value) : ?>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <?php if ($value->picturePath !== null) : ?>
                    <img src="<?= base_url($value->picturePath) ?>" class="card-img-top" alt="Foto ruangan"
                        style="max-height: 200px;">
                    <?php else : ?>
                    <img src="<?= base_url(); ?>/assets/images/Ruangan.jpeg" class="card-img-top"
                        alt="Default Foto ruangan" style="max-height: 200px;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?= $value->namaLab ?> (
                            <?= $value->kodeLab; ?> )
                        </h5>
                        <p class="card-text mb-3">
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaGedung; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaLantai; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                Luas:
                                <?= $value->luas; ?> m&sup2
                            </span>
                        </p>
                        <a href="<?=site_url('laboratorium/'.$value->idIdentitasLab) ?>"
                            class="btn btn-primary">Tampilkan
                            Aset</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#searchInput').on('input', function () {
        var namaLab = $(this).val();

        $.ajax({
            type: 'POST',
            url: '<?= site_url('laboratorium/search') ?>',
            data: { namaLab: namaLab },
            dataType: 'json',
            success: function (result) {
                displaySearchResults(result);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    function displaySearchResults(results) {
        var container = $('#searchResultsContainer');
        container.empty();

        if (results.length > 0) {
            $.each(results, function (index, value) {
                var card = '<div class="col-md-4 grid-margin stretch-card">' +
                    '<div class="card">';

                if (value.picturePath !== null) {
                    card += '<img src="<?= base_url() ?>' + value.picturePath + '" class="card-img-top" alt="Foto ruangan" style="max-height: 200px;">';
                } else {
                    card += '<img src="<?= base_url(); ?>/assets/images/Ruangan.jpeg" class="card-img-top" alt="Default Foto ruangan" style="max-height: 200px;">';
                }

                card += '<div class="card-body">' +
                    '<h5 class="card-title text-center">' +
                    value.namaLab + ' (' + value.kodeLab + ')</h5>' +
                    '<p class="card-text mb-3">' +
                    '<span class="badge rounded-pill border border-primary text-primary">' +
                    value.namaGedung + '</span>' +
                    '<span class="badge rounded-pill border border-primary text-primary">' +
                    value.namaLantai + '</span>' +
                    '<span class="badge rounded-pill border border-primary text-primary">' +
                    'Luas: ' + value.luas + ' m&sup2</span>' +
                    '</p>' +
                    '<a href="<?=site_url('laboratorium/') ?>' + value.idIdentitasLab + '" class="btn btn-primary">Tampilkan Aset</a>' +
                    '</div></div></div>';

                container.append(card);
            });
        } else {
            container.html('<p>No results found</p>');
        }
    }
});
</script>


<?= $this->endSection(); ?>
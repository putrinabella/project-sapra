<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <!-- <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalImport">
                    Filter
                </button> -->
                <div class="row mb-2">
                    <div class="col-3">
                        <input type="text" class="form-control" name="searchInput" id="searchInput" placeholder="Type keyword">
                    </div>
                    <div class="col-3">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                        data-width="100%" tabindex="-1" aria-hidden="true" id="sortingData"
                        name="sortingData">
                            <option value="" selected disabled hidden>Pilih Lokasi</option>
                            <?php foreach ($dataPrasaranaLab as $key => $value): ?>
                            <option value="<?= $value->idIdentitasLab ?>">
                                <?= $value->namaLab ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="myTable">
                        <thead>
                            <tr class="text-center">
                                <th></th>
                                <th style="width: 12%;">Kode Aset</th>
                                <th>Lokasi</th>
                                <th>Kategori Aset</th>
                                <th>Nama Aset</th>
                                <th>Status</th>
                                <th>Section</th>
                                <th>Merek</th>
                                <th>Warna</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                <div class="example">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item col-6">
                            <a class="nav-link active text-center" id="identitasPeminjam-tab" data-bs-toggle="tab"
                                href="#identitasPeminjam" role="tab" aria-controls="identitasPeminjam"
                                aria-selected="true">Identitas Peminjam</a>
                        </li>
                        <li class="nav-item col-6">
                            <a class="nav-link text-center" id="dataPinjaman-tab" data-bs-toggle="tab"
                                href="#dataPinjaman" role="tab" aria-controls="dataPinjaman"
                                aria-selected="false">Aset</a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="identitasPeminjam" role="tabpanel"
                            aria-labelledby="identitasPeminjam-tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                                        <div class="col-sm-9">
                                            <div class="input-group date datepicker m-0" id="tanggal">
                                                <input type="text" class="form-control" name="tanggal"
                                                    placeholder="Masukkan tanggal" readonly>
                                                <span class="input-group-text input-group-addon"><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama
                                            Peminjam</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="namaPeminjam"
                                                name="namaPeminjam" placeholder="Masukkan nama">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="kategoriPeminjam"
                                            class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                                        <div class="col-sm-9">
                                            <select
                                                class="js-example-basic-single form-select select2-hidden-accessible"
                                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                                id="kategoriPeminjam" name="kategoriPeminjam">
                                                <option value="" selected disabled hidden>Pilih Kategori</option>
                                                <option value="karyawan">Karyawan</option>
                                                <option value="siswa">Siswa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Role</label>
                                        <div class="col-sm-9">
                                            <select
                                                class="js-example-basic-single form-select select2-hidden-accessible"
                                                data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true"
                                                id="asalPeminjam" name="asalPeminjam">
                                                <option value="" selected disabled hidden>Pilih Role</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- ROW 2 -->
                                <div class="col-6">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dataPinjaman" role="tabpanel" aria-labelledby="dataPinjaman-tab">
                            <div class="row mb-3">
                                <label for="idIdentitasLab" class="col-sm-1 col-form-label">Lokasi</label>
                                <div class="col-sm-3">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" tabindex="-1" aria-hidden="true" id="idIdentitasLab"
                                        name="idIdentitasLab">
                                        <option value="" selected disabled hidden>Pilih Lokasi</option>
                                        <?php foreach ($dataPrasaranaLab as $key => $value): ?>
                                        <option value="<?= $value->idIdentitasLab ?>">
                                            <?= $value->namaLab ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="idIdentitasSarana" class="col-sm-1 col-form-label">Aset</label>
                                <div class="col-sm-3">
                                    <select 
                                    class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" tabindex="-1" aria-hidden="true" id="idIdentitasSarana"
                                        name="idIdentitasSarana">
                                        <option value="" selected disabled hidden>Pilih Aset</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeRincianLabAset" class="col-sm-1 col-form-label">Kode
                                    Aset</label>
                                <div class="col-sm-3">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" data-select2-id="6" tabindex="-1" aria-hidden="true"
                                        id="kodeRincianLabAset" name="kodeRincianLabAset">
                                        <option value="" selected disabled hidden>Pilih Kode Aset</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Filter Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url("manajemenPeminjaman/getPeminjamanTabel")?>" method="POST" enctype="multipart/form-data"
                id="custom-validation">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filterJenis" class="form-label">Filter</label>
                        
                        <select class="form-control myselect2" id="filterJenis" name="filterJenis">
                            <option value="" selected disabled hidden>Pilih Filter</option>
                            <option value="lokasi">Lokasi</option>
                            <option value="sarana">Sarana</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filterBerdasarkan" class="form-label">Berdasarkan</label>
                        <select class="form-control myselect2" id="filterBerdasarkan" name="filterBerdasarkan">
                            <option value="" selected disabled hidden>Pilih Opsi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/DataTables/datatables.js"></script>

<script>
    // var table = $('#myTable').DataTable({
    //     "searching": false,
    //     "processing": true,
    //     "serverSide": true,
    //     "ajax": {
    //         "url": "<?= site_url('manajemenPeminjaman/getPeminjamanTabel') ?>",
    //         "type": "POST",
    //         "data": function (d) {
    //             var additionalData = {
    //                 searchKeywords: $('#searchInput').val().toLowerCase(),
    //                 filterOption: $('#sortingData').val().toLowerCase()
    //             };
    //             return $.extend(d, additionalData);
    //         }
    //     }
    // });
    var table = $('#myTable').DataTable({
    "searching": false,
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "<?= site_url('manajemenPeminjaman/getPeminjamanTabel') ?>",
        "type": "POST",
        "data": function (d) {
                    return $.extend({}, d, {
                        searchKeywords: $('#searchInput').val().toLowease(),
                        filterOption: $('#sortingData').val().toLowerCase()
                    });
                },
        "dataSrc": function (json) {
            return json.data;
        }
    },
    "columns": [
        { "data": "idRincianLabAset", "visible": false }, // Hide the ID column
        { "data": "kodeRincianLabAset" },
        { "data": "namaLab" },
        { "data": "namaKategoriManajemen" },
        { "data": "namaSarana" },
        { "data": "status" },
        { "data": "sectionAset" },
        { "data": "merk" },
        { "data": "warna" }
    ]
});


    $(document).ready(function () {
        // table.draw();

        $('#searchInput, #sortingData').on("keyup change", function () {
        table.draw();
    });
        // table = $('#myTable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     lengthChange: true,
        //     scrollX: true,
        //     searching: false,
        //     ajax: {
        //         url: "<?= site_url('manajemenPeminjaman/getPeminjamanTabel') ?>",
        //         type: "POST",
                // data: function (d) {
                //     return $.extend({}, d, {
                //         searchKeywords: $('#searchInput').val().toLowerCase(),
                //         filterOption: $('#sortingData').val().toLowerCase()
                //     });
                // }
        //     },
        //     columns: [
        //         {
        //             data: null,
        //             orderable: false,
        //             searchable: false,
        //             render: function (data, type, row, meta) {
        //                 return '<input type="checkbox" class="form-check-input row-select select-checkbox" value="' + row.idRincianLabAset + '">';
        //             }
        //         },
        //         { data: 'kodeRincianLabAset', name: 'kodeRincianLabAset' },
        //         { data: 'namaLab', name: 'namaLab' },
        //         { data: 'namaKategoriManajemen', name: 'namaKategoriManajemen' },
        //         { data: 'namaSarana', name: 'namaSarana' },
        //         { data: 'status', name: 'status' },
        //         { data: 'sectionAset', name: 'sectionAset' },
        //         { data: 'merk', name: 'merk' },
        //         { data: 'warna', name: 'warna' },
        //     ],
        //     createdRow: function (row, data, dataIndex) {
        //         $(row).addClass('custom-row-class');
        //     }
        // });

        $("#filterJenis").on("change", function () {
            var selectedFilterJenis = $(this).val();
            var $filterBerdasarkanSelect = $("#filterBerdasarkan");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getFilterOptions') ?>",
                type: "POST",
                data: {
                    filterJenis: selectedFilterJenis,
                },
                dataType: "json",
                success: function (response) {
                    $filterBerdasarkanSelect.empty();
                    if (response.length === 0) {
                        $filterBerdasarkanSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $filterBerdasarkanSelect.append("<option value='' selected disabled hidden>Pilih Opsi</option>");
                        $.each(response, function (key, value) {
                            if (selectedFilterJenis === 'lokasi') {
                                $filterBerdasarkanSelect.append("<option value='" + value.idIdentitasLab  + "'>" + value.namaLab + "</option>");
                            } else if (selectedFilterJenis === 'sarana') {
                                $filterBerdasarkanSelect.append("<option value='" + value.idIdentitasSarana + "'>" + value.namaSarana + "</option>");
                            }
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve data");
                }
            });
        });
        
        $("#kategoriPeminjam").on("change", function () {
            var selectedKategoriPeminjam = $(this).val();
            var $asalPeminjamSelect = $("#asalPeminjam");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getRole') ?>",
                type: "POST",
                data: {
                    kategoriPeminjam: selectedKategoriPeminjam,
                },
                dataType: "json",
                success: function (response) {
                    $asalPeminjamSelect.empty();
                    if (response.length === 0) {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>Pilih Role</option>");
                        $.each(response, function (key, value) {
                            if (selectedKategoriPeminjam === 'siswa') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKelas + "'>" + value.namaKelas + "</option>");
                            } else if (selectedKategoriPeminjam === 'karyawan') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKategoriPegawai + "'>" + value.namaKategoriPegawai + "</option>");
                            }
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve data");
                }
            });
        });

        $("#idIdentitasLab").on("change", function () {
            var selectedIdIdentitasLab = $(this).val();
            var $idIdentitasSaranaSelect = $("#idIdentitasSarana");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getSaranaByLab') ?>",
                type: "POST",
                data: {
                    idIdentitasLab: selectedIdIdentitasLab,
                },
                dataType: "json",
                success: function (response) {
                    $idIdentitasSaranaSelect.empty();
                    if (response.length === 0) {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>Pilih Aset</option>");
                        $.each(response, function (key, value) {
                            $idIdentitasSaranaSelect.append("<option value='" + value.idIdentitasSarana + "'>" + value.namaSarana + "</option>");
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve kode rincian aset options.");
                }
            });
        });

        $("#idIdentitasSarana").on("change", function () {
            var selectedIdIdentitasSarana = $(this).val();
            var selectedIdIdentitasLab = $("#idIdentitasLab").val();
            var $kodeRincianLabAsetSelect = $("#kodeRincianLabAset");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getKodeBySarana') ?>",
                type: "POST",
                data: {
                    idIdentitasSarana: selectedIdIdentitasSarana,
                    idIdentitasLab: selectedIdIdentitasLab,
                },
                dataType: "json",
                success: function (response) {
                    $kodeRincianLabAsetSelect.empty();
                    if (response.length === 0) {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>Pilih Kode Aset</option>");
                        $.each(response, function (key, value) {
                            $kodeRincianLabAsetSelect.append("<option value='" + value.idRincianLabAset + "'>" + value.kodeRincianLabAset + "</option>");
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve kode rincian lab aset options.");
                }
            });
        });

    });


</script>
<?= $this->endSection(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rincian Aset Report</title>

    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/simplemde/simplemde.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: none;
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            width: 5%;
        }


        h3 {
            text-align: center;
        }

        img {
            display: block;
            margin: 0 auto;
            max-width: 200px;
            width: 100%;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <h3>Data Rincian Aset</h3>
            <div class="text-center">
                <img src="<?= base_url($dataRincianAset->link) ?>" alt="Foto Bukti"
                    style="width: 100%; max-width: 200px;" class="mx-auto">
            </div>
            <br>
            <table class="table" style="max-width: 90%; margin: 0 auto;">
                <tr>
                    <td style="width: 20%;">Kode Aset</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $dataRincianAset->kodeRincianAset?>
                    </td>
                </tr>
                <tr>
                    <td>Nama Aset</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->namaSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->namaPrasarana?>
                    </td>
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->namaSumberDana?>
                    </td>
                </tr>
                <tr>
                    <td>Kategori Manajemen</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->namaKategoriManajemen?>
                    </td>
                </tr>
                <tr>
                    <td>Tahun Pengadaan</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->tahunPengadaan?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Layak</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->saranaLayak?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Rusak</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->saranaRusak?>
                    </td>
                </tr>
                <tr>
                    <td>Total Sarana</td>
                    <td>:</td>
                    <td>
                        <?= $dataRincianAset->totalSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Spesifikasi</td>
                    <td>:</td>
                    <td>
                        <textarea class="form-control" id="editspek" name="spesifikasi" rows="10"
                            placeholder="Masukkan spesifikasi aset"><?=$dataRincianAset->spesifikasi?></textarea>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

<script src="<?= base_url(); ?>/assets/vendors/simplemde/simplemde.min.js"></script>
<script src="<?= base_url(); ?>/assets/js/simplemde.js"></script>
<script src="<?= base_url(); ?>/assets/js/my-simplemde.js"></script>

<script>
    window.onload = function () {
        window.print();
    };
</script>

</html>
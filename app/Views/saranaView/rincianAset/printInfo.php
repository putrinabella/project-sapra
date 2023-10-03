<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Aset Report</title>
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
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            width: 5%;
        }
    </style>
</head>

<body>
    <div class="table-responsive">
    <h2 class="mt-3 mb-4">Rincian Aset Report</h2>
    <div class="text-center">
                <img src="<?= base_url($dataRincianAset->link) ?>" alt="Foto Bukti" style="width: 100%; max-width: 200px;" class="mx-auto">
                </div>
                <br>
                <table class="table" style="max-width: 90%; margin: 0 auto;">
                    <tr>
                        <td style="width: 10%;">Kode Aset</td>
                        <td style="width: 5%;">:</td>
                        <td><?= $dataRincianAset->kodeRincianAset?></td>
                    </tr>
                    <tr>
                        <td>Nama Aset</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaSarana?></td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaPrasarana?></td>
                    </tr>
                    <tr>
                        <td>Sumber Dana</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaSumberDana?></td>
                    </tr>
                    <tr>
                        <td>Kategori Manajemen</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaKategoriManajemen?></td>
                    </tr>
                    <tr>
                        <td>Tahun Pengadaan</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->tahunPengadaan?></td>
                    </tr>
                    <tr>
                        <td>Sarana Layak</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->saranaLayak?></td>
                    </tr>
                    <tr>
                        <td>Sarana Rusak</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->saranaRusak?></td>
                    </tr>
                    <tr>
                        <td>Total Sarana</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->totalSarana?></td>
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
</body>

</html>

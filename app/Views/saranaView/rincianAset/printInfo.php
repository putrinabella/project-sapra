<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rincian Aset Report</title>

    <bukti rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/simplemde/simplemde.min.css">
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
            
            .image-container {
                text-align: center;
            }

            .image-container img {
                max-height: 300px;
                display: block;
                margin: 0 auto;
            }
        </style>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <h3>Data Rincian Aset
                <?= $data['dataRincianAset']->namaSarana?>
            </h3>
            <div class="image-container">
                <?php
                    $imageData = base64_encode(file_get_contents($data['buktiUrl']));
                    echo '<img src="data:image/png;base64,' . $imageData . '" alt="Foto Bukti">';
                ?>
            </div>


            <br>
            <table class="table" style="max-width: 90%; margin: 0 auto;">
                <tr>
                    <td style="width: 30%;">Kode Aset</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $data['dataRincianAset']->kodeRincianAset ?>
                    </td>
                </tr>
                <tr>
                    <td>Nama Aset</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->namaSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->namaPrasarana?>
                    </td>
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->namaSumberDana?>
                    </td>
                </tr>
                <tr>
                    <td>Kategori Manajemen</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->namaKategoriManajemen?>
                    </td>
                </tr>
                <tr>
                    <td>Tahun Pengadaan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->tahunPengadaan?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Layak</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->saranaLayak?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Rusak</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->saranaRusak?>
                    </td>
                </tr>
                <tr>
                    <td>Total Sarana</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianAset']->totalSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Spesifikasi</td>
                    <td>:</td>
                    <td>
                        <?=  $data['spesifikasiHtml']  ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
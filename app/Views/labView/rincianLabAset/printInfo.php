<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rincian Aset Lab Report</title>

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
            <h3>Data Rincian Aset Lab
                <?= $data['dataRincianLabAset']->namaSarana?>
            </h3>
            <div class="image-container">
                <?php
                    if (!empty($imageData = base64_encode(@file_get_contents($data['buktiUrl'])))) {
                        echo '<img src="data:image/png;base64,' . $imageData . '" alt="Foto Bukti">';
                    } else {
                        echo '(no image)';
                    }
                ?>

            </div>
            <br>
            <table class="table" style="max-width: 90%; margin: 0 auto;">
                <tr>
                    <td style="width: 30%;">Kode Aset</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->kodeLab ?>
                    </td>
                </tr>
                <tr>
                    <td>Nama Aset</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaLab?>
                    </td>
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaSumberDana?>
                    </td>
                </tr>
                <tr>
                    <td>Kategori Manajemen</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaKategoriManajemen?>
                    </td>
                </tr>
                <tr>
                    <td>Tahun Pengadaan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->tahunPengadaan?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Layak</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->saranaLayak?>
                    </td>
                </tr>
                <tr>
                    <td>Sarana Rusak</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->saranaRusak?>
                    </td>
                </tr>
                <tr>
                    <td>Total Sarana</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->saranaLayak + $data['dataRincianLabAset']->saranaRusak?>
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
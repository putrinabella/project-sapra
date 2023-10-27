<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rincian Aset Laboratorium</title>

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
                max-widht: 90%;
            }
        </style>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <h3>Data Rincian Aset Laboratorium
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
                        <?= $data['dataRincianLabAset']->kodeRincianLabAset ?>
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
                    <td>Kategori Barang</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaKategoriManajemen?>
                    </td>
                </tr>
                <tr>
                    <td>Spesifikasi Barang</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->namaSarana?>
                    </td>
                </tr>
                <tr>
                    <td>Status Aset</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->status?>
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
                    <td>Tahun Pengadaan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->tahunPengadaan?>
                    </td>
                </tr>
                <tr>
                    <td>Harga Beli</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->hargaBeli?>
                    </td>
                </tr>
                <tr>
                    <td>Merek</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->merk?>
                    </td>
                </tr>
                <tr>
                    <td>Warna</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataRincianLabAset']->warna?>
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
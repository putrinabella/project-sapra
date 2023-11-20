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
            border: 1px solid black;
            padding: 7px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            width: 5%;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="table-responsive">
        <table class="table table-hover" id="dataTable" style="width: 100%;">
            <thead>
                <tr class="text-center">
                    <td style="width: 5%;">No.</th>
                    <th>Lokasi</th>
                    <th>Kategori Barang</th>
                    <th>Spesifikasi Barang</th>
                    <th>Status</th>
                    <th>Sumber Dana</th>
                    <th>Tahun Pengadaan</th>
                    <th>Harga Beli</th>
                    <th>Merk</th>
                    <th>QR</th>
            </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataRincianAset'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td rowspan="2" >
                            <?= $key + 1 ?>
                        </td>
                        <td colspan="9">  
                            <?= $value->kodeRincianAset ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$value->namaPrasarana?></td>
                        <td><?=$value->namaKategoriManajemen?></td>
                        <td><?=$value->namaSarana?></td>
                        <td><?=$value->status?></td>
                        <td><?=$value->namaSumberDana?></td>
                        <td><?=$value->tahunPengadaan?></td>
                        <td><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                        <td><?=$value->merk?></td>
                        <td>      
                            <?php
                                if (!empty($value->qrCodeData)) {
                                    echo '<img src="' . $value->qrCodeData . '" alt="QR Code" style="max-width: 100px;">';
                                } else {
                                    echo '(no image)';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

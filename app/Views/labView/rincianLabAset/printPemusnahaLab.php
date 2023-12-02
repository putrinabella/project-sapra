<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemusnahan Report</title>
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
            padding: 8px;
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
    <h2 class="mt-3 mb-4">Data Pemusnahan Report</h2>
        <table class="table table-hover" id="dataTable" style="width: 100%;">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Tanggal Pemusnahan</th>
                    <th>Nama Akun</th>
                    <th>Kode Akun</th>
                    <th>Lokasi</th>
                    <th>Kategori Barang</th>
                    <th>Spesifikasi Barang</th>
                    <th>Status</th>
                    <th>Sumber Dana</th>
                    <th>Tahun Pengadaan</th>
                    <th>Merek</th>
                    <th>Warna</th>
            </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataPemusnahan'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td rowspan="2" >
                            <?= $key + 1 ?>
                        </td>
                        <td colspan="11">  
                            <?= $value->kodeRincianLabAset ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$value->tanggalPemusnahan?></td>
                        <td><?=$value->namaAkun?></td>
                        <td><?=$value->kodeAkun?></td>
                        <td><?=$value->namaLab?></td>
                        <td><?=$value->namaKategoriManajemen?></td>
                        <td><?=$value->namaSarana?></td>
                        <td><?=$value->status?></td>
                        <td><?=$value->namaSumberDana?></td>
                        <td>
                            <?php 
                                if ($value->tahunPengadaan == 0 || 0000) {
                                    echo "Tidak diketahui";
                                } else {
                                    echo $value->tahunPengadaan;
                                }
                            ?>
                        </td>
                        <td><?=$value->merk?></td>
                        <td><?=$value->warna?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

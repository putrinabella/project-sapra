<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rincian Aset Laboratorium </title>
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
    <h2 class="mt-3 mb-4">Rincian Aset Laboratorium </h2>
        <table class="table table-hover" id="dataTable" style="width: 100%;">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Lokasi</th>
                    <th>Kategori Barang</th>
                    <th>Spesifikasi Barang</th>
                    <th>Status</th>
                    <th>Ketersediaan</th>
                    <th>Sumber Dana</th>
                    <th>Tahun Pengadaan</th>
                    <th>Harga Beli</th>
                    <th>Merek</th>
                    <th>Warna</th>
            </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataRincianLabAset'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td rowspan="2" >
                            <?= $key + 1 ?>
                        </td>
                        <td colspan="10">  
                            <?= $value->kodeRincianLabAset ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=$value->namaLab?></td>
                        <td><?=$value->namaKategoriManajemen?></td>
                        <td><?=$value->namaSarana?></td>
                        <td><?=$value->status?></td>
                        <td class="text-center">
                            <?php if ($value->sectionAset == "None") : ?>
                                Tersedia
                            <?php else : ?>
                                <?= $value->sectionAset; ?> 
                            <?php endif; ?>
                        </td>
                        <td><?=$value->namaSumberDana?></td>
                        <td><?=$value->tahunPengadaan?></td>
                        <td><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                        <td><?=$value->merk?></td>
                        <td><?=$value->warna?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

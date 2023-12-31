<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $dataLaboratorium->namaLab; ?>
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .row {
            display: flex;
            margin: -10px;
        }

        .col {
            flex: 1;
            padding: 10px;
        }

        .border {
            border: 1px solid #ccc;
        }

        .rounded-2 {
            border-radius: 0.25rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        .table {
            max-width: 100%;
            width: 100%;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            border: 1px solid #e6dfdf;
            padding: 10px;
        }


        .my-table {
            max-width: 100%;
            width: 100%;
            border-collapse: collapse;
        }

        .my-table td,
        .my-table th {
            border: 1px solid #e6dfdf;
            padding: 10px;
            text-align: center;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td>
                        Nama Lab
                    </td>
                    <td>
                        <?= $dataLaboratorium->namaLab; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Kode Lab
                    </td>
                    <td>
                        <?= $dataLaboratorium->kodeLab; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Lokasi Gedung
                    </td>
                    <td>
                        <?= $dataInfoLab->namaGedung; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Lokasi Lantai
                    </td>
                    <td>
                        <?= $dataInfoLab->namaLantai; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Luas
                    </td>
                    <td>
                        <?= $luasFormatted = number_format($dataLaboratorium->luas, 0, ',', '.'); ?> m2
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Aset
                    </td>
                    <td>
                        <?= count($dataSarana); ?>
                    </td>
                </tr>
            </table>
            <h3> Rincian Aset </h3>
            <table class="my-table">
                <thead>
                    <tr class="text-center">
                        <th style="width: 5%;">No.</th>
                        <th style="width: 12%;">Kode Aset</th>
                        <th>Kategori Aset</th>
                        <th>Nama Aset</th>
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
                    <?php foreach ($dataSarana as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td class="text-center">
                            <?= $key + 1 ?>
                        </td>
                        <td class="text-center"><?=$value->kodeRincianLabAset?></td>
                        <td class="text-center"><?=$value->namaKategoriManajemen?></td>
                        <td class="text-center"><?=$value->namaSarana?></td>
                        <td class="text-center"><?=$value->status?></td>
                        <td class="text-center">
                            <?php if ($value->sectionAset == "None") : ?>
                            Tersedia
                            <?php else : ?>
                                <?= $value->sectionAset;; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?=$value->namaSumberDana?></td>
                        <td class="text-center">
                            <?php 
                            if($value->tahunPengadaan == 0 || 0000) {
                                echo "Tidak diketahui"; 
                            } else {
                                echo $value->tahunPengadaan;
                            };
                        ?>
                        </td>
                        <td class="text-center"><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                        <td class="text-center"><?=$value->merk?></td>
                        <td class="text-center"><?=$value->warna?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
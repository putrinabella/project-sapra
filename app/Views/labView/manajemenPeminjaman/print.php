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
            </table>
            <h3> Rincian Aset </h3>
            <table class="my-table">
                <thead>
                    <tr class="text-center">
                        <th style="width: 5%;">No.</th>
                        <th>Nama Aset</th>
                        <th>Jumlah Aset</th>
                        <th>Aset Dipinjam</th>
                        <th>Aset Tersedia</th>
                    </tr>
                </thead>
                <tbody class="py-2">
                    <?php foreach ($dataSarana as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td class="text-center">
                            <?= $key + 1 ?>
                        </td>
                        <td class="text-center">
                            <?= $value->namaSarana ?>
                        </td>
                        <td class="text-center">
                            <?= $value->totalSaranaLayak ?>
                        </td>
                        <td class="text-center">
                            <?= $value->jumlahPeminjaman ?>
                        </td>
                        <td class="text-center">
                            <?= $value->asetTersedia ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
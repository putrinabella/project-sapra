<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $dataPerangkatIt->namaSarana; ?>
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
                        Nama Sarana
                    </td>
                    <td>
                        <?= $dataPerangkatIt->namaSarana; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jumlah Keseluruhah
                    </td>
                    <td>
                        <?= !empty($totalSarana) ? $totalSarana : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jumlah
                        <?= $dataPerangkatIt->namaSarana; ?> Layak
                    </td>
                    <td>
                        <?= !empty($saranaLayak) ? $saranaLayak : '-'; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Jumlah
                        <?= $dataPerangkatIt->namaSarana; ?> rusak
                    </td>
                    <td>
                        <?= !empty($saranaRusak) ? $saranaRusak : '-'; ?>
                    </td>
                </tr>
            </table>
            <h3> Rincian Aset </h3>
            <table class="my-table">
                <thead>
                    <tr class="text-center">
                        <th style="width: 5%;">No.</th>
                        <th>Lokasi</th>
                        <th>Tahun Pengadaan</th>
                        <th>Aset Layak</th>
                        <th>Aset Rusak</th>
                        <th>Total Aset</th>
                    </tr>
                </thead>
                <tbody class="py-2">
                    <?php foreach ($dataAsetIT as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td rowspan="2" class="text-center">
                            <?=$key + 1?>
                        </td>
                        <td><?=$value->namaPrasarana?></td>
                        <td><?=$value->tahunPengadaan?></td>
                        <td><?=$value->saranaLayak?></td>
                        <td><?=$value->saranaRusak?></td>
                        <td><?=$value->totalSarana?></td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: justify;">
                        <?=nl2br($value->spesifikasi)?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
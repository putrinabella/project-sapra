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
    <h2 class="mt-3 mb-4">Rincian Aset Report</h2>
        <table class="table table-hover" id="dataTable">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama Aset</th>
                    <th>Aset Bagus</th>
                    <th>Aset Rusak</th>
                    <th>Aset Hilang</th>
                    <th>Total Aset</th>
            </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataSarana'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td> <?= $key + 1 ?> </td>
                        <td class="text-center"><?=$value->namaSarana?></td>
                        <td class="text-center"><?=$value->jumlahBagus?></td>
                        <td class="text-center"><?=$value->jumlahRusak?></td>
                        <td class="text-center"><?=$value->jumlahHilang?></td>
                        <td class="text-center"><?=$value->jumlahAset?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

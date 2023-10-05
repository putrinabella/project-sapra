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
                    <th>Lokasi</th>
                    <th>Sumber Dana</th>
                    <th>Kategori Manajemen</th>
                    <th>Tahun Pengadaan</th>
                    <th>Sarana Layak</th>
                    <th>Sarana Rusak</th>
                    <th>Total Sarana</th>
                </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataRincianAset'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td rowspan="2" >
                            <?= $key + 1 ?>
                        </td>
                        <td colspan="8">  
                            <?= $value->kodeRincianAset ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $value->namaSarana ?>
                        </td>
                        <td>
                            <?= $value->namaPrasarana ?>
                        </td>
                        <td>
                            <?= $value->namaSumberDana ?>
                        </td>
                        <td>
                            <?= $value->namaKategoriManajemen ?>
                        </td>
                        <td>
                            <?= $value->tahunPengadaan ?>
                        </td>
                        <td>
                            <?= $value->saranaLayak ?>
                        </td>
                        <td>
                            <?= $value->saranaRusak ?>
                        </td>
                        <td>
                            <?= $value->totalSarana ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

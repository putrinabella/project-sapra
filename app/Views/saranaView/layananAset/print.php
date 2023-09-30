<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarana Layanan Aset Report</title>
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
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            width: 5%;
        }
    </style>
</head>

<body>
    <div class="table-responsive">
        <table class="table table-hover" id="dataTable">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama Aset</th>
                    <th>Lokasi</th>
                    <th>Status Layanan</th>
                    <th>Kategori Manajemen</th>
                    <th>Sumber Dana</th>
                    <th>Biaya</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody class="py-2">
                <?php foreach ($data['dataSaranaLayananAset'] as $key => $value) : ?>
                    <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                        <td>
                            <?= $key + 1 ?>
                        </td>
                        <td>
                            <?= $value->tanggal ?>
                        </td>
                        <td>
                            <?= $value->namaSarana ?>
                        </td>
                        <td>
                            <?= $value->namaPrasarana ?>
                        </td>
                        <td>
                            <?= $value->namaStatusLayanan ?>
                        </td>
                        <td>
                            <?= $value->namaKategoriManajemen ?>
                        </td>
                        <td>
                            <?= $value->namaSumberDana ?>
                        </td>
                        <td>
                            Rp<?= number_format($value->biaya, 0, ',', '.') ?>
                        </td>
                        <td>
                            <?= $value->bukti ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

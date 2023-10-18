<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Kelas Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mt-3 mb-4">Identitas Kelas Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Identitas Kelas</th>
                    <th>Nama Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataIdentitasKelas'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        G<?= sprintf('%02d', $value->idIdentitasKelas) ?>
                    </td>
                    <td>
                        <?= $value->namaKelas ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
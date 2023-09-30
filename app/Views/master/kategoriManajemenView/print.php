<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Manajemen Report</title>
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
        <h2 class="mt-3 mb-4">Kategori Manajemen Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Kategori Manajemen</th>
                    <th>Kategori Manajemen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataKategoriManajemen'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        KM<?= sprintf('%02d', $value->idKategoriManajemen) ?>
                    </td>
                    <td>
                        <?= $value->namaKategoriManajemen ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identias Prasarana Report</title>
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
        <h2 class="mt-3 mb-4">Identias Prasarana Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Prasarana</th>
                    <th>Nama Prasarana</th>
                    <th>Lokasi Gedung</th>
                    <th>Lokasi Lantai</th>
                    <th>Luas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataidentitasPrasarana'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?= $value->kodePrasarana ?>
                    </td>
                    <td>
                        <?= $value->namaPrasarana ?>
                    </td>
                    <td>
                        <?= $value->namaGedung ?>
                    </td>
                    <td>
                        <?= $value->namaLantai ?>
                    </td>
                    <td>
                        <?= $value->luas ?> m²
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
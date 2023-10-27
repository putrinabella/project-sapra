<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identias Lab Report</title>
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
            text-align: center;
        }

        th {
           text-align: center;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mt-3 mb-4">Identias Lab Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Lokasi Gedung</th>
                    <th>Lokasi Lantai</th>
                    <th>Luas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataidentitasLab'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?= $value->kodeLab ?>
                    </td>
                    <td>
                        <?= $value->namaLab ?>
                    </td>
                    <td>
                        <?= $value->tipe ?>
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Lantai Report</title>
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
        <h2 class="mt-3 mb-4">Identitas Lantai Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Identitas Lantai</th>
                    <th>Nama Lantai</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataIdentitasLantai'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        L<?= sprintf('%02d', $value->idIdentitasLantai) ?>
                    </td>
                    <td>
                        <?= $value->namaLantai ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
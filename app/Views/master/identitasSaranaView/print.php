<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Sarana Report</title>
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
        <h2 class="mt-3 mb-4">Identitas Sarana Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Identitas Sarana</th>
                    <th>Nama Sarana</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataIdentitasSarana'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        S<?= sprintf('%02d', $value->idIdentitasSarana) ?>
                    </td>
                    <td>
                        <?= $value->namaSarana ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
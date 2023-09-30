<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Layanan Report</title>
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
        <h2 class="mt-3 mb-4">Status Layanan Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Status Layanan</th>
                    <th>Status Layanan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataStatusLayanan'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        SL<?= sprintf('%02d', $value->idStatusLayanan) ?>
                    </td>
                    <td>
                        <?= $value->namaStatusLayanan ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
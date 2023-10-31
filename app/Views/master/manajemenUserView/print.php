<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
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
        <h2 class="mt-3 mb-4">Data User</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>User</th>
                    <th>PIC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataManajemenUser'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?= $value->namaManajemenUser ?>
                    </td>
                    <td>
                        <?= $value->picManajemenUser ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
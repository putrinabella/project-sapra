<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sumber Dana Report</title>
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
        <h2 class="mt-3 mb-4">Sumber Dana Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Sumber Dana</th>
                    <th>Nama Sumber Dana</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataSumberDana'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?= sprintf('%03d', $value->idSumberDana) ?>
                    </td>
                    <td>
                        <?= $value->namaSumberDana ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
    window.onload = function () {
        window.print();
    };
</script>
</body>

</html>
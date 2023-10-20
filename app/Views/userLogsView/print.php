<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs Report</title>
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
        <h2 class="mt-3 mb-4">User Logs Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Action Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataUserLogs'] as $key => $value) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?=$value->username?>
                    </td>
                    <td>
                        <?=$value->role?>
                    </td>
                    <td>
                        <?= date('H:i:s', strtotime($value->loginTime)) ?>
                    </td>
                    <td>
                        <?= date('d F Y', strtotime($value->loginTime)) ?>
                    </td>
                    <td>
                        <?= $value->actionType ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Data Inventaris</title>
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
        <h2 class="mt-3 mb-4">Data Inventaris</h2>

        <!-- Table for Pemasukan -->
        <h3>Pemasukan</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataDataInventaris'] as $key => $value) : ?>
                    <?php if ($value->tipeDataInventaris == 'Pemasukan') : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->tanggalDataInventaris ?></td>
                            <td><?= $value->namaInventaris ?></td>
                            <td><?= $value->satuan ?></td>
                            <td><?= $value->jumlahDataInventaris ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Table for Pengeluaran -->
        <h3>Pengeluaran</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataDataInventaris'] as $key => $value) : ?>
                    <?php if ($value->tipeDataInventaris == 'Pengeluaran') : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->tanggalDataInventaris ?></td>
                            <td><?= $value->namaInventaris ?></td>
                            <td><?= $value->satuan ?></td>
                            <td><?= $value->jumlahDataInventaris ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>


</html>
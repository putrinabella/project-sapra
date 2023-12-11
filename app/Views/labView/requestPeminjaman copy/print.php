<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Report</title>
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
            padding: 2px;
            text-align: left;
            text-align: center;
        }

        th {
            text-align: center;
        }

        .detail th,
        .detail td {
            border: none;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mt-3 mb-4">Data Peminjaman Report</h2>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama Peminjam</th>
                    <th>Karyawan/Siswa</th>
                    <th>Status</th>
                    <th>Jumlah Aset</th>
                    <th>Lokasi</th>
                    <th>Tanggal Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['dataDataPeminjaman'] as $key => $value) : ?>
                
                <tr>
                    <td class="text-center">
                        <?= $key + 1 ?>
                    </td>
                    <td class="text-center">
                        <?= date('d F Y', strtotime($value->tanggal)) ?>
                    </td>
                    <td>
                        <?= $value->namaPeminjam ?>
                    </td>
                    <td>
                        <?= $value->asalPeminjam ?>
                    </td>
                    <td>
                        <?php if ($value->loanStatus == "Peminjaman"): ?>
                        <span class="badge bg-warning">Sedang Dipinjam</span>
                        <?php else: ?>
                        <span class="badge bg-success">Sudah Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $value->jumlahPeminjaman ?>
                    </td>
                    <td>
                        <?= $value->namaLab ?>
                    </td>
                    <td>
                        <?= $value->tanggalPengembalian ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah Report</title>

    <bukti rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/simplemde/simplemde.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
            }

            .table-responsive {
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: none;
                border-bottom: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                width: 5%;
            }


            h3 {
                text-align: center;
            }

            .image-container {
                text-align: center;
            }

            .image-container img {
                max-height: 300px;
                display: block;
                margin: 0 auto;
            }
        </style>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <h3>Data Profil Sekolah
            </h3>
            <br>
            <h4>Identitas Sekolah</h4>
            <table class="my-table">
                <tr>
                    <td style="width: 30%;">NPSN</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->npsn?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->status; ?>
                    </td>
                </tr>
                <tr>
                    <td>Bentuk Pendidikan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->bentukPendidikan; ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Kepemilikan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->statusKepemilikan; ?>
                    </td>
                </tr>
                <tr>
                    <td>SK Pendirian</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->skPendirian; ?>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal SK Pendirian</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->tanggalSkPendirian; ?>
                    </td>
                </tr>
                <tr>
                    <td>SK Izin Operasional</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->skIzinOperasional; ?>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal SK Izin Operasinal</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->tanggalSkIzinOperasional; ?>
                    </td>
                </tr>
            </table>
            <br>
            <h4>Data Rinci</h4>
            <table class="my-table">
                <tr>
                    <td style="width: 30%;">Status BOS</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->statusBos; ?>
                    </td>
                </tr>
                <tr>
                    <td>Waktu Penyelenggaraan</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->waktuPenyelenggaraan; ?>
                    </td>
                </tr>
                <tr>
                    <td>Spesifikasi ISO</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->sertifikasiIso; ?>
                    </td>
                </tr>
                <tr>
                    <td>Sumber Listrik</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->sumberListrik; ?>
                    </td>
                </tr>
                <tr>
                    <td>Kecepatan Internet</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->kecepatanInternet; ?>
                    </td>
                </tr>
            </table>
            <br>
            <h4>Data Pelengkap</h4>
            <table class="my-table">
                <tr>
                <tr>
                    <td style="width: 30%;">Siswa Berkebutuhan Khusus</td>
                    <td style="width: 5%;">:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->siswaKebutuhanKhusus; ?>
                    </td>
                </tr>
                <tr>
                    <td>Nama Bank</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->namaBank; ?>
                    </td>
                </tr>
                <tr>
                    <td>Cabang KCP</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->cabangKcp; ?>
                    </td>
                </tr>
                <tr>
                    <td>Atas Nama Rekening</td>
                    <td>:</td>
                    <td>
                        <?= $data['dataProfilSekolah']->atasNamaRekening; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
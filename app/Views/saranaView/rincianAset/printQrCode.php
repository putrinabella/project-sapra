<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Rincian Aset </title>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <table>
                <tr>
                    <?php $count = 0; ?>
                    <?php foreach ($data['dataRincianAset'] as $key => $value) : ?>
                    <td style="padding: 0;">
                        <table
                            style="width: 100%; border-collapse: collapse; margin: 0 auto; padding: 10px; border: 1px dashed #000;">
                            <tr>
                                <td style="width: 30%; padding: 10 0 10 10;">
                                    <div class="image-container">
                                        <?php if (!empty($value->qrCodeData)) : ?>
                                        <img src="<?= $value->qrCodeData ?>" alt="QR Code" style="max-width: 100px;">
                                        <?php else : ?>
                                        (QR Code not found)
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="width: 70%; vertical-align: top; padding:10 5 10 10;">
                                    <?= $value->kodeRincianAset ?>
                                    <br>
                                    <?= $value->namaPrasarana ?>
                                    <br>
                                    <?= $value->namaSarana ?>
                                    <br>
                                    Tahun Pengadaan:
                                    <?= ($value->tahunPengadaan == 0 || $value->tahunPengadaan == '0000') ? '-' : $value->tahunPengadaan ?>
                                    <br>
                                    Sumber Dana:
                                    <?= $value->namaSumberDana ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <?php $count++; ?>
                    <?php if ($count % 2 == 0) : ?>
                </tr>
                <tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
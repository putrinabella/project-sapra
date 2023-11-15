<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selected QR Code</title>
</head>

<body>
    <div class="card overflow-hidden">
        <div class="card-body">
            <table class="table" style="max-width: 90%; margin: 0 auto; padding: 0;">
                <tr>
                    <?php $count = 0; ?>
                    <?php foreach ($data['dataRincianLabAset'] as $key => $value) : ?>
                    <td style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 30%;">
                                    <div class="image-container">
                                        <?php if (!empty($value->qrCodeData)) : ?>
                                        <img src="<?= $value->qrCodeData ?>" alt="QR Code" style="max-width: 100px;">
                                        <?php else : ?>
                                        (no image)
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="width: 70%; vertical-align: top; padding: 0 10px;">
                                    <?= $value->kodeRincianLabAset ?>
                                    <br>
                                    <?= $value->namaLab ?>
                                    <br>
                                    <?= $value->namaSarana ?>
                                    <br>
                                    <?= $value->tahunPengadaan ?>
                                    <br>
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
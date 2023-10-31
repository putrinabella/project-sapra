<?php

namespace App\Controllers;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class QRBarcode extends BaseController
{

    public function generateQRCode($idRincianAset)    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($idRincianAset)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        $dataUri = $result->getDataUri();

        // You can return the QR code image or render it on a view as needed
        return view('qrCodeView', ['qrCode' => $dataUri]);
    }

    public function index()
    {
        
        $writer = new PngWriter();
        $qrCode = QrCode::create('Putri Nabella')
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(300)
        ->setMargin(10)
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        $dataUri = $result->getDataUri();
        echo '<img src="'.$dataUri.'" alt="QR Code">';
    }

    public function test()
    {
        
        $writer = new PngWriter();

        // Create QR code
        $qrCode = QrCode::create('Data')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic logo
        $logo = Logo::create('logo-round.png')
            ->setResizeToWidth(150);

        // Create generic label
        $label = Label::create('SMK Telkom Banjarbaru')
            ->setTextColor(new Color(0, 0, 0));

        $result = $writer->write($qrCode, $logo, $label);
        
        $dataUri = $result->getDataUri();
        echo '<img src="'.$dataUri.'" alt="SMK Telkom Banjarbaru">';
    }
}

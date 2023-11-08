<?php if (!defined("BASEPATH")) {
    exit("No direct script access allowed");
}
require_once "tcpdf/tcpdf.php";
class MYPDF extends TCPDF
{
}
function generate_pdf($data, $id_barcode)
{
    $pdf = new MYPDF("P", PDF_UNIT, "F4", true, "UTF-8", false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("Putri Nabella");
    $pdf->SetTitle("Sarana, Prasarana, Laboratorium, dan IT");
    $pdf->SetSubject("SMK TELKOM BANJARBARU");
    $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
    $pdf->SetLineStyle([
        "width" => 0.5,
        "cap" => "butt",
        "join" => "miter",
        "dash" => 4,
        "color" => [255, 0, 0],
    ]);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(5, 5, 5);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, "", 10]);
    $pdf->SetAutoPageBreak(true, 12);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__) . "/lang/eng.php")) {
        require_once dirname(__FILE__) . "/lang/eng.php";
        $pdf->setLanguageArray($l);
    }
    $pdf->AddPage();
    $pdf->SetFont("times", "", 10);
    $styleQR = [
        "border" => 1,
        "vpadding" => "1",
        "hpadding" => "1",
        "fgcolor" => [0, 0, 0],
        "bgcolor" => true,
        "module_width" => 1,
        "module_height" => 1,
    ];
    $i = 1;
    $html = '<table border="0">';
    if (sizeof($data) > 1) {
        foreach ($data as $row => $value) {
            $params = $pdf->serializeTCPDFtagParameters([
                $value[$id_barcode],
                "QRCODE",
                "",
                "",
                25,
                25,
                $styleQR,
                "N",
            ]);
            $htmlBarckode =
                '<tcpdf method="write2DBarcode" params="' . $params . '" />';
            if ($i % 2 != 0) {
                $html .= '<tr  nobr="true">';
            } else {
                $html .= '<td width="50px"></td>';
            }
            $html .= '<td width="200px"><b>'.$value["masKode"] ."</b><br>" .
               		 	$value["masMerek"] ."<br>".
                		$value["masNoMesin"] ."<br><i>" .
                		$value["masNoRangka"] .
                		"</i></td>";
            $html .= '<td width="100px">'.$htmlBarckode .'</td>';
            
            if (($i + 1) % 2 != 0) {
                $html .= "</tr><br><br>";
            }
            $i++;
        }
    } else {
        foreach ($data as $row => $value) {
            $params = $pdf->serializeTCPDFtagParameters([
                $value[$id_barcode],
                "QRCODE",
                "",
                "",
                25,
                25,
                $styleQR,
                "N",
            ]);
            $htmlBarckode =
                '<tcpdf method="write2DBarcode" params="' . $params . '" />';
            $html .= '<tr  nobr="true">';
            $html .= '<td width="200px"><b>'.$value["masKode"] ."</b><br>" .
                        $value["masMerek"] ."<br>".
                        "No. Mesin <br><i>".$value["masNoMesin"] ."</i><br>" .
                        "No. Rangka <br><i>".$value["masNoRangka"] .
                        "</i></td>";
			$html .= '<td width="100px">'.$htmlBarckode .'</td>';
            
            $html .= "</tr>";
            $i++;
        }
    }
    $html .= "</table>";
    $pdf->writeHTML(trim($html), true, true, true, true, "");
    $pdf->Output("BARCODE.pdf", "I");
} ?>
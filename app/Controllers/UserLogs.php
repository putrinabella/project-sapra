<?php

namespace App\Controllers;

use App\Models\UserLogModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class UserLogs extends BaseController
{
    
     function __construct() {
        $this->userLoginModel = new UserLogModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }
    
    public function viewLogs()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }
            
        $data['tableHeading'] = $tableHeading;        
        $data['dataUserLog'] = $this->userLoginModel->getAll($startDate, $endDate);

        return view('userLogsView/userLoginLogs/viewLogs', $data);
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        
        $data = $this->userLoginModel->getAll($startDate, $endDate);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('User Log');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Username', 'Role', 'Time', 'Date', 'Action Type'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {           
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->username);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->role);
            $activeWorksheet->setCellValue('D'.($index + 2), date('H:i:s', strtotime($value->loginTime)));
            $activeWorksheet->setCellValue('E'.($index + 2), date('d F Y', strtotime($value->loginTime)));
            $activeWorksheet->setCellValue('F'.($index + 2), $value->actionType);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $activeWorksheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }           
        }
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - User Logs.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataUserLogs = $this->userLoginModel->getAll($startDate, $endDate);
        
        $title = "REPORT USER LOGS";
        
        if (!$dataUserLogs) {
            return view('error/404');
        }
    
        $pdfData = pdfUserLogs($dataUserLogs, $title, $startDate, $endDate);
    
        
        $filename = 'Logs - User Logs' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
    
    public function generatePDFs() {
        $filePath = APPPATH . 'Views/userLogsView/userLoginLogs/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataUserLogs'] = $this->userLoginModel->getAll();

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = "Data Master - User Logs.pdf";
        $dompdf->stream($filename);
    }
}
<?php

namespace App\Controllers;

use App\Models\UserActionLogsModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class UserActionLogs extends BaseController
{
    
     function __construct() {
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf']);
    }
    
    public function viewActions()
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
        $data['dataActionLog'] = $this->userActionLogsModel->getAll($startDate, $endDate);
        return view('userLogsView/userActionLogs/actionLogs', $data);
    }

    public function export() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        
        $data = $this->userActionLogsModel->getData($startDate, $endDate);
        $dataRestore = $this->userActionLogsModel->getDataRestore($startDate, $endDate);
        $dataDelete = $this->userActionLogsModel->getDataDelete($startDate, $endDate);
        $dataSoftDelete = $this->userActionLogsModel->getDataSoftDelete($startDate, $endDate);

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('User Actions');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Username', 'Role', 'Time', 'Date','Action Type', 'Detail'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {           
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), date('d F Y', strtotime($value->actionTime)));
            $activeWorksheet->setCellValue('C'.($index + 2), date('H:i:s', strtotime($value->actionTime)));
            $activeWorksheet->setCellValue('D'.($index + 2), $value->username);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->role);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->actionType);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->actionDetails);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
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
        $activeWorksheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:G'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $restoreSheet = $spreadsheet->createSheet();
        $restoreSheet->setTitle('Restore');
        $restoreSheet->getTabColor()->setRGB('DF2E38');
        $restoreSheet->fromArray([$headers], NULL, 'A1');
        $restoreSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataRestore as $index => $value) {           
            $restoreSheet->setCellValue('A'.($index + 2), $index + 1);
            $restoreSheet->setCellValue('B'.($index + 2), date('d F Y', strtotime($value->actionTime)));
            $restoreSheet->setCellValue('C'.($index + 2), date('H:i:s', strtotime($value->actionTime)));
            $restoreSheet->setCellValue('D'.($index + 2), $value->username);
            $restoreSheet->setCellValue('E'.($index + 2), $value->role);
            $restoreSheet->setCellValue('F'.($index + 2), $value->actionType);
            $restoreSheet->setCellValue('G'.($index + 2), $value->actionDetails);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $restoreSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }                
        }
        $restoreSheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $restoreSheet->getStyle('A1:G1')->getFont()->setBold(true);
        $restoreSheet->getStyle('A1:G'.$restoreSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $restoreSheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'G') as $column) {
            $restoreSheet->getColumnDimension($column)->setAutoSize(true);
        }


        $deleteSheet = $spreadsheet->createSheet();
        $deleteSheet->setTitle('Delete');
        $deleteSheet->getTabColor()->setRGB('DF2E38');
        $deleteSheet->fromArray([$headers], NULL, 'A1');
        $deleteSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataDelete as $index => $value) {           
            $deleteSheet->setCellValue('A'.($index + 2), $index + 1);
            $deleteSheet->setCellValue('B'.($index + 2), date('d F Y', strtotime($value->actionTime)));
            $deleteSheet->setCellValue('C'.($index + 2), date('H:i:s', strtotime($value->actionTime)));
            $deleteSheet->setCellValue('D'.($index + 2), $value->username);
            $deleteSheet->setCellValue('E'.($index + 2), $value->role);
            $deleteSheet->setCellValue('F'.($index + 2), $value->actionType);
            $deleteSheet->setCellValue('G'.($index + 2), $value->actionDetails);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $deleteSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }                
        }
        $deleteSheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $deleteSheet->getStyle('A1:G1')->getFont()->setBold(true);
        $deleteSheet->getStyle('A1:G'.$deleteSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $deleteSheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'G') as $column) {
            $deleteSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $softDeleteSheet = $spreadsheet->createSheet();
        $softDeleteSheet->setTitle('Soft Delete');
        $softDeleteSheet->getTabColor()->setRGB('DF2E38');
        $softDeleteSheet->fromArray([$headers], NULL, 'A1');
        $softDeleteSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($dataSoftDelete as $index => $value) {           
            $softDeleteSheet->setCellValue('A'.($index + 2), $index + 1);
            $softDeleteSheet->setCellValue('B'.($index + 2), date('d F Y', strtotime($value->actionTime)));
            $softDeleteSheet->setCellValue('C'.($index + 2), date('H:i:s', strtotime($value->actionTime)));
            $softDeleteSheet->setCellValue('D'.($index + 2), $value->username);
            $softDeleteSheet->setCellValue('E'.($index + 2), $value->role);
            $softDeleteSheet->setCellValue('F'.($index + 2), $value->actionType);
            $softDeleteSheet->setCellValue('G'.($index + 2), $value->actionDetails);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
            foreach ($columns as $column) {
                $cellReference = $column . ($index + 2);
                $alignment = $softDeleteSheet->getStyle($cellReference)->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                if ($column === 'A') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    
                }
            }                
        }
        $softDeleteSheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $softDeleteSheet->getStyle('A1:G1')->getFont()->setBold(true);
        $softDeleteSheet->getStyle('A1:G'.$softDeleteSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $softDeleteSheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'G') as $column) {
            $softDeleteSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Data Master - User Action.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }


    public function generatePDF() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $dataRestore = $this->userActionLogsModel->getDataRestore($startDate, $endDate);
        $dataDelete = $this->userActionLogsModel->getDataDelete($startDate, $endDate);
        $dataSoftDelete = $this->userActionLogsModel->getDataSoftDelete($startDate, $endDate);
        
        $title = "REPORT USER ACTION";
        
        if (!$dataRestore && !$dataDelete && !$dataSoftDelete) {
            return view('error/404');
        }
    
        $pdfData = pdfUserAction($dataRestore, $dataDelete, $dataSoftDelete, $title, $startDate, $endDate);
    
        
        $filename = 'Logs - User Action' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }

}
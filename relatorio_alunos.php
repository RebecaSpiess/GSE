<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;


class relatorio_alunos {
    
    function gerarRelatorio() {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Hello World !');
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="relatorio_alunos.xlsx"');
        $streamedResponse->send();
        exit;
    }
}

?>
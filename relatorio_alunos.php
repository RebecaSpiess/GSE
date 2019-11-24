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
            
            $db = new db();
            $alunos = $db->query('SELECT * FROM PESSOA WHERE TIPO_PESSOA = 3');
            $alunosResult = $alunos->fetchAll();
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Nome');
            $sheet->setCellValue('B1', 'Sobrenome');
            $sheet->setCellValue('C1', 'E-Mail');
            $sheet->setCellValue('D1', 'Data de nascimento');
            $sheet->setCellValue('E1', 'Sexo');
            
            for($i = 0; $i < sizeof($alunosResult); $i++){
                $sheet->setCellValue('A'.($i + 2), $alunosResult[$i]["NOME"]);
                $sheet->setCellValue('B'.($i + 2), $alunosResult[$i]["SOBRENOME"]);
                $sheet->setCellValue('C'.($i + 2), $alunosResult[$i]["EMAIL"]);
                $sheet->setCellValue('D'.($i + 2), $alunosResult[$i]["DATA_NASCIMENTO"]);
                $sheet->setCellValue('E'.($i + 2), $alunosResult[$i]["TIPO_SEXO"]);
            }
            
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
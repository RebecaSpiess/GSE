<?php 

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class relatorio_turma {
    
    function gerarRelatorio() {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () {
            
            $db = new db();
            $alunos = $db->query("SELECT TU.NOME_TURMA AS TURMA, MA.NOME AS MATERIA, CONCAT(CONCAT(PE_PROFESSOR.NOME, ' '), PE_PROFESSOR.SOBRENOME) AS 'PROFESSOR',  PE.NOME, PE.SOBRENOME, PE.EMAIL, PE.DATA_NASCIMENTO, SEX.SEXO from TURMA TU 
            JOIN TURMA_PESSOA TUP ON (TU.ID = TUP.ID_TURMA)
            JOIN PESSOA PE ON (PE.ID = TU.ID_PESSOA)
            JOIN PESSOA PE_PROFESSOR ON (TU.ID_PESSOA = PE_PROFESSOR.ID)
            JOIN SEXO SEX ON (SEX.ID = PE.TIPO_SEXO)
            JOIN MATERIA MA ON (MA.ID = TU.ID)");
            $alunosResult = $alunos->fetchAll();
            
            $spreadsheet = new Spreadsheet();
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Turmas');
            $spreadsheet->addSheet($sheet, 0);
            $sheet->setCellValue('A1', 'Turma');
            $sheet->setCellValue('B1', 'Matéria');
            $sheet->setCellValue('C1', 'Professor');
            $sheet->setCellValue('D1', 'Nome');
            $sheet->setCellValue('E1', 'Sobrenome');
            $sheet->setCellValue('F1', 'E-Mail');
            $sheet->setCellValue('G1', 'Data de nascimento');
            $sheet->setCellValue('H1', 'Sexo');
            
            $spreadsheet->getSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);
            $spreadsheet->getSheet(0)->setSelectedCell('A1');
            
           for($i = 0; $i < sizeof($alunosResult); $i++){
                $sheet->setCellValue('A'.($i + 2), $alunosResult[$i]["TURMA"]);
                $sheet->setCellValue('B'.($i + 2), $alunosResult[$i]["MATERIA"]);
                $sheet->setCellValue('C'.($i + 2), $alunosResult[$i]["PROFESSOR"]);
                $sheet->setCellValue('D'.($i + 2), $alunosResult[$i]["NOME"]);
                $sheet->setCellValue('E'.($i + 2), $alunosResult[$i]["SOBRENOME"]);
                $sheet->setCellValue('F'.($i + 2), $alunosResult[$i]["EMAIL"]);
                $date = date_create($alunosResult[$i]["DATA_NASCIMENTO"]);
                $sheet->setCellValue('G'.($i + 2), date_format($date, 'd/m/Y'));
                $sheet->setCellValue('H'.($i + 2), $alunosResult[$i]["SEXO"]);
            } 
            foreach(range('A',$sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            $spreadsheet->removeSheetByIndex(1);
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
            
        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="Relatório de turmas.xlsx"');
        $streamedResponse->send();
        exit;
    }
}

?>
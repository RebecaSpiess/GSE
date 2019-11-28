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
            $db0 = new db();
            $alunos = $db->query('SELECT * FROM PESSOA WHERE TIPO_PESSOA = 3');
            $alunosResult = $alunos->fetchAll();
            
            $spreadsheet = new Spreadsheet();
            $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Alunos');
            $spreadsheet->addSheet($sheet, 0);
            $sheet->setCellValue('A1', 'Nome');
            $sheet->setCellValue('B1', 'Sobrenome');
            $sheet->setCellValue('C1', 'E-Mail');
            $sheet->setCellValue('D1', 'Data de nascimento');
            $sheet->setCellValue('E1', 'Sexo');
            $sheet->setCellValue('F1', 'Notas');
            
            $spreadsheet->getSheet(0)->getStyle('A1:F1')->getFont()->setBold(true); //Deixa negrito
            $spreadsheet->getSheet(0)->setSelectedCell('A1');
            
            for($i = 0; $i < sizeof($alunosResult); $i++){
                $sheet->setCellValue('A'.($i + 2), $alunosResult[$i]["NOME"]);
                $sheet->setCellValue('B'.($i + 2), $alunosResult[$i]["SOBRENOME"]);
                $sheet->setCellValue('C'.($i + 2), $alunosResult[$i]["EMAIL"]);
                $alunoId = $alunosResult[$i]["ID"];
                
                $date = date_create($alunosResult[$i]["DATA_NASCIMENTO"]);
                $sheet->setCellValue('D'.($i + 2), date_format($date, 'd/m/Y'));
                
                $sexo_db = $alunosResult[$i]["TIPO_SEXO"];
                if ($sexo_db == 0){
                    $sheet->setCellValue('E'.($i + 2), 'Feminino');
                } else {
                    $sheet->setCellValue('E'.($i + 2), 'Masculino');
                }
                $columnNotas=6;
                $notas_alunos = $db0->query("SELECT NOTA, DESCRICAO FROM NOTAS WHERE ID_PESSOA = ?", $alunoId)->fetchAll();
                for($j = 0; $j < sizeof($notas_alunos); $j++){
                    $sheet->setCellValueByColumnAndRow($columnNotas, $i, $notas_alunos[$j]["NOTA"]);
                    $columnNotas++;
                }
                
                $db0->close();
                $db0 = new db();
            }
            foreach(range('A',$sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true); // Ajuste de colunas
            }
            $db0->close();
            //Baixar excel
            
            $spreadsheet->removeSheetByIndex(1);
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            
            $db->close();
        });
        
        //Cabeçalho navegador
        
        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="Relatório de alunos.xlsx"');
        $streamedResponse->send();
        exit;
    }
}

?>
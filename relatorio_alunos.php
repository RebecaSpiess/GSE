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
            $sheet->setCellValue('A1', 'Aluno');
            $sheet->setCellValue('B1', 'Nome');
            $sheet->setCellValue('C1', 'Sobrenome');
            $sheet->setCellValue('D1', 'E-Mail');
            $sheet->setCellValue('E1', 'Data de nascimento');
            $sheet->setCellValue('F1', 'Sexo');
            
            $spreadsheet->getSheet(0)->getStyle('A1:F1')->getFont()->setBold(true); //Deixa negrito
            $spreadsheet->getSheet(0)->setSelectedCell('A1');
            
            $aluno_id_count = 1;
            for($i = 0; $i < sizeof($alunosResult); $i++){
                $sheet->setCellValue('A'.($i + 2), $alunosResult[$i]["ID"]);
                $sheet->setCellValue('B'.($i + 2), $alunosResult[$i]["NOME"]);
                $sheet->setCellValue('C'.($i + 2), $alunosResult[$i]["SOBRENOME"]);
                $sheet->setCellValue('D'.($i + 2), $alunosResult[$i]["EMAIL"]);
                
                $date = date_create($alunosResult[$i]["DATA_NASCIMENTO"]);
                $sheet->setCellValue('E'.($i + 2), date_format($date, 'd/m/Y'));
                
                $sexo_db = $alunosResult[$i]["TIPO_SEXO"];
                if ($sexo_db == 0){
                    $sheet->setCellValue('F'.($i + 2), 'Feminino');
                } else {
                    $sheet->setCellValue('F'.($i + 2), 'Masculino');
                }
                $aluno_id_count++;
            }
            foreach(range('A',$sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true); // Ajuste de colunas
            }
           
            //Baixar excel
            $id = 1;
            for($i = 0; $i < sizeof($alunosResult); $i++){
                $columnNotas = 1;
                $sheet_novo = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet,'Aluno ' . $id);
                $spreadsheet->addSheet($sheet_novo, $i + 1);
                $spreadsheet->setActiveSheetIndex($id);
                $alunoId = $alunosResult[$i]['ID'];
                $notas_alunos = $db0->query("SELECT NOTA, DATA, DESCRICAO FROM NOTAS WHERE ID_PESSOA = ?", $alunoId)->fetchAll();
                $sheet_novo->setCellValue('A1', 'Nota');
                $sheet_novo->setCellValue('B1', 'Data');
                $sheet_novo->setCellValue('C1', 'Descrição');
                $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true); //Deixa negrito
                $linha = 2;
                $columnNotas = 1;
                for($j = 0; $j < sizeof($notas_alunos); $j++){
                    $sheet_novo->setCellValueByColumnAndRow($columnNotas, $linha, $notas_alunos[$j]["NOTA"]);
                    $sheet_novo->setCellValueByColumnAndRow($columnNotas + 1, $linha, $notas_alunos[$j]["DATA"]);
                    $sheet_novo->setCellValueByColumnAndRow($columnNotas + 2, $linha, trim($notas_alunos[$j]["DESCRICAO"]));
                    $linha++;
                }
                
                $db0->close();
                $db0 = new db();
                $id++;
                foreach(range('A','C') as $column) {
                    $sheet_novo->getColumnDimension($column)->setAutoSize(true); // Ajuste de colunas
                }
                $spreadsheet->getActiveSheet()->setSelectedCell('A1');
            }
            $db0->close();
            
            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getSheet(0)->setSelectedCell('A1');
            $spreadsheet->removeSheetByIndex($id);
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
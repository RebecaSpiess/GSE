<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class relatorio_alunos_cadastro_turmas
{

    private $id_pessoa;

    private $tipo_pessoa;
    
    function __construct($id_pessoa, $tipo_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
        $this->tipo_pessoa = $tipo_pessoa;
    }

    function gerarRelatorio()
    {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(
            function () {

                $db = new db();
               
                $sqlAlunos = 'select pe.ID, pe.NOME, pe.SOBRENOME, pe.DATA_NASCIMENTO, sex.SEXO from PESSOA pe join SEXO sex ON (sex.ID = pe.TIPO_SEXO) where pe.TIPO_PESSOA = 3 order by NOME, SOBRENOME';
                $alunos = $db->query($sqlAlunos);
                
                $alunosResult = $alunos->fetchAll();

                $spreadsheet = new Spreadsheet();
                $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Alunos');
                $spreadsheet->addSheet($sheet, 0);
                $sheet->setCellValue('A1', 'ID do Aluno');
                $sheet->setCellValue('B1', 'Nome');
                $sheet->setCellValue('C1', 'Sobrenome');
                $sheet->setCellValue('D1', 'Data de nascimento');
                $sheet->setCellValue('E1', 'Sexo');

                $spreadsheet->getSheet(0)
                    ->getStyle('A1:E1')
                    ->getFont()
                    ->setBold(true); // Deixa negrito
                $spreadsheet->getSheet(0)
                    ->setSelectedCell('A1');

                $aluno_id_count = 1;
                for ($i = 0; $i < sizeof($alunosResult); $i ++) {
                    $sheet->setCellValue('A' . ($i + 2), $alunosResult[$i]["ID"]);
                                                     
                    
                    $sheet->setCellValue('B' . ($i + 2), $alunosResult[$i]["NOME"]);
                    $sheet->setCellValue('C' . ($i + 2), $alunosResult[$i]["SOBRENOME"]);

                    $date = date_create($alunosResult[$i]["DATA_NASCIMENTO"]);
                    $sheet->setCellValue('D' . ($i + 2), date_format($date, 'd/m/Y'));

                    $sheet->setCellValue('E' . ($i + 2), $alunosResult[$i]["SEXO"]);
                    $aluno_id_count ++;
                }
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)
                        ->setAutoSize(true); // Ajuste de colunas
                }

                $styleArray = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array(
                                'argb' => '000000'
                            )
                        )
                    )
                );

                $termino_bordas = "E" . $aluno_id_count;
                $range_borda = "A1:" . $termino_bordas;
                $spreadsheet->getActiveSheet()
                    ->getStyle($range_borda)
                    ->applyFromArray($styleArray);

                $spreadsheet->getActiveSheet()
                    ->setSelectedCell('A1');

               
                $spreadsheet->setActiveSheetIndex(0);
                $spreadsheet->getSheet(0)
                    ->setSelectedCell('A1');
                $spreadsheet->removeSheetByIndex(1);
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');

                $db->close();
            });

        // Cabeçalho navegador

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="Relatório de alunos.xlsx"');
        $streamedResponse->send();
        exit();
    }
}

?>
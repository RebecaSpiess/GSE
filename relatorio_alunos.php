<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;

class relatorio_alunos
{

    private $id_pessoa;

    private $tipo_pessoa;
    
    private $id_turma;

    function __construct($id_pessoa, $tipo_pessoa, $id_turma)
    {
        $this->id_pessoa = $id_pessoa;
        $this->tipo_pessoa = $tipo_pessoa;
        $this->id_turma = $id_turma;
    }

    function gerarRelatorio()
    {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(
            function () {

                $db = new db();
                if (isset($this->id_turma)){
                    $sqlAlunos = 'SELECT p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO FROM TURMA_PESSOA tp JOIN PESSOA p ON (p.ID = tp.ID_PESSOA) JOIN TURMA_MATERIA tm on (tm.ID_TURMA = tp.ID_TURMA) WHERE p.TIPO_PESSOA = 3  AND tp.ID_TURMA = ' . $this->id_turma . ' GROUP BY p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO ORDER BY p.NOME, p.SOBRENOME';
                    if ($this->tipo_pessoa == 1) {
                        $sqlAlunos = 'SELECT p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO FROM TURMA_PESSOA tp JOIN PESSOA p ON (p.ID = tp.ID_PESSOA) JOIN TURMA_MATERIA tm on (tm.ID_TURMA = tp.ID_TURMA) WHERE p.TIPO_PESSOA = 3 and tm.ID_PROFESSOR = ' . $this->id_pessoa . ' AND tp.ID_TURMA = ' . $this->id_turma . ' GROUP BY p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO ORDER BY p.NOME, p.SOBRENOME';
                    }
                    $alunos = $db->query($sqlAlunos);
                } else {
                    $sqlAlunos = 'SELECT p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO FROM TURMA_PESSOA tp JOIN PESSOA p ON (p.ID = tp.ID_PESSOA) JOIN TURMA_MATERIA tm on (tm.ID_TURMA = tp.ID_TURMA) WHERE p.TIPO_PESSOA = 3 GROUP BY p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO ORDER BY p.NOME, p.SOBRENOME';
                    if ($this->tipo_pessoa == 1) {
                        $sqlAlunos = 'SELECT p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO FROM TURMA_PESSOA tp JOIN PESSOA p ON (p.ID = tp.ID_PESSOA) JOIN TURMA_MATERIA tm on (tm.ID_TURMA = tp.ID_TURMA) WHERE p.TIPO_PESSOA = 3 and tm.ID_PROFESSOR = ' . $this->id_pessoa . ' GROUP BY p.ID, p.NOME, p.SOBRENOME, p.TIPO_SEXO, p.DATA_NASCIMENTO ORDER BY p.NOME, p.SOBRENOME';
                    }
                    $alunos = $db->query($sqlAlunos);
                }
                
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
                    
                    $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, ($i + 2))->getHyperlink()->setUrl("#'Aluno " . $alunosResult[$i]["ID"] . "'!A1");
                    
                    
                    $sheet->setCellValue('B' . ($i + 2), $alunosResult[$i]["NOME"]);
                    $sheet->setCellValue('C' . ($i + 2), $alunosResult[$i]["SOBRENOME"]);

                    $date = date_create($alunosResult[$i]["DATA_NASCIMENTO"]);
                    $sheet->setCellValue('D' . ($i + 2), date_format($date, 'd/m/Y'));

                    $sexo_db = $alunosResult[$i]["TIPO_SEXO"];
                    if ($sexo_db == 0) {
                        $sheet->setCellValue('E' . ($i + 2), 'Feminino');
                    } else {
                        $sheet->setCellValue('E' . ($i + 2), 'Masculino');
                    }
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

                // Baixar excel
                $id = 1;

                for ($i = 0; $i < sizeof($alunosResult); $i ++) {
                    $idLinha = 1;
                    $columnNotas = 1;
                    $sheet_novo = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Aluno ' . $alunosResult[$i]['ID']);
                    $spreadsheet->addSheet($sheet_novo, $i + 1);
                    $spreadsheet->setActiveSheetIndex($id);
                    $alunoId = $alunosResult[$i]['ID'];

                    $db00 = new db();
                    try {
                        $materia_aluno_fetch = $db00->query("SELECT distinct m.NOME, m.ID MATERIA_ID, t.ID TURMA_ID  FROM TURMA_PESSOA tp JOIN TURMA t on (t.ID = tp.ID_TURMA) JOIN TURMA_MATERIA tm on (tm.ID_TURMA = t.ID) JOIN MATERIA m on (m.ID = tm.ID_MATERIA) WHERE ID_PESSOA = ? ORDER BY m.NOME", $alunoId)
                            ->fetchAll();
                        error_log("CHEGOU ATÉ AQUI A!");
                        foreach ($materia_aluno_fetch as $materia_aluno_fetch_single) {
                            error_log("CHEGOU ATÉ AQUI B!");
                            $colunaId = 1;
                            $id_linha_titulo_materia = $idLinha;

                            $coluna_convertida = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colunaId);
                            $inicio_bordas = $coluna_convertida . $idLinha;

                            $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha, $materia_aluno_fetch_single['NOME']);
                            $spreadsheet->getActiveSheet()
                                ->getStyleByColumnAndRow($colunaId, $idLinha)
                                ->getFont()
                                ->setBold(true);
                            $spreadsheet->getActiveSheet()
                                ->getStyleByColumnAndRow($colunaId, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');
                            $idLinha ++;
                            $db01 = new db();
                            try {
                                error_log("CHEGOU ATÉ AQUI C!");
                                $materia_aluno_turma_nota = $db01->query("select DATE_FORMAT(n.DATA,'%d-%m-%Y') DATA, n.INSTRUMENTO_AVALIACAO, np.NOTA from NOTA_PESSOA np join NOTAS n ON (n.ID = np.ID_NOTA) where n.ID_TURMA = ? and n.ID_MATERIA = ? and np.ID_PESSOA = ? order by n.DATA", $materia_aluno_fetch_single['TURMA_ID'], $materia_aluno_fetch_single['MATERIA_ID'], $alunoId)
                                    ->fetchAll();
                                $primeira_nota = null;
                                $ultima_nota = null;
                                $alunoPossuiNotas = false;
                                foreach ($materia_aluno_turma_nota as $materia_aluno_turma_nota_single) {
                                    error_log("CHEGOU ATÉ AQUI D!");
                                    
                                    $idLinha_turma_materia = $idLinha;
                                    $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha_turma_materia ++, $materia_aluno_turma_nota_single['DATA']);
                                    $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha_turma_materia ++, $materia_aluno_turma_nota_single['INSTRUMENTO_AVALIACAO']);

                                    $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha_turma_materia, $materia_aluno_turma_nota_single['NOTA']);
                                    if (! isset($primeira_nota)) {
                                        $coluna_convertida = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colunaId);
                                        $primeira_nota = $coluna_convertida . $idLinha_turma_materia;
                                    }

                                    $coluna_convertida = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colunaId);
                                    $ultima_nota = $coluna_convertida . $idLinha_turma_materia;

                                    $colunaId ++;
                                    $idLinha_turma_materia ++;
                                    $alunoPossuiNotas = true;
                                }
                                $idLinha_turma_materia = $idLinha;
                                $idLinha_turma_materia ++;
                                
                                if ($alunoPossuiNotas){
                                    $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha_turma_materia, 'Média');
                                    $spreadsheet->getActiveSheet()
                                    ->getStyleByColumnAndRow($colunaId, $idLinha_turma_materia ++)
                                    ->getFont()
                                    ->setBold(true);
                                    $formula = '=AVERAGE(' . $primeira_nota . ':' . $ultima_nota . ')';
                                    $sheet_novo->setCellValueByColumnAndRow($colunaId, $idLinha_turma_materia, $formula);
                                } 
                              


                                $coluna_convertida = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colunaId);
                                $termino_bordas = $coluna_convertida . $idLinha_turma_materia;

                                $range_borda = $inicio_bordas . ":" . $termino_bordas;

                                $colunaId ++;
                                $idLinha_turma_materia ++;

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

                                $spreadsheet->getActiveSheet()
                                    ->getStyle($range_borda)
                                    ->applyFromArray($styleArray);

                                $idLinha ++;
                                $idLinha ++;
                                $idLinha ++;
                                $idLinha ++;
                            } finally {
                                $db01->close();
                            }
                            $spreadsheet->getActiveSheet()
                                ->mergeCellsByColumnAndRow(1, $id_linha_titulo_materia, ($colunaId - 1), $id_linha_titulo_materia);
                        }
                        foreach (range('A', 'Z') as $column) {
                            $sheet_novo->getColumnDimension($column)
                                ->setAutoSize(true); // Ajuste de colunas
                        }
                    } finally {
                        $db00->close();
                    }

                    $ocorrenciaCell = "A".$idLinha;
                    $sheet_novo->setCellValueByColumnAndRow(1, $idLinha, 'Ocorrências');
                    $sheet_novo->getStyleByColumnAndRow(1, $idLinha)
                        ->getFont()
                        ->setBold(true);
                    $sheet_novo->getStyleByColumnAndRow(1, $idLinha)
                        ->getAlignment()
                        ->setHorizontal('center');

                    $spreadsheet->getActiveSheet()
                        ->mergeCellsByColumnAndRow(1, $idLinha, 8, $idLinha ++);

                    $db4 = new db();
                    try {
                        $ocorrencia_aluno_fetch = $db4->query("SELECT tp.NOME AS 'TIPO', oc.DATA, CONCAT(CONCAT(pe.NOME, ' '), pe.SOBRENOME) 'AUTOR', oc.DESCRICAO, (CASE WHEN oc.ATENDIMENTO_ESPECIAL = 1 THEN 'Sim' ELSE 'Não' END) as AEE FROM OCORRENCIA oc
                JOIN PESSOA pe ON (pe.ID = oc.ID_PESSOA_AUTOR)
                JOIN TIPO_OCORRENCIA tp ON (tp.ID = oc.ID_TIPO)
                WHERE ID_PESSOA_ALUNO = ?
                order by tp.PRIORIDADE, AUTOR, oc.DATA", $alunoId)
                            ->fetchAll();
                        if (sizeof($ocorrencia_aluno_fetch) > 0) {
                            
                            $sheet_novo->setCellValueByColumnAndRow(1, $idLinha, 'Tipo');
                            $sheet_novo->getStyleByColumnAndRow(1, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');

                            $sheet_novo->setCellValueByColumnAndRow(2, $idLinha, 'Autor');
                            $sheet_novo->getStyleByColumnAndRow(2, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');

                            $sheet_novo->setCellValueByColumnAndRow(3, $idLinha, 'Data');
                            $sheet_novo->getStyleByColumnAndRow(3, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');
                            
                                $sheet_novo->setCellValueByColumnAndRow(4, $idLinha, 'AEE');
                                $sheet_novo->getStyleByColumnAndRow(4, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');

                            $sheet_novo->setCellValueByColumnAndRow(5, $idLinha, 'Descrição');
                            $sheet_novo->getStyleByColumnAndRow(5, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');

                            $spreadsheet->getActiveSheet()
                                ->mergeCellsByColumnAndRow(5, $idLinha, 8, $idLinha);

                            $idLinha ++;

                            foreach ($ocorrencia_aluno_fetch as $ocorrencia_aluno_single) {
                                $colunaId = 1;
                                $sheet_novo->setCellValueByColumnAndRow($colunaId ++, $idLinha, $ocorrencia_aluno_single['TIPO']);
                                $sheet_novo->setCellValueByColumnAndRow($colunaId ++, $idLinha, $ocorrencia_aluno_single['AUTOR']);
                                $sheet_novo->setCellValueByColumnAndRow($colunaId ++, $idLinha, $ocorrencia_aluno_single['DATA']);
                                $sheet_novo->setCellValueByColumnAndRow($colunaId ++, $idLinha, $ocorrencia_aluno_single['AEE']);
                                $sheet_novo->setCellValueByColumnAndRow($colunaId ++, $idLinha, $ocorrencia_aluno_single['DESCRICAO']);
                                $spreadsheet->getActiveSheet()
                                ->mergeCellsByColumnAndRow(5, $idLinha, 8, $idLinha);
                                $idLinha ++;
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
                            $range_borda = $ocorrenciaCell . ":H".($idLinha-1);
                            $sheet_novo->getStyle($range_borda)->applyFromArray($styleArray);
                            
                        } else {
                            $sheet_novo->setCellValueByColumnAndRow(1, $idLinha, 'Não possui ocorrências cadastradas!');
                            $sheet_novo->getStyleByColumnAndRow(1, $idLinha)
                                ->getAlignment()
                                ->setHorizontal('center');
                            $spreadsheet->getActiveSheet()
                                ->mergeCellsByColumnAndRow(1, $idLinha, 8, $idLinha ++);
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
                                $range_borda = $ocorrenciaCell . ":H".($idLinha-1);
                                $sheet_novo->getStyle($range_borda)->applyFromArray($styleArray);
                        }
                    } finally {
                        $db4->close();
                    }

                    
                    $id ++;
                    $spreadsheet->getActiveSheet()
                        ->setSelectedCell('A1');
                }
                $spreadsheet->setActiveSheetIndex(0);
                $spreadsheet->getSheet(0)
                    ->setSelectedCell('A1');
                $spreadsheet->removeSheetByIndex($id);
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
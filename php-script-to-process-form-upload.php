<?php

require 'database/db.php';

$erros=array();
$registroExistente = false;
$nome_turma=$_POST['nome_turma'];
$id_professor_regente=$_POST['professor_responsavel'];

$db00 = new db();
try {
    $materias = $db00->query("SELECT ID FROM MATERIA ORDER BY ID")->fetchAll();
} finally {
    $db00->close();
}

$materiaProfessorArray = array();
foreach ($materias as $materia){
    $materiaId = $materia['ID'];
    $nomeOption = "professor_disciplina_" . $materiaId;
    if (isset($_POST[$materiaId]) and isset($_POST[$nomeOption])){
        $idProfessorSelecionado = $_POST[$nomeOption];
        $materiaProfessorArray[$materiaId] = $idProfessorSelecionado;
    }
}



if (isset($nome_turma)){
    $db = new db();
    try {
        $result = $db->query("SELECT ID FROM TURMA WHERE NOME_TURMA = ?",$nome_turma)->numRows();
        if ($result == 1) {
            array_push($erros, "O nome de turma <b>" . $nome_turma . "</b> já existe!");
            $registroExistente = true;
        }
    } finally {
        $db->close();
    }
} else {
    array_push($erros, "Nome da turma não foi preenchido!");
}

if (!$registroExistente){
    $csv_mimetypes = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt');
    if (in_array($_FILES['csvfile']['type'], $csv_mimetypes)) {
        $filePath = "C:\Users\spies\Documents\arquivos_CSV";
        
        $inipath = php_ini_loaded_file();
        $app_properties = parse_ini_file($inipath, false);
        
        $caminho_property = $app_properties['file.path.upload.alunos'];
        if (isset($caminho_property) and !empty(trim($caminho_property))){
            $filePath = $caminho_property;
        }
        error_log("FP:" . $filePath);
        
        $token = date("YmdHis");
        $rawCSV = file_get_contents($_FILES['csvfile']['tmp_name']);
        $fileCSV = fopen($filePath . "/" . $token . ".csv", "w");
        fwrite($fileCSV, $rawCSV);
        fclose($fileCSV);
        $rawCSV = fopen($filePath . "/" . $token . ".csv", "r");
        $alunosCadastroExcel = array();
        $alunosCadastradosNoBanco = array();
        if ($rawCSV) {
            while (($buffer = fgetcsv($rawCSV, 4096,";")) !== false) {
                $valorPrimeiraColuna = $buffer[0];
                if (strtoupper(trim($valorPrimeiraColuna)) != "ID DO ALUNO"){
                    array_push($alunosCadastroExcel,$valorPrimeiraColuna);
                }
            }
            if (!feof($rawCSV)) {
                echo "Erro: falha inexperada de fgets()\n";
            }
            fclose($rawCSV);
        }
        $informacaoListaDeclarada = false;
        
        for ($i = 0; $i < sizeof($alunosCadastroExcel); $i++) {
            $alunoCadastro = $alunosCadastroExcel[$i];
            if (alunoCadastrado($alunoCadastro)){
                array_push($alunosCadastradosNoBanco, $alunoCadastro);
            } else {
                if (!$informacaoListaDeclarada){
                    array_push($erros, "Os seguintes IDs de alunos não estão cadastrados no sistema:<br>");
                    array_push($erros, $alunoCadastro . ", ");
                    $informacaoListaDeclarada = true;
                } else {
                    array_push($erros, $alunoCadastro . ", ");
                }
            }
        }
            
        
        try{
            $db2 = new db();
            $db3 = new db();
            $db4 = new db();
            
            if (sizeof($alunosCadastradosNoBanco) > 0){
                $result = $db2->query("INSERT INTO TURMA (ID_PESSOA_PROFESSOR_REGENTE, NOME_TURMA)
                                  VALUES (?,?)", $id_professor_regente, $nome_turma)->query_count;
                if ($result == 1) {
                    $selectId = $db2->query("SELECT t.ID FROM TURMA t WHERE t.NOME_TURMA = ?", $nome_turma);
                    $contador = $selectId->numRows();
                    $resultadoSelect = $selectId->fetchAll();
                    if ($contador > 0) {
                        $id_turma = $resultadoSelect[0]['ID'];
                        foreach ($alunosCadastradosNoBanco as $alunosValidadosNoBanco){
                            $db3->query("INSERT INTO TURMA_PESSOA (ID_TURMA, ID_PESSOA)
                                  VALUES (?,?)", $id_turma, $alunosValidadosNoBanco);
                        }
                        foreach ($materiaProfessorArray as $key => $value) {
                            $db4->query("INSERT INTO TURMA_MATERIA (ID_TURMA, ID_MATERIA, ID_PROFESSOR)
                                  VALUES (?,?,?)", $id_turma, $key, $value);
                        }
                    }
                }
            }
        } finally {
            $db2->close();
            $db3->close();
            $db4->close();
        }
    }
}

session_start();
if (!empty($erros)){
    $_SESSION['errosCadastroTurma'] = $erros;
} else {
    $_SESSION['sucessoCadastroTurma'] = true;
}
header("Location: turma_cadastro.php");

function alunoCadastrado($idAluno){
    $db = new db();
    try {
        $result = $db->query("SELECT p.ID FROM PESSOA p WHERE p.ID = ?",$idAluno)->numRows();
        if ($result == 1) {
            return true;
        }
    } finally {
        $db->close();
    }
    return false;
}
?>
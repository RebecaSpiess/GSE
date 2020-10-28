<?php

require 'database/db.php';


$turmaId = $_POST['turmaId'];

$nome_turma=$_POST['nome_turma'];
$id_professor_regente=$_POST['professor_responsavel'];
$result = 0;

$db0 = new db();
try{
    $result = $db0->query("UPDATE TURMA SET NOME_TURMA=?, ID_PESSOA_PROFESSOR_REGENTE=? WHERE ID=?", $nome_turma, $id_professor_regente, $turmaId)->query_count;
} finally {
    $db0->close();
}

$db1 = new db();
try{
    $result = $db1->query("DELETE FROM TURMA_MATERIA WHERE ID_TURMA=?", $turmaId)->query_count;
} finally {
    $db1->close();
}


$db2 = new db();
try {
    $materias = $db2->query("SELECT ID FROM MATERIA ORDER BY ID")->fetchAll();
    foreach ($materias as $materia){
        $materiaId = $materia['ID'];
        if (isset($_POST[$materiaId])){
            $campoProfessorMateria = "professor_disciplina_" . $materiaId;
            if (isset($_POST[$campoProfessorMateria])){
                $idProfessor = $_POST[$campoProfessorMateria];
                $db3 = new db();
                $result = $db3->query("INSERT INTO TURMA_MATERIA (ID_TURMA, ID_MATERIA, ID_PROFESSOR) VALUES (?,?,?)",
                    $turmaId, $materiaId, $idProfessor)->query_count;
                $db3->close();
            }
        }
    }
} finally {
    $db2->close();
}

if ($result == 1){
    session_start();
    $_SESSION['atualizarTurmaMensagem'] = 'Turma alterada com sucesso';
    header("Location: turma_visualizar.php");
} 

?>
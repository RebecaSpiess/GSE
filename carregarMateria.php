<?php

require 'database/db.php';

if(!empty($_POST['turma_id'])){
    $turma_id = $_POST['turma_id'];
    $db0 = new db();
    try {
        $materiaFetch = $db0->query("SELECT ma.ID, ma.NOME FROM MATERIA ma
	                                   JOIN TURMA_MATERIA tm ON (tm.ID_MATERIA = ma.ID)
                                       JOIN TURMA t ON (t.ID = tm.ID_TURMA)
                                       WHERE t.ID = ?
                                       ORDER BY ma.NOME DESC", $turma_id)->fetchAll();
        $result = "";
        foreach ($materiaFetch as $single_row) {
            $result .= "<option value=\"" . $single_row['ID'] . "\">" . $single_row['NOME'] .  "</option>";
        }
        echo $result;
    } finally {
        $db0->close();
    }
};

?>
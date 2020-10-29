<?php

require 'database/db.php';

if(!empty($_POST['turma_id'])){
    $turma_id = $_POST['turma_id'];
    $db0 = new db();
    try {
        $sql = "SELECT distinct ma.ID, ma.NOME FROM NOTAS n
                                        JOIN TURMA t ON (t.ID = n.ID_TURMA)
                                        JOIN MATERIA ma ON (ma.ID = n.ID_MATERIA)
                                        WHERE t.ID = ?
                                        ORDER BY ma.NOME";
        $materiaFetch = $db0->query($sql, $turma_id)->fetchAll();
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
<?php

require 'database/db.php';

if(!empty($_POST['turma_id'])){
    $turma_id = $_POST['turma_id'];
    $db0 = new db();
    try {
        $sql = "SELECT distinct m.ID, m.NOME FROM FREQUENCIA f
                    JOIN TURMA t ON (t.ID = f.ID_TURMA)
                    JOIN MATERIA m ON (f.ID_MATERIA = m.ID) 
                    order by m.NOME";
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
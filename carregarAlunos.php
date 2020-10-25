<?php

require 'database/db.php';

if(!empty($_POST['turma_id'])){
    $turma_id = $_POST['turma_id'];
    $db0 = new db();
    try {
        $alunosFetch = $db0->query("SELECT pessoa.ID, pessoa.NOME, pessoa.SOBRENOME, pessoaResp.EMAIL FROM TURMA_PESSOA turmaPessoa
        JOIN PESSOA pessoa ON (pessoa.ID = turmaPessoa.ID_PESSOA)
        JOIN PESSOA pessoaResp ON (pessoa.RESPONSAVEL_1 = pessoaResp.ID)
        JOIN TIPO_PESSOA tipoPessoa ON (tipoPessoa.ID = pessoa.TIPO_PESSOA and tipoPessoa.NOME = 'Aluno(a)')
        WHERE turmaPessoa.ID_TURMA = ?
        ORDER BY pessoa.NOME, pessoa.SOBRENOME", $turma_id)->fetchAll();
        $result = "";
        foreach ($alunosFetch as $single_row) {
            $result .= "<option value=\"" . $single_row['ID'] . "\">" . $single_row['NOME'] . ' ' . $single_row['SOBRENOME'] . ' - ' . $single_row['EMAIL'].  "</option>";
        }
        echo $result;
    } finally {
        $db0->close();
    }
};

?>
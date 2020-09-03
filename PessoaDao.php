<?php

use model\Pessoa;

class PessoaDao
{

    
    
    function adicionar($pessoa){
        try {
        
            $db = new db();
            $db2 = new db();
            $result = $db->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA, CPF, TELEFONE, RESPONSAVEL_1, RESPONSAVEL_2)
                          VALUES (?,?,?,?,?,?,?,?,?,?,?)", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $pessoa->senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2)->query_count;
            if ($result == 1) {
                $selectId = $db2->query("SELECT p.ID FROM PESSOA p WHERE p.EMAIL = ?", $pessoa->email);
                $contador = $selectId->numRows();
                $resultadoSelect = $selectId->fetchAll();
                if ($contador > 0) {
                    $pessoa->id = $resultadoSelect[0]['ID'];
                    error_log($pessoa->id);
                }
            }
        } finally {
            $db->close();
            $db2->close();
        }
        return false;
    }
    
}


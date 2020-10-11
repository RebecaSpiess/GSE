<?php

use model\Turma;

class PessoaDao
{

    
    
    function adicionar($turma){
        $db = new db();
        $db1 = new db();
        $db2 = new db();
        $db3 = new db();
        $inserir = true;
        try {
            if ($sobrescrita){
                $selectId = $db1->query("SELECT p.ID FROM PESSOA p WHERE p.EMAIL = ?", $pessoa->email);
                $contador = $selectId->numRows();
                $resultadoSelect = $selectId->fetchAll();
                if ($contador > 0) {
                    $db3->query("UPDATE PESSOA SET NOME=?, SOBRENOME=?, DATA_NASCIMENTO=?, TIPO_SEXO=?, SENHA=?, CPF=?, TELEFONE=?, RESPONSAVEL_1=?, RESPONSAVEL_2=? WHERE EMAIL = ?",
                        $pessoa->nome, $pessoa->sobrenome, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2, $pessoa->email)->query_count;
                    $inserir = false;
                    $pessoa->id = $resultadoSelect[0]['ID'];
                    error_log("ID: " . $pessoa->id);
                }
            } 
            if ($inserir){
                $result = $db->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA, CPF, TELEFONE, RESPONSAVEL_1, RESPONSAVEL_2)
                              VALUES (?,?,?,?,?,?,?,?,?,?,?)", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $pessoa->senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2)->query_count;
                if ($result == 1) {
                    $selectId = $db2->query("SELECT p.ID FROM PESSOA p WHERE p.EMAIL = ?", $pessoa->email);
                    $contador = $selectId->numRows();
                    $resultadoSelect = $selectId->fetchAll();
                    if ($contador > 0) {
                        $pessoa->id = $resultadoSelect[0]['ID'];
                        error_log("ID: " . $pessoa->id);
                    }
                }
            }
        } finally {
            $db->close();
            $db1->close();
            $db2->close();
            $db3->close();
        }
        return false;
    }
    
    function adicionarServidor($pessoa){
        $db = new db();
        $db1 = new db();
        $db2 = new db();
        $db3 = new db();
        $inserir = true;
        try {
           $selectId = $db1->query("SELECT p.ID FROM PESSOA p WHERE p.EMAIL = ?", $pessoa->email);
           $contador = $selectId->numRows();
           $resultadoSelect = $selectId->fetchAll();
           if ($contador > 0) {
               $db3->query("UPDATE PESSOA SET NOME=?, SOBRENOME=?, DATA_NASCIMENTO=?, TIPO_SEXO=?, SENHA=?, CPF=?, TELEFONE=?, RESPONSAVEL_1=?, RESPONSAVEL_2=?, TIPO_PESSOA=? WHERE EMAIL = ?",
                   $pessoa->nome, $pessoa->sobrenome, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2, $pessoa->tipo_pessoa, $pessoa->email)->query_count;
               $inserir = false;
               $pessoa->id = $resultadoSelect[0]['ID'];
               return true;
            }
            if ($inserir){
                $result = $db->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA, CPF, TELEFONE, RESPONSAVEL_1, RESPONSAVEL_2)
                              VALUES (?,?,?,?,?,?,?,?,?,?,?)", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $pessoa->senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2)->query_count;
                if ($result == 1) {
                    $selectId = $db2->query("SELECT p.ID FROM PESSOA p WHERE p.EMAIL = ?", $pessoa->email);
                    $contador = $selectId->numRows();
                    $resultadoSelect = $selectId->fetchAll();
                    if ($contador > 0) {
                        $pessoa->id = $resultadoSelect[0]['ID'];
                        error_log("ID: " . $pessoa->id);
                        return true;
                    }
                }
            }
        } finally {
            $db->close();
            $db1->close();
            $db2->close();
            $db3->close();
        }
        return false;
    }
    
}


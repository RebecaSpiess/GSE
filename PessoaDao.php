<?php

use model\Pessoa;

class PessoaDao
{

    const SENHA = '123456';
    
    function adicionar($pessoa){
        $db = new db();
        
        try {
            $enc_senha = hash('sha512', self::SENHA . 'GSE');
            $result = $db->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA)
                          VALUES (?,?,?,?,?,?,?) ", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $enc_senha)->query_count;
            if ($result == 1) {
                return true;
            }
        } finally {
            $db->close();
        }
        return false;
    }
    
}


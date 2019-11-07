<?php
namespace bo;

require 'model/Pessoa.php';

class ControleAcesso
{
    public static function validar($papeisPermitidos) {
        if (!isset($_SESSION['loggedGSEUser'])){
            $log = "[CONTROLE DE ACESSO] Sessão é inválida!";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: login.php");
        } else {
            $pessoa = unserialize($_SESSION['loggedGSEUser']);
            $tipoPessoaId = $pessoa->tipo_pessoa;
            foreach ($papeisPermitidos as $value){
                echo $value;
                echo $tipoPessoaId;
                if ($tipoPessoaId == $value){
                    return;
                }
            }
            
            unset ($_SESSION['loggedGSEUser']);
            session_destroy();
            $log = "[CONTROLE DE ACESSO] Acesso negado!";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: erro_403.php");
        }
    }
    
    public static function validarPapelFuncao($papeisPermitidos) {
        if (!isset($_SESSION['loggedGSEUser'])){
            $log = "[CONTROLE DE ACESSO] Sessão é inválida!";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: login.php");
        } else {
            $pessoa = unserialize($_SESSION['loggedGSEUser']);
            $tipoPessoaId = $pessoa->tipo_pessoa;
            foreach ($papeisPermitidos as $value){
                if ($tipoPessoaId == $value){
                    return true;
                }
            }
            return false; 
        }
    }
}


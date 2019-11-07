<?php
namespace bo;

class ControleAcesso
{
    public static function validar($papeisPermitidos) {
        session_start();
        if (!isset($_SESSION['loggedGSEUser'])){
            $log = "[CONTROLE DE ACESSO] Sessão é inválida!";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: login.php");
        } else {
            $pessoa = $_SESSION['loggedGSEUser'];
            $tipoPessoaId = $pessoa->id;
            foreach ($papeisPermitidos as $value){
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
    
}


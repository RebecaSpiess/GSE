<?php
namespace bo;

class Sessao
{
    
    public static function validar() {
        session_start();
        if (!isset($_SESSION['loggedGSEUser'])){
            $log = "[ACESSO] Sessão é inválida!";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: login.php");
        }
    }
    
}


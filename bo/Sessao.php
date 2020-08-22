<?php
namespace bo;

class Sessao
{
    
    public static function validar() {
        session_start();
        if (!isset($_SESSION['loggedGSEUser'])){
            file_put_contents('./log_'.date("j.n.Y").'.log', var_dump($_SESSION), FILE_APPEND);
            $log = "[ACESSO] Sessão é inválida!\r\n";
            file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            header("Location: login.php");
        }
    }
    
    
}

if (isset($_POST['logout'])){
    $logout = $_POST['logout'];
    if ($logout == 'GSElogout' ) {
        session_start();
        unset ($_SESSION['loggedGSEUser']);
        session_destroy();
        header("Location: ../login.php");
    }
}

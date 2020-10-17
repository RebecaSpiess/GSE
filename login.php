<?php 

use model\Pessoa;

require 'model/Pessoa.php';

require 'database/db.php';

$mostrarMensagemSenhaErrada = false;

$enc_senha_512 = hash('sha512','123456GSE');
error_log($enc_senha_512);

if (isset($_POST['email']) and isset($_POST['senha'])){
    $email = $_POST["email"];    
    $senha = $_POST["senha"];
    if (!empty(trim($email)) and !empty(trim($senha))){
        $db = new db();
        $enc_senha = hash('sha512',$senha.'GSE');
        $pessoa = $db->query('SELECT * FROM PESSOA WHERE EMAIL = ? AND SENHA = ?',
            $email, $enc_senha);
        
        $contador = $pessoa->numRows();
        $pessoaResult = $pessoa->fetchAll();
        if ($contador == 0){
            $mostrarMensagemSenhaErrada = true;
        } else {
            $usuarioLogado =  new Pessoa();
            $usuarioLogado->id = $pessoaResult[0]['ID'];
            $usuarioLogado->nome = $pessoaResult[0]['NOME'];
            $usuarioLogado->sobrenome = $pessoaResult[0]['SOBRENOME'];
            $usuarioLogado->email = $pessoaResult[0]['EMAIL'];
            $usuarioLogado->tipo_pessoa = $pessoaResult[0]['TIPO_PESSOA'];
            session_start();
            $_SESSION['loggedGSEUser'] = serialize($usuarioLogado);
            header("Location: index.php");
        }
        $db->close();
    }
} 

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>GSE</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  
  <style type="text/css">

.btn-primary {
    color: black !important;
    background-color: #e9ecef !important;
    border-color: black !important;
}

</style>
  
  <script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var email = document.getElementById("exampleInputEmail1");
		var senha = document.getElementById("exampleInputPassword1");
		var camposPreenchidos = true; 
		if (!isNotBlank(email.value)){
			camposPreenchidos = false;
			document.getElementById("emailValidacao").style.display = "block";
		} else {
			camposPreenchidos = true;
			document.getElementById("emailValidacao").style.display = "none";
		}	

		if (!isNotBlank(senha.value)){
			camposPreenchidos = false;
			document.getElementById("senhaValidacao").style.display = "block";
		} else {				
			document.getElementById("senhaValidacao").style.display = "none";
		}	

		if (camposPreenchidos){
			submit();
		}		
	}

	function isNotBlank(value){
		if (value == null){
			return false;
		}
		return value.trim().length !== 0;
	}	

  </script>
  
</head>

<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">GSE - Login</div>
      <div class="card-body">
        <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
          <div class="form-group">
            <label for="exampleInputEmail1">Endereço de e-mail</label>
            <input class="form-control" id="exampleInputEmail1" name="email" type="email" aria-describedby="emailHelp" placeholder="E-Mail" required>
          	<div id="emailValidacao" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Senha</label>
            <input class="form-control" id="exampleInputPassword1" name="senha" type="password" placeholder="Senha">
            <div id="senhaValidacao" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          </div>          
          <input type="submit" class="btn btn-primary btn-block" onclick="validateAndSubmitForm();" value="Login" />
        </form>
        <div class="text-center">
         <div style="display: none">
          	<a class="d-block small" href="forgot-password.php">Esqueci a senha</a>
          </div>
          <?php 
          if ($mostrarMensagemSenhaErrada){
              echo '<br><span style="font-size: 12pt; color:red">Credenciais inválidas!</span>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>

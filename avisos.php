<?php
require 'bo/Sessao.php';
require  'bo/ControleAcesso.php';
require 'database/db.php';



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer();

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();

$papeisPermitidos = array(2,4,1);
ControleAcesso::validar($papeisPermitidos);

$pessoa = unserialize($_SESSION['loggedGSEUser']);


$id = $pessoa->id;


if (isset($_POST['servidor']) and
    isset($_POST['aviso'])){
        $servidor = $_POST['servidor'];
        $aviso = $_POST['aviso'];
        $remetente = $pessoa->id;
         
        if (!empty(trim($servidor)) and
            !empty(trim($aviso))){
                
                $sqlEmail = "";
                if ($servidor=="todosResponsaveis"){
                    $servidor = "Todos os responsáveis";
                    $sqlEmail = "SELECT resp.EMAIL FROM PESSOA resp WHERE (resp.ID)  IN ";
                    $sqlEmail .= "(SELECT p.RESPONSAVEL_1 FROM PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Aluno(a)') ";
                    $sqlEmail .= " UNION ";
                    $sqlEmail .= " SELECT p.RESPONSAVEL_2 FROM PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Aluno(a)'))";
                } else if ($servidor=="todosProfessores"){
                    $sqlEmail = "SELECT EMAIL FROM PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Professor(a)')";
                    $servidor = "Todos os professores";
                } else {
                    $sqlEmail = "SELECT resp.EMAIL FROM PESSOA resp WHERE (resp.ID)  IN ";
                    $sqlEmail .= " (SELECT p.RESPONSAVEL_1 FROM PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Aluno(a)') ";
                    $sqlEmail .= " JOIN TURMA_PESSOA turmaPessoa ON (turmaPessoa.ID_TURMA = '$servidor' and turmaPessoa.ID_PESSOA = p.ID) ";
                    $sqlEmail .= " UNION ";
                    $sqlEmail .= " SELECT p.RESPONSAVEL_2 FROM PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Aluno(a)') ";
                    $sqlEmail .= " JOIN TURMA_PESSOA turmaPessoa ON (turmaPessoa.ID_TURMA = '$servidor' and turmaPessoa.ID_PESSOA = p.ID)) ";
                    
                    $db4 = new db();
                    try {
                        $nome_turma_db =  $db4->query("SELECT NOME_TURMA FROM TURMA WHERE ID = '" . $servidor . "'")->fetchAll();
                        $servidor = "Todos os responsáveis da turma: " . $nome_turma_db[0]['NOME_TURMA'];
                    } finally {
                        $db4->close();
                    }
                    
                }
                $db2 = new db();
                $db3 = new db();
                try {
                    $result = $db2->query("INSERT INTO MENSAGEM (REMETENTE, DESTINATARIO, AVISO)
                          VALUES (?,?,?) "
                        , $remetente
                        , $servidor
                        , substr($aviso,0,249)
                        )->query_count;
                    
                        if ($result == 1){
                            $destinatario_db =  $db3->query($sqlEmail)->fetchAll();
                            foreach ($destinatario_db as $destinatario_db_registro){
                                try {
                                    //Server settings
                                    #$mail->SMTPDebug = SMTP::                      // Enable verbose debug output
                                    $mail->isSMTP();                                            // Send using SMTP
                                    $mail->Host       = 'email-ssl.com.br';                    // Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                    
                                    $mail->Username   = 'comunicados@gestaosocioeducacional.com.br'; // SMTP username
                                    $mail->Password   = 'Comunicados#20201';                               // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                                    $mail->Port       = 587;
                                    
                                    //Recipients
                                    $mail->setFrom('comunicados@gestaosocioeducacional.com.br', 'GSE - ' . $pessoa->nome . ' ' . $pessoa->sobrenome);
                                    $mail->addReplyTo($pessoa->email);
                                    
                                    $mail->addAddress($destinatario_db_registro['EMAIL']); 
                                    error_log($destinatario_db_registro['EMAIL']);
                                    $mail->CharSet='UTF-8';
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = 'GSE - Aviso';
                                    $mail->Body    = '<div style="color: #363534; font-family: Calibri, Candara;font-size: 12pt;"> Olá, <br/><br/> você recebeu a seguinte mensagem de <a href="mailto:' 
                                        . $pessoa->email . '">' . $pessoa->nome . ' '
                                        . $pessoa->sobrenome  . '</a>: <br/> <br/>' . $aviso . 
                                    '<br/><br/>Atenciosamente,<br/>GSE - Gestão Sócio Educacional.</div><span style="font-family: Calibri, Candara;font-size:10pt">http://gestaosocioeducacional.com.br</span>';
                                     $mail->send();
                                } catch (Exception $ex){
                                    $error_code = $ex->getMessage();
                                    echo $error_code;
                                    error_log($ex);
                                }
                            }
                        }
                } catch (Exception $ex){
                    $error_code = $ex->getMessage();
                    echo $error_code;
                } finally {
                    $db2->close();
                    $db3->close();
                }
        }
}
$db1 = new db();
$msg = null;
$msg_temp = null;
try {
    $mensagens_recebidas = $db1->query("SELECT m.AVISO, DATE_FORMAT(m.DATA_HORA_AVISO, '%d/%m/%Y %H:%i:%s') as DATA_HORA_AVISO , p.NOME, p.SOBRENOME, p.EMAIL FROM MENSAGEM m JOIN PESSOA p ON (m.REMETENTE = p.ID) WHERE (m.DESTINATARIO = 'Todos os professores' OR m.REMETENTE=?) ORDER BY m.DATA_HORA_AVISO DESC", $id)->fetchAll();
    foreach ($mensagens_recebidas as $single_row0) {
        $horarioRecebimento = $single_row0['DATA_HORA_AVISO'];
        $avisoRecebimento = $single_row0['AVISO'];
        $nomeRecebimento = $single_row0['NOME'] . ' ' . $single_row0['SOBRENOME'];
        $email = $single_row0['EMAIL'];
        $msg_temp = $horarioRecebimento . '&#13;&#10;'. $nomeRecebimento . ' (' . $email . '): ' . $avisoRecebimento . '&#13;&#10;&#13;&#10;';
        $msg .= trim($msg_temp);
    }
} finally {
    $db1->close(); 
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<title>GSE - Avisos</title>
<!-- Bootstrap core CSS-->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom fonts for this template-->
<link href="vendor/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">
<!-- Custom styles for this template-->
<link href="css/sb-admin.css" rel="stylesheet">


<script type="text/javascript">
	function trimMensagensRecebidas() {
		element = document.getElementById('mensagensRecebidas');
		element.value = element.value.trim(); 
	}

	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var servidor = document.getElementById("servidor");
		var aviso = document.getElementById("aviso");
		var camposPreenchidos = true; 
		if (!isNotBlank(servidor.value)){
			camposPreenchidos = false;
			document.getElementById("servidorErro").style.display = "block";
		} else {
			document.getElementById("servidorErro").style.display = "none";
		}

		if (!isNotBlank(aviso.value)){
			camposPreenchidos = false;
			document.getElementById("avisoErro").style.display = "block";
		} else {
			document.getElementById("avisoErro").style.display = "none";
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

<body class="fixed-nav sticky-footer bg-dark" id="page-top" onload="trimMensagensRecebidas();">
	<!-- Navigation-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"
		id="mainNav">
		<a class="navbar-brand" href="index.php">GSE - Gestão sócio
			educacional</a>
		<button class="navbar-toggler navbar-toggler-right" type="button"
			data-toggle="collapse" data-target="#navbarResponsive"
			aria-controls="navbarResponsive" aria-expanded="false"
			aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Alunos</span>
				</a>
					<ul class="sidenav-second-level collapse" id="collapseExamplePages">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
							<li><a href="aluno_cadastro.php">Cadastro</a></li>
						<?php } ?>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="ocorrencias_cadastro_busca.php">Ocorrências</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts"><a class="nav-link" href="avisos.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Avisos</span>
				</a></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages1" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Disciplinas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages1">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="disciplina_cadastro.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages2" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Frequência</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages2">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="frequencia_cadastro.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages3" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Notas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages3">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="aluno_notas.php">Cadastro</a></li>
						<?php } ?>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="relatorio.php">Gerar relatório</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages4" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Plano
							de aula</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages4">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="plano_aula_cadastro.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages5" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Ocorrências</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages5">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="ocorrencias_cadastro_busca.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts"><a class="nav-link" href="relatorio.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Relatório</span>
				</a></li>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages6" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Servidores</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
					</ul></li>
						<?php } ?>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages7" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Turmas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages7">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1))) { ?>
						<li><a href="turma_cadastro.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a class="nav-link" data-toggle="modal"
					data-target="#exampleModal"> <i class="fa fa-fw fa-sign-out"></i>Sair
				</a></li>
			</ul>
		</div>
	</nav>
	<div class="content-wrapper">
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Avisos</a></li>
				<li class="breadcrumb-item active">Quadro de avisos</li>
			</ol>
			<div class="card mb-3">
				<div class="card-header">
					Enviar aviso
				</div>
		<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
				<div class="card-body" style="margin-left: -5px; width: 100%">
					<label for="exampleInputName" style="margin-left: 16px;">Destinatário*</label>
				<select class="form-control" id="servidor" name="servidor" required style="marging: 50px">
				<optgroup label="Responsáveis">
					<option value="todosResponsaveis">Todos responsáveis</option>
				</optgroup>
				<optgroup label="Servidores">
					<option value="todosProfessores">Todos professores</option>
				</optgroup>
				<optgroup label="Turmas">
					<?php
					try {
					    $db = new db();
					    $turmas_db = $db->query("SELECT ID, NOME_TURMA FROM gestaose.TURMA ORDER BY NOME_TURMA");
					 
                        $turmas_db_fetch = $turmas_db->fetchAll();
                        foreach ($turmas_db_fetch as $single_row0) {
                            echo "<option value=\"" . $single_row0['ID'] . "\">" . $single_row0['NOME_TURMA'] . "</option>";
                        } 
                    } finally {
                        $db->close();
                    }
        ?>
				</optgroup>
										</select>
										<div id="servidorErro" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div><br>  
				</div> 
				<div class="card-body" style="margin-left: -5px; width: 100%">
					<label for="exampleInputName" style="margin-left: 16px;">Mensagem*</label>
					<textarea class="form-control" id="aviso" rows="3"
						name="aviso" placeholder="descreva o aviso" maxlength="250"> </textarea>
					<div id="avisoErro" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div><br>
				</div>
				</div>
				<a class="btn btn-primary btn-block" onclick="validateAndSubmitForm()">Enviar</a>
				</form>
			</div>
			<br>
			<div class="row">
				<div class="card mb-3"
					style="width: 100%; margin-left: 17px; margin-right: 17px">
					<div class="card-header">
						Avisos enviados
					</div>
					<div class="card-body">
					<textarea class="form-control" id="mensagensRecebidas" rows="3" style="text-align: left;" disabled>	<?php echo $msg;?></textarea>
					</div>
				</div>
			</div>
			<!-- /.container-fluid-->
			<!-- /.content-wrapper-->
			<footer class="sticky-footer">
				<div class="container">
					<div class="text-center">
						<small>Copyright © GSE 2020</small>
					</div>
				</div>
			</footer>
			<!-- Scroll to Top Button-->
			<a class="scroll-to-top rounded" href="#page-top"> <i
				class="fa fa-angle-up"></i>
			</a>
			<!-- Logout Modal-->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
				aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Deseja mesmo sair?</h5>
							<button class="close" type="button" data-dismiss="modal"
								aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">Selecione "Sair" abaixo, caso você esteja
							pronto para encerrar a seção atual.</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button"
								data-dismiss="modal">Cancelar</button>
							<form action="bo/Sessao.php" name="logout" method="POST">
								<input type="hidden" value="GSElogout" name="logout"> <a
									class="btn btn-primary" onclick="document.logout.submit()">Sair</a>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Bootstrap core JavaScript-->
			<script src="vendor/jquery/jquery.min.js"></script>
			<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
			<!-- Core plugin JavaScript-->
			<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
			<!-- Page level plugin JavaScript-->
			<script src="vendor/chart.js/Chart.min.js"></script>
			<!-- Custom scripts for all pages-->
			<script src="js/sb-admin.min.js"></script>
			<!-- Custom scripts for this page-->
			<script src="js/sb-admin-charts.min.js"></script>
		</div>

</body>

</html>
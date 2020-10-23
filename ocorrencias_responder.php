<?php
require 'bo/Sessao.php';
require  'bo/ControleAcesso.php';
require 'database/db.php';
use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer();

$papeisPermitidos = array(2,4,7,6);
ControleAcesso::validar($papeisPermitidos);


$pessoa = unserialize($_SESSION['loggedGSEUser']);
$alunoId = $_POST['alunoId'];


$showErrorMessage = null;
$showSuccessMessage = false;



$msg = null;
$msg_temp = null;
try {
    $db1 = new db();
    $ocorrencia_recebida = $db1->query("SELECT oc.ID, DATE_FORMAT(oc.DATA, '%d/%m/%Y %H:%i:%s') as DATA, oc.DESCRICAO, CONCAT(CONCAT(pe.NOME, ' '),  pe.SOBRENOME) as 'ALUNO', CONCAT(CONCAT(p.NOME, ' '),  p.SOBRENOME) as 'AUTOR' FROM OCORRENCIA oc
			JOIN PESSOA pe ON (pe.ID = oc.ID_PESSOA_ALUNO)
            JOIN PESSOA p ON (p.ID = oc.ID_PESSOA_AUTOR)
            WHERE (ID_PESSOA_ALUNO = ?) ORDER BY DATA DESC", $alunoId)->fetchAll();
    foreach ($ocorrencia_recebida as $single_row0) {
        $horarioRecebimento = $single_row0['DATA'];
        $avisoRecebimento = $single_row0['DESCRICAO'];
        $nomeRecebimento = $single_row0['ALUNO'];        
        $msg_temp = $horarioRecebimento . '&#13;&#10;'. $nomeRecebimento . ': ' . $avisoRecebimento . '&#13;&#10;&#13;&#10;';
        $msg .= trim($msg_temp); 
    }
} finally {
    $db1->close();
}

if (isset($_POST['aluno'])
    and isset($_POST['ocorrencia'])
    and isset($_POST['tipoOcorrencia'])){
        $aluno = $_POST['aluno'];
        $tipo_ocorrencia = $_POST['tipoOcorrencia'];
        $ocorrencia = $_POST['ocorrencia'];
        $tipoOcorrencia = $_POST['tipoOcorrencia'];
        $autor = $pessoa->id;
        if (!empty(trim($aluno)) and
            !empty(trim($ocorrencia))){
                try {
                    $result = $db1->query("INSERT INTO OCORRENCIA (ID_PESSOA_ALUNO, ID_PESSOA_AUTOR,DESCRICAO, ID_TIPO)
                          VALUES (?,?,?,?) "
                        , $aluno
                        , $autor
                        , $ocorrencia
                        , $tipoOcorrencia
                        )->query_count;
                        if ($result == 1){
                            $showSuccessMessage = true;
                            $mail->isSMTP();                                            // Send using SMTP
                            $mail->Host       = 'email-ssl.com.br';                    // Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                            $mail->Username   = 'comunicados@gestaosocioeducacional.com.br'; // SMTP username
                            $mail->Password   = 'Comunicados#20201';                               // SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                            $mail->Port       = 587;                                    // TCP port to connect to
                            
                            //Recipients
                            $mail->setFrom('comunicados@gestaosocioeducacional.com.br', 'GSE - ' . $pessoa->nome . ' ' . $pessoa->sobrenome);
                            error_log($pessoa->email);
                            $mail->addReplyTo($pessoa->email);
                            
                            $db_aluno_email_fetch = $db0->query("SELECT responsavel1.EMAIL as RESP1_EMAIL, responsavel2.EMAIL as RESP2_EMAIL FROM PESSOA aluno JOIN PESSOA responsavel1 ON (aluno.RESPONSAVEL_1 = responsavel1.ID) LEFT JOIN PESSOA responsavel2 ON (aluno.RESPONSAVEL_2 = responsavel2.ID) WHERE aluno.ID = ? " , $aluno)->fetchAll();
                            $resp2email = $db_aluno_email_fetch[0]['RESP2_EMAIL'];
                            if (isset($resp2email)){
                                $mail->addAddress($resp2email);
                            }
                            $mail->addAddress($db_aluno_email_fetch[0]['RESP1_EMAIL']);
                            $mail->CharSet='UTF-8';
                            
                            // Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'GSE - Ocorrencia';
                            $mail->Body    = '<div style="color: #363534; font-family: Calibri, Candara;font-size: 12pt;"> Olá, <br/><br/> você recebeu a seguinte ocorrência de <a href="mailto:'
                                . $pessoa->email . '">' . $pessoa->nome . ' '
                                    . $pessoa->sobrenome  . '</a>: <br/> <br/>' . $ocorrencia .
                                    '<br/><br/>Atenciosamente,<br/>GSE - Gestão Sócio Educacional.</div><span style="font-family: Calibri, Candara;font-size:10pt">http://gestaosocioeducacional.com.br/</span>';
                            if ($mail->send()){
                                error_log("Enviado!!!!");
                            } else {
                                error_log("Não foi enviado!!!!");
                            }
                        } 
                } catch (Exception $ex){
                    $error_code = $ex->getMessage();
                    if ($error_code == 1062){
                        $showErrorMessage = "Já existe um registro com ID informado!";
                    } else {
                        $showErrorMessage = "Ocorreu um erro interno! Contate o administrador do sistema!";
                    }
                }
        }
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
<title>GSE - Cadastro de ocorrências</title>
<!-- Bootstrap core CSS-->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom fonts for this template-->
<link href="vendor/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">
<!-- Page level plugin CSS-->
<link href="vendor/datatables/dataTables.bootstrap4.css"
	rel="stylesheet">
<!-- Custom styles for this template-->
<link href="css/sb-admin.css" rel="stylesheet">

<style type="text/css">

.btn-primary {
    color: black !important;
    background-color: #e9ecef !important;
    border-color: black !important;
}

::-webkit-scrollbar-track {
    background-color: #F4F4F4;
}
::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
}
::-webkit-scrollbar-thumb {
    background: #dad7d7;
}


</style>

<script src="vendor/jquery/jquery.min.js"></script>

  <script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var ocorrencia = document.getElementById("ocorrencia");
		var aluno = document.getElementById("aluno");
		var camposPreenchidos = true;
		 
		if (!isNotBlank(aluno.value)){
			camposPreenchidos = false;
		}
		
		if (!isNotBlank(ocorrencia.value)){
			camposPreenchidos = false;
		}	

		if (camposPreenchidos){
			submit();
		} else {
			alert('Preencha todos os campos obrigatórios!');
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

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
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
			<ul class="navbar-nav navbar-sidenav" id="exampleAccordion" style="overflow-y:auto" >
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Alunos</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse" id="collapseExamplePages">
						
							<li><a href="aluno_cadastro.php">Cadastro</a></li>
							<li><a href="aluno_visualizar.php">Visualizar</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link" href="avisos.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Avisos</span>
				</a> <?php } ?>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages1" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Disciplinas</span>
				</a><?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages1">
						<li><a href="disciplina_cadastro.php">Cadastro</a></li>
						<li><a href="disciplina_visualizar.php">Visualizar</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages2" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Frequência</span>
				</a><?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages2">
						<li><a href="frequencia_cadastro.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages3" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Notas</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages3">
						<li><a href="aluno_notas.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages4" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Plano
							de aula</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages4">
						<li><a href="plano_aula_cadastro.php">Cadastro</a></li>
						<li><a href="plano_aula_visualizar.php">Visualizar</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7,6))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages5" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Ocorrências</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages5">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
						<li><a href="ocorrencias_cadastro_busca.php">Cadastro</a></li>
						<?php } ?>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,7,6))) { ?>
						<li><a href="ocorrencias_visualizar.php">Visualizar</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link" href="relatorio.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Relatório</span>
				</a><?php } ?>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages6" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Servidores</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
						<li><a href="servidores_visualizar.php">Visualizar</a></li>
					</ul></li>	
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages7" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Turmas</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages7">
						<li><a href="turma_cadastro.php">Cadastro</a></li>
						<li><a href="turma_visualizar.php">Visualizar</a></li>
					</ul></li>
			</ul>
			<ul class="navbar-nav sidenav-toggler">
				<li class="nav-item"><a class="nav-link text-center"
					id="sidenavToggler"> <i class="fa fa-fw fa-angle-left"></i>
				</a></li>
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
				<li class="breadcrumb-item">Ocorrências</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
						<div class="row">
							<div class="card mb-3"
								style="width: 100%; margin-left: 17px; margin-right: 17px">
								<div class="card-header">
									Descrição da ocorrência
								</div>
								<div class="card-body">
								<textarea class="form-control" id="ocorrenciaRecebidas" rows="10" style="text-align: left;" disabled><?php echo $msg;?></textarea>
								</div>
							</div>
							<br>
							<span style="margin-left: 17px;">Resposta: *</span>
							<div class="card mb-3"
								style="width: 100%; margin-left: 17px; margin-right: 17px;">
								<div class="card-body" style="padding-left: 1.25rem; padding-top: 5px;padding-bottom: 5px;">
									<input type="checkbox" name="atendimentoEducacionalEspecializado" id="atendimentoEducacionalEspecializado" />
									Necessita de atendimento educacional especializado
								</div>	
								<div class="card-body" style="padding-top: 0px;padding-bottom: 1.25rem;" >
								<textarea class="form-control" id="ocorrenciaRecebidas" rows="10" style="text-align: left;"></textarea>
								</div>
							</div>
					
        					<a class="btn btn-primary btn-block" style="margin-left: 1.1rem;margin-right: 1rem;" onclick="validateAndSubmitForm()">Responder</a>
					</form>
					</div>
				</div>
				<?php 
					if (isset($showErrorMessage)){ ?>
						<div style="color:red;text-align: center;"><?php echo $showErrorMessage ?> </div>
					<?php 
					}
					
					if ($showSuccessMessage and !isset($showErrorMessage)){ ?>
					    <div style="color:green;text-align: center;">Ocorrência criada com sucesso!</div>
					<?php }
					
					?>
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
					<div class="modal-body">Seleciona "Sair" abaixo, caso você esteja
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
		<script src="vendor/datatables/jquery.dataTables.js"></script>
		<script src="vendor/datatables/dataTables.bootstrap4.js"></script>
		<!-- Custom scripts for all pages-->
		<script src="js/sb-admin.min.js"></script>
		<!-- Custom scripts for this page-->
		<script src="js/sb-admin-datatables.min.js"></script>
	</div>
</body>

</html>



<?php
require 'bo/Sessao.php';
require 'database/db.php';
require 'model/Pessoa.php';

use bo\Sessao;

Sessao::validar();



$db = new db();
$db2 = new db();

$servidores_db = $db->query("SELECT p.ID, p.NOME, p.EMAIL, tp.NOME as PROFISSAO FROM PESSOA p JOIN TIPO_PESSOA tp ON (p.TIPO_PESSOA = tp.ID and tp.ID IN (1,2,4)) ORDER BY p.nome, p.sobrenome");

if (isset($_POST['servidor']) and
    isset($_POST['aviso'])){
        $servidor = $_POST['servidor'];
        $aviso = $_POST['aviso'];
        $pessoa = unserialize($_SESSION['loggedGSEUser']);
        $remetente = $pessoa->id;
         
        if (!empty(trim($servidor)) and
            !empty(trim($aviso))){
                try {
                    $result = $db2->query("INSERT INTO MENSAGEM (REMETENTE, DESTINATARIO, AVISO)
                          VALUES (?,?,?) "
                        , $remetente
                        , $servidor
                        , substr($aviso,0,249)
                        )->query_count;
                        if ($result == 1){
                            ini_set('SMTP', 'mysmtphost');
                            ini_set('smtp_port', 587); 
                            $from = "gse_aviso@smarthomecontrol.com.br";
                            $to = "luizglasenapp@gmail.com";
                            $subject = "GSE - Aviso";
                            $message = "<html><body>" . $aviso . "</body></html>";
                            $headers =  'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'From: GSE aviso ' . $from . "\r\n";
                            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                            mail($to, $subject, $message, $headers);
                        }
                } catch (Exception $ex){
                    $error_code = $ex->getMessage();
                    echo $error_code;
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
<title>GSE - Avisos</title>
<!-- Bootstrap core CSS-->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom fonts for this template-->
<link href="vendor/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">
<!-- Custom styles for this template-->
<link href="css/sb-admin.css" rel="stylesheet">


<script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var servidor = document.getElementById("servidor");
		var aviso = document.getElementById("aviso");
		var camposPreenchidos = true; 
		if (!isNotBlank(servidor.value)){
			camposPreenchidos = false;
		} else {
			camposPreenchidos = true;
		}	

		if (!isNotBlank(aviso.value)){
			camposPreenchidos = false;
		} else {
			camposPreenchidos = true;				
		}	

		if (camposPreenchidos){
			submit();
		} else {
			alert('Por favor preencha todos os campos!');
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
			<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Alunos</span>
				</a>
					<ul class="sidenav-second-level collapse" id="collapseExamplePages">
						<li><a href="aluno_cadastro.php">Cadastro</a></li>
						<li><a href="aluno_notas.php">Notas</a></li>
						<li><a href="ocorrencias_cadastro_busca.php">Ocorrências</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts"><a class="nav-link" href="avisos.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Avisos</span>
				</a></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages1" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Disciplinas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages1">
						<li><a href="disciplina_cadastro.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages2" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Frequência</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages2">
						<li><a href="frequencia_cadastro.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages3" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Notas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages3">
						<li><a href="aluno_cadastro.php">Cadastro</a></li>
						<li><a href="relatorio.php">Gerar relatório</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages4" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Plano de
							aula</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages4">
						<li><a href="plano_aula_cadastro.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages5" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Ocorrências</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages5">
						<li><a href="ocorrencias_cadastro_busca.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts"><a class="nav-link" href="relatorio.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Relatório</span>
				</a></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages6" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Servidores</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages7" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Turmas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages7">
						<li><a href="turma_cadastro.php">Cadastro</a></li>
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
					<div id="servidor" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          </div>
																							<?php
        $servidores_db_fetch = $servidores_db->fetchAll();
        foreach ($servidores_db_fetch as $single_row0) {
            echo "<option value=\"" . $single_row0['ID'] . "\">" . $single_row0['NOME'] . " (" . $single_row0['EMAIL'] . ") - " . $single_row0['PROFISSAO'] . "</option>";
        } 
        ?>
										</select> <br>
					<textarea class="form-control" id="aviso" rows="3"
						name="aviso" placeholder="descreva o aviso" maxlength="250"> </textarea>
				</div>
				<a class="btn btn-primary btn-block" onclick="validateAndSubmitForm()">Enviar</a>
				</form>
			</div>
			<div class="row">
				<div class="card mb-3"
					style="width: 100%; margin-left: 17px; margin-right: 17px">
					<div class="card-header">
						Avisos recebidos
					</div>
					<div class="card-body">
						<canvas id="myBarChart" width="100%" height="90"></canvas>
					</div>
				</div>
			</div>
			<!-- /.container-fluid-->
			<!-- /.content-wrapper-->
			<footer class="sticky-footer">
				<div class="container">
					<div class="text-center">
						<small>Copyright © GSE 2019</small>
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
							pornto para encerrar a seção atual.</div>
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

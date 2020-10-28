<?php
require 'bo/Sessao.php';
require  'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();

$papeisPermitidos = array(2,4,1,7);
ControleAcesso::validar($papeisPermitidos);

$pessoa = unserialize($_SESSION['loggedGSEUser']);
$showErrorMessage = null;
$showSuccessMessage = false;
$avaliacaoId = $_POST['idAvaliacao'];

$db0 = new db();
$db1 = new db();

$sqlTurmas = "SELECT n.ID, n.DESCRICAO, n.INSTRUMENTO_AVALIACAO, DATE_FORMAT(n.DATA, '%Y-%m-%d') as DATA, m.NOME , t.NOME_TURMA FROM NOTAS n
JOIN TURMA_PESSOA turmaPessoa ON (turmaPessoa.ID_TURMA = n.ID_TURMA)
JOIN TURMA t ON (t.ID = n.ID_TURMA)
JOIN MATERIA m ON (m.ID = n.ID_MATERIA)
where n.ID = ?";
$tipoPessoaIdentificador = $pessoa->tipo_pessoa;

$db_turma_fetch = $db0->query($sqlTurmas, $avaliacaoId)->fetchAll();

if (isset($_POST['instrumentoAvaliacao']) and
    isset($_POST['dataAvaliacao'])and 
    isset($_POST['conteudoAvaliacao'])){
        $turma = $_POST['turma'];
        $instrumentoAvaliacao = $_POST['instrumentoAvaliacao'];
        $dataAvaliacao = $_POST['dataAvaliacao'];
        $conteudoAvaliacao = $_POST['conteudoAvaliacao'];
        
        if (!empty(trim($instrumentoAvaliacao))and
            !empty(trim($dataAvaliacao))and
            !empty(trim($conteudoAvaliacao))){
                try {
                    $result = $db1->query("UPDATE NOTAS SET INSTRUMENTO_AVALIACAO = ?, DATA = ?, DESCRICAO = ? WHERE ID=?", $instrumentoAvaliacao, $dataAvaliacao, $conteudoAvaliacao, $avaliacaoId)
                       ->query_count;
                        if ($result == 1){
                            $_SESSION['avaliacaoAtualizadaComSucesso'] = true;
                            header("Location: avaliacao_visualizar.php");
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

if (isset($_SESSION['mensagem_notas'])){
    $showSuccessMessage = true;
    $mensagem_sucesso = $_SESSION['mensagem_notas'];
    unset($_SESSION['mensagem_notas']);
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
<title>GSE - Notas de aluno</title>
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


 <script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var mensagem = document.getElementById("mensagemSucesso");
		if (mensagem != null){
			mensagem.style.display = 'none';
		}	
		
		var turma = document.getElementById("turma");
		var conteudoAvaliacao = document.getElementById("conteudoAvaliacao");
		var camposPreenchidos = true;
		 
		if (!isNotBlank(turma.value)){
			camposPreenchidos = false;
			document.getElementById("turmaErro").style.display = "block";
		} else {
			document.getElementById("turmaErro").style.display = "none";
		}	

		if (!isNotBlank(conteudoAvaliacao.value)){
			camposPreenchidos = false;
			document.getElementById("assuntoErro").style.display = "block";
		} else {
			document.getElementById("assuntoErro").style.display = "none";
		}

		if (!isNotBlank(dataAvaliacao.value)){
			camposPreenchidos = false;
			document.getElementById("dataAvaliacaoErro").style.display = "block";
		} else {		
			document.getElementById("dataAvaliacaoErro").style.display = "none";
		}

		if (!isNotBlank(instrumentoAvaliacao.value)){
			camposPreenchidos = false;
			document.getElementById("instrumentoAvaliacaoErro").style.display = "block";
		} else {
			document.getElementById("instrumentoAvaliacaoErro").style.display = "none";
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
	<?php 
					if (isset($showErrorMessage)){ ?>
						<div style="color:red;text-align: center;"><?php echo $showErrorMessage ?> </br></br></div>
					<?php 
					}
					
					if ($showSuccessMessage and !isset($showErrorMessage)){ ?>
					    <div style="color:green;text-align: center;">Registro criado com sucesso!</br></br></div>
					<?php }
					
					?>
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Alunos</li>
				<li class="breadcrumb-item active">Notas</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post">
						<input type="hidden" name="idAvaliacao" value="<?php echo $avaliacaoId;?>"/>
							<div class="form-group">
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="turma">Turma*</label> 
									<input type="text"
										class="form-control" aria-describedby="nameHelp"
										disabled="disabled" placeholder="Turma" id="turma"
										name="turma"
										value="<?php echo $db_turma_fetch[0]['NOME_TURMA'];?>">
									<div id="turmaErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
								<br>
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="turma">Materia*</label> 
									<input type="text"
										class="form-control" aria-describedby="nameHelp"
										disabled="disabled" placeholder="Turma" id="materia"
										name="materia"
										value="<?php echo $db_turma_fetch[0]['NOME'];?>">
									<div id="materiaErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
								<br>
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="instrumentoAvaliacao">Instrumento de avaliação*</label> 
									<input
										class="form-control" id="instrumentoAvaliacao" type="text"
										maxlength="255"  name="instrumentoAvaliacao"
										placeholder="Tipo avaliação. Ex.: Prova, seminário, etc." value="<?php echo $db_turma_fetch[0]['INSTRUMENTO_AVALIACAO'];?>">
									<div id="instrumentoAvaliacaoErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
								<br>
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="dataAavalilação">Data da avaliação*</label> 
									<input
											class="form-control date-mask" id="dataAvaliacao"
											name="dataAvaliacao" type="date"
											aria-describedby="nameHelp" placeholder="Data de nascimento"
											required value="<?php echo $db_turma_fetch[0]['DATA'];?>">
									<div id="dataAvaliacaoErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
								<br>
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
								<label for="conteudoAvaliacao">Conteúdo*</label> 
									<textarea rows="10" cols="30" style="width: 100%; max-width:100%;border: 1px solid #ced4da" maxlength="250" id="conteudoAvaliacao" name="conteudoAvaliacao" placeholder="Conteudo cobrado na avaliação"> <?php echo $db_turma_fetch[0]['DESCRICAO'];?></textarea>
									<div id="assuntoErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
							</div>
					
        					<a class="btn btn-primary btn-block" onclick="validateAndSubmitForm()">Alterar</a>
					</form>
					</div>
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

<?php 
$db0->close();
$db1->close();
?>

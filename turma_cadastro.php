<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4,
    1
);
ControleAcesso::validar($papeisPermitidos);


$db = new db();
$db1 = new db();
$db2 = new db();
$db3 = new db();
$db4 = new db();
$db5 = new db();

$professor_db = $db2->query("select p.ID, p.NOME, p.SOBRENOME, p.EMAIL from PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Professor(a)') ORDER BY p.NOME, p.SOBRENOME");

$materia_db = $db1->query("SELECT ID, NOME FROM MATERIA ORDER BY NOME")->fetchAll();

function listar_professores($id){
    $db6 = new db();
    try {
        $professores = $db6->query("select p.ID, p.NOME, p.SOBRENOME, p.EMAIL from PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Professor(a)') ORDER BY p.NOME, p.SOBRENOME")->fetchAll();
        $listaProfessorCriada = "<select name=\"professor_disciplina_" . $id . "\">";
        foreach ($professores as $professor){
            $listaProfessorCriada .= "<option value=\"" . $professor['ID'] . "\"\>" . $professor['NOME'] . " " . $professor['SOBRENOME'] . " (" . $professor['EMAIL'] . ")</option>";
        }
        $listaProfessorCriada .= "</select>";
    } finally {
        $db6->close();
    }
    return $listaProfessorCriada;
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
<title>GSE - Cadastro de turma</title>
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

<script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var professor_responsavel = document.getElementById("professorResp");
		var camposPreenchidos = true;
		 
		var nome_turma = document.getElementById("nomeTurma");
		if (!isNotBlank(nome_turma.value)){
			camposPreenchidos = false;
			document.getElementById("nome_turma_erro").style.display = "block";
		} else {			
			document.getElementById("nome_turma_erro").style.display = "none";
		}

		var haUmaMateriaSelecionada = false;
	
		<?php
		echo "\n";
		foreach ($materia_db as $single_row1) {
		    echo "\t\tvar materia_" . $single_row1['ID'] . " = document.getElementById(\"materia_" . $single_row1['ID'] . "\");\n";
		    echo "\t\tif (materia_" . $single_row1['ID'] . ".checked){\n";
			echo "   \t\thaUmaMateriaSelecionada = true;\n";
		    echo "\t\t}\n";
		}
		?>

		if (!haUmaMateriaSelecionada){
			camposPreenchidos = false;
			document.getElementById("materia_erro").style.display = "block";
		} else {
			document.getElementById("materia_erro").style.display = "none";
		}		 

		var arquivoCsv = document.getElementById("csvfile");
		if (arquivoCsv.files.length == 0){
			camposPreenchidos = false;
			document.getElementById("arquivo_csv_erro").style.display = "block";
		} else {			
			document.getElementById("arquivo_csv_erro").style.display = "none";
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
		if (isset($_SESSION['errosCadastroTurma'])){
		    $errosIdentificados = $_SESSION['errosCadastroTurma'];
		    echo "<div style=\"color: red; text-align: left; padding: 15px\">";
		    $textoFinal = "";
		    foreach ($errosIdentificados as $erroIndentificado) {
		        $textoFinal .= $erroIndentificado;
		    }
		    $textoFinal = trim($textoFinal);
		    $textoFinalUltimoCaracter= substr($textoFinal, -1);
		    if ($textoFinalUltimoCaracter == ","){
		        echo substr($textoFinal, 0, (strlen($textoFinal)-1));
		    } else {
		        echo $textoFinal;
		    }
		    echo "<br></div>";
		    $_SESSION['errosCadastroTurma'] = null;		    
		} else if (isset($_SESSION['sucessoCadastroTurma']) and $_SESSION['sucessoCadastroTurma']){
		    echo "<div style=\"color: green; text-align: center;\">Turma cadastrada com sucesso!<br><br></div>";
		}
		
		?>
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Turma</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body" style="padding: 0px;">
						<form method="post" action="php-script-to-process-form-upload.php" enctype="multipart/form-data">
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6" style="width:100%; max-width: 100%; flex: none;">
										<label for="exampleInputName">Nome da turma*</label> <input
											class="form-control" id="nomeTurma" type="text"
											aria-describedby="nameHelp" placeholder="Nome da turma"
											name="nome_turma" required maxlength="255">
											<div id="nome_turma_erro" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          							</div>
								</div>
								<br>
								<div class="col-md-6" style="padding-left: 0px;padding-right: 0px;width:100%; max-width: 100%;">
										<label for="exampleInputLastName">Professor regente*</label>
											<input type="hidden" name="professor_responsavel" id="professorResp" value="false" >
								<br>
          								
										
										<select
									class="form-control" id="professorResp"
									aria-describedby="nameHelp" name="professor_responsavel">
											<?php
											$professor_db_fetch = $professor_db->fetchAll();
											 foreach ($professor_db_fetch as $single_row0) {
											    echo "<option value=\"" . $single_row0['ID'] . "\"\>" . $single_row0['NOME'] . " " . $single_row0['SOBRENOME']
											    . " (" . $single_row0['EMAIL'] . ")" . "</option>";
                                                }
                                            ?>

										</select>
								</div>
								<br>
								<div class="col-md-6" style="padding-left: 0px;padding-right: 0px;width:100%; max-width: 100%;">
										<label>Matéria*</label>
										<div id="materia_erro" style="display: none;font-size: 10pt; color:red">Selecione ao menos uma matéria!</div>
										<input type="hidden" name="materia" id="materia" value="false" >
								<br>
          								<table cellpadding="3">
										<?php
                                            foreach ($materia_db as $single_row1) {
                                                echo "<tr>";
                                                echo "<td>" . "<input type=\"checkbox\" id=\"materia_" . $single_row1['ID'] . "\" name=\"". $single_row1['ID'] . "\" /> </td>";
                                                echo "<td>" . $single_row1['NOME'] . "</td>";
                                                echo "<td>" . listar_professores($single_row1['ID']) . "</td>";
                                                echo "</tr>";
                                            } 
                                        ?>
										</table>
								</div>
								<br>		
										<div class="form-group">
											<label for="exampleInputEmail1">Alunos</label><br>
											<div id="arquivo_csv_erro" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
											<input type="file" accept=".csv" name="csvfile" id="csvfile" /> <br><br> 
																							
										</div>
									</div>
								</div>
								<a class="btn btn-primary btn-block"
									onclick= "validateAndSubmitForm()">Cadastrar</a>
						
						</form>
					</div>
					<br>
        <br>
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
	<script src="vendor/chart.js/Chart.min.js"></script>
	<script src="vendor/datatables/jquery.dataTables.js"></script>
	<script src="vendor/datatables/dataTables.bootstrap4.js"></script>
	<!-- Custom scripts for all pages-->
	<script src="js/sb-admin.min.js"></script>
	<!-- Custom scripts for this page-->
	<script src="js/sb-admin-datatables.min.js"></script>
	<script src="js/sb-admin-charts.min.js"></script>
	</div>
</body>

</html>

<?php 
$db->close();
$db1->close();
$db2->close();
$db3->close();
$db4->close();
$db5->close();
?>


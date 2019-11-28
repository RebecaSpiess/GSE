<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Turma;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4
);
ControleAcesso::validar($papeisPermitidos);

$db = new db();
$db1 = new db();
$db2 = new db();


$aluno_db = $db->query("SELECT p.* FROM PESSOA p JOIN TIPO_PESSOA tp ON (p.TIPO_PESSOA = tp.ID and tp.ID = 3) ORDER BY p.nome, p.sobrenome");
$professor_db = $db2->query("SELECT p.* FROM PESSOA p JOIN TIPO_PESSOA tp ON (p.TIPO_PESSOA = tp.ID and tp.ID = 1) ORDER BY p.nome, p.sobrenome");

$materia_db = $db1->query("SELECT ID, NOME FROM MATERIA ORDER BY NOME")->fetchAll();

$showErrorMessage = null;
$showSuccessMessage = false;

if (isset($_POST['nome_turma']) and isset($_POST['professor_responsavel'])) {
    $nome_turma = $_POST['nome_turma'];
    $professor_responsavel = $_POST['professor_responsavel'];
    
    if (! empty(trim($nome_turma)) and ! empty(trim($professor_responsavel))) {
        $turma = new Turma();
        $turma->id_pessoa = $professor_responsavel;
        $turma->nome_turma = $nome_turma;
        try {
            $result = $db->query("INSERT INTO TURMA (ID_PESSOA, ID_MATERIA, NOME_TURMA)
                          VALUES (?,?,?)", $turma->id_pessoa, $turma->id_materia, $turma->nome_turma)->query_count;
            if ($result == 1) {
                $showSuccessMessage = true;
            }
        } catch (Exception $ex) {
            $error_code = $ex->getMessage();
            if ($error_code == 1062) {
                $showErrorMessage = "Turma existente!";
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
debbuger;
	function submit() {
		alert("chegou até aqui");
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var nome_turma = document.getElementById("nomeTurma");
		var professor_responsavel = document.getElementById("professorResp");
		var camposPreenchidos = true; 
		if (!isNotBlank(nome_turma.value)){
			camposPreenchidos = false;
			document.getElementById("nome_turma").style.display = "block";
		} else {
			camposPreenchidos = true;
			document.getElementById("nome_turma").style.display = "none";
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
						<li><a href="aluno_ocorrencias.php">Ocorrências</a></li>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
						<li><a href="aluno_notas.php">Cadastro</a></li>
						<?php } ?>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
						<li><a href="ocorrencias_cadastro_busca.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Charts"><a class="nav-link" href="relatorio.php"> <i
						class="fa fa-fw fa-area-chart"></i> <span class="nav-link-text">Relatório</span>
				</a></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages6" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Servidores</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
						<?php } ?>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages7" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Turmas</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages7">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
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
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Turma</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6" style="width:100%; max-width: 100%; flex: none;">
										<label for="exampleInputName">Nome da turma*</label> <input
											class="form-control" id="nomeTurma" type="text"
											aria-describedby="nameHelp" placeholder="Nome da turma"
											name="nome_turma" required>
											<div id="nome_turma" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          							</div>
								</div>
								<br>
								<div class="col-md-6" style="padding-left: 0px;padding-right: 0px;width:100%; max-width: 100%;">
										<label for="exampleInputLastName">Professor responsável*</label>
										<select class="form-control" id="professorResp"
											aria-describedby="nameHelp"
											name="professor_responsavel" required>
          
                            		<?php
                                        $professor_db_fetch = $professor_db->fetchAll();
                                        foreach ($professor_db_fetch as $single_row0) {
                                            echo "<option value=\"" . $single_row0['ID'] . "\">" . $single_row0['NOME'] . "</option>";
                                        } 
                                    ?>
										</select>
								</div>
								<br>
								<div class="col-md-6" style="padding-left: 0px;padding-right: 0px;width:100%; max-width: 100%;">
										<label>Matéria*</label>
										<select class="form-control" id="materia" name="materia" required>
										<?php
                                            foreach ($materia_db as $single_row1) {
                                                echo "<option value=\"" . $single_row1['ID'] . "\">" . $single_row1['NOME'] . "</option>";
                                            } 
                                        ?>
										</select>
								</div>
								<br>		
										
										
										

										<div class="form-group">
											<label for="exampleInputEmail1">Alunos*</label><br>
																							<?php
        $aluno_db_fetch = $aluno_db->fetchAll();
        $nameCheckBox = 0;
        foreach ($aluno_db_fetch as $single_row1) {
            echo "<input type=\"checkbox\" name=\"alunoCheck_" . $nameCheckBox . "\" value=\"" . $single_row1['ID'] . "\"> " . $single_row1['NOME'] . " " 
 . $single_row1['SOBRENOME'] . "</input> <br>";
        $nameCheckBox++;
        }
        ?>
										</div>
									</div>
								</div>
								<a class="btn btn-primary btn-block"
									onclick= "validateAndSubmitForm()">Cadastrar</a>
						
						</form>
					</div>
										<?php
        if (isset($showErrorMessage)) {
            ?>
						<div style="color: red; text-align: center;"><?php echo $showErrorMessage ?> </div>
					<?php
        }

        if ($showSuccessMessage and ! isset($showErrorMessage)) {
            ?>
					    <div style="color: green; text-align: center;">Registro criado
						com sucesso!</div>
					<?php
        }

        ?>
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
					<div class="modal-body">Seleciona "Sair" abaixo, caso você esteja
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
	</div>
</body>

</html>

<?php 
$db->close();
$db1->close();
$db2->close();
?>


<?php
require 'bo/Sessao.php';
require  'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();

$papeisPermitidos = array(2,4,1);
ControleAcesso::validar($papeisPermitidos);

$turma_id = $_POST['turma'];
$assunto = $_POST['assunto'];

$showErrorMessage = null;
$showSuccessMessage = false;

$db0 = new db();
$db1 = new db();

$db_turma_fetch = $db0->query("SELECT PE.ID, PE.NOME, PE.SOBRENOME, TU.NOME_TURMA FROM TURMA TU JOIN TURMA_PESSOA TU_PE ON (TU_PE.ID_TURMA = TU.ID) JOIN PESSOA PE ON (TU_PE.ID_PESSOA = PE.ID) 
WHERE PE.TIPO_PESSOA = 3 AND TU.ID = ? ORDER BY PE.NOME, PE.SOBRENOME", $turma_id)->fetchAll();

if (isset($_POST['cadastro_notas'])){
        $cadastro_notas = $_POST['cadastro_notas'];
        $count = 0;
        if (!empty(trim($cadastro_notas)) and $cadastro_notas == 'true'){
            foreach ($db_turma_fetch as $single_row0) {
                if (isset($_POST[$single_row0['ID']])){
                    $nota = $_POST[$single_row0['ID']];
                    $db1->query("INSERT INTO NOTAS (ID_TURMA, ID_PESSOA, NOTA, DESCRICAO) VALUES (?,?,?,?) ",$turma_id,$single_row0['ID'],$nota,$assunto);
                    $db1->close();
                    $db1 = new db();
                    $count++;
                }
            }
            header("Location: /aluno_notas.php");
            if ($count == 1){
                $_SESSION['mensagem_notas'] = "Nota cadastrada com sucesso!";
            } else if ($count > 1){
                $_SESSION['mensagem_notas'] = "Notas cadastradas com sucesso!";
            }
        }
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


 <script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var camposPreenchidos = true;
		var existeCampoPreenchido = false;
		<?php
        foreach ($db_turma_fetch as $single_row1) {
            echo "var aluno_" .  $single_row1['ID'] . " = document.getElementById(\"" . $single_row1['ID'] . "\");\n";
            
            echo "if (isNotBlank(aluno_" .$single_row1['ID'] . ".value)){\n";
            echo "    var value_aluno_" .$single_row1['ID'] . " =  aluno_" .$single_row1['ID'] . ".value;\n";
            echo "    existeCampoPreenchido = true;\n";
            echo "    if (value_aluno_" . $single_row1['ID'] . " < 0 || value_aluno_" . $single_row1['ID'] ."  > 10) {\n";
            echo "         camposPreenchidos = false;\n";
            echo "    }\n";
            echo "}\n\n";
        } 
       ?>
       	
		if (!existeCampoPreenchido){
			alert('Preencha ao menos um campo de nota!');
		} else if (camposPreenchidos){
			document.getElementById('cadastro_notas').value = 'true';
			submit();
		} else {
			alert('Preencha os campos cujo os valores deverão estar entre o seguinte intervalo: 0 e 10!');
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
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Alunos</li>
				<li class="breadcrumb-item active">Notas</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body" style="border-style: solid; border-width: 1px; border-color: #b3b8bd;">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
							<div class="form-group">
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
								
								<span style="font-weight: bold;">Turma:</span> <?php echo $db_turma_fetch[0]['NOME_TURMA'];?><br>
								<span style="font-weight: bold;">Assunto:</span> <?php echo $assunto;?><br>
								<input type="hidden" name="turma" value="<?php echo $turma_id;?>" />
								<input type="hidden" name="assunto" value="<?php echo $assunto;?>" />
								<input type="hidden" name="cadastro_notas" id="cadastro_notas" value="false" />
								<br>
								<table cellpadding="3">									
									<?php
                                            foreach ($db_turma_fetch as $single_row1) {
                                                echo "<tr>";
                                                echo "<td>" . $single_row1['NOME'] . ' ' . $single_row1['SOBRENOME'] . "</td>";
                                                echo "<td>" . "<input type=\"number\" min=\"0\" max=\"10\"  name=\"". $single_row1['ID'] . "\" id=\"". $single_row1['ID'] . "\" /> </td>";
                                                echo "</tr>";
                                            } 
                                        ?>
								</table>		
								</div>
								<br>
							</div>
					
        					<a class="btn btn-primary btn-block" onclick="validateAndSubmitForm()">Cadastrar notas</a>
					</form>
					</div>
				</div>
				<?php 
					if (isset($showErrorMessage)){ ?>
						<div style="color:red;text-align: center;"><?php echo $showErrorMessage ?> </div>
					<?php 
					}
					
					if ($showSuccessMessage and !isset($showErrorMessage)){ ?>
					    <div style="color:green;text-align: center;">Notas cadastradas com sucesso!</div>
					<?php }
					
					?>
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
$db0->close();
?>

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
$materia_id = $_POST['materia'];

$showErrorMessage = null;
$showSuccessMessage = false;

$db0 = new db();
$db1 = new db();
$db2 = new db();
$db3 = new db();
$db4 = new db();
$db5 = new db();

$db_turma_fetch = $db0->query("SELECT PE.ID, PE.NOME, PE.SOBRENOME, TU.NOME_TURMA FROM TURMA TU JOIN TURMA_PESSOA TU_PE ON (TU_PE.ID_TURMA = TU.ID) JOIN PESSOA PE ON (TU_PE.ID_PESSOA = PE.ID) 
WHERE PE.TIPO_PESSOA = 3 AND TU.ID = ? ORDER BY PE.NOME, PE.SOBRENOME", $turma_id)->fetchAll();

$sqlmateria = "select ma.NOME from MATERIA ma
WHERE ma.ID = ? ";
$db_materia_fetch = $db2->query($sqlmateria, $materia_id)->fetchAll();

$db_pessoa_frequencia_fetch = $db5->query("SELECT distinct p.ID, p.NOME, p.SOBRENOME
FROM FREQUENCIA f 
JOIN FREQUENCIA_PESSOA fp ON (fp.ID_FREQUENCIA = f.ID)
JOIN PESSOA p on (p.ID = fp.ID_PESSOA)
WHERE f.ID_TURMA = ? AND f.ID_MATERIA = ? ORDER BY p.NOME, p.SOBRENOME", $turma_id, $materia_id)->fetchAll();


$db_frequencia_fetch = $db3->query("SELECT f.ID, DATE_FORMAT(f.DATA,'%d-%m-%Y') 'DATA' FROM FREQUENCIA f WHERE f.ID_TURMA = ? AND f.ID_MATERIA = ? ORDER BY f.DATA", $turma_id, $materia_id)->fetchAll();

$db_frequencia_mapa = $db4->query("select CONCAT(CONCAT(FP.ID_FREQUENCIA,'_'), FP.ID_PESSOA) 'KEY', PRESENCA 'VALUE' from FREQUENCIA_PESSOA FP JOIN FREQUENCIA F ON (F.ID = FP.ID_FREQUENCIA) WHERE F.ID_MATERIA = ? AND F.ID_TURMA = ?",
$materia_id, $turma_id)->fetchAll();

$mapaFrequencias = array();

foreach ($db_frequencia_mapa as $db_frequencia_mapa_single){
    $mapaFrequencias[$db_frequencia_mapa_single['KEY']]  = $db_frequencia_mapa_single['VALUE'];
}

function checkCheckbox($id_frequencia, $id_aluno, $mapaFrequencias){
    $key = $id_frequencia . "_" . $id_aluno;
    if (array_key_exists($key, $mapaFrequencias)){
        return $mapaFrequencias[$key] == 1 ? " checked " : "";
    }
    return "";
}


if (isset($_POST['cadastro_frequencia'])){
    $count = 0;
    foreach ($db_frequencia_fetch as $single_row1) {
        foreach ($db_pessoa_frequencia_fetch as $single_aluno) {
            $presente = 0;
            $key = $single_row1['ID'] . "_" . $single_aluno['ID'];
            if (isset($_POST[$key])){
                $presente = 1;
            }
            $db16 = new db();
            $count += $db16->query("INSERT INTO FREQUENCIA_PESSOA (ID_PESSOA, ID_FREQUENCIA, PRESENCA) VALUES (?,?,?)
            ON DUPLICATE KEY UPDATE ID_PESSOA=VALUES(ID_PESSOA), ID_FREQUENCIA=VALUES(ID_FREQUENCIA), PRESENCA=VALUES(PRESENCA)"
                ,$single_aluno['ID'], $single_row1['ID'], $presente)->query_count;
                $db16->close();
            
        }
    }
    
    if ($count >= 1){
        $_SESSION['mensagem_frequencia_alteracao'] = "Frequência(s) cadastrada(s) com sucesso!";
        header("Location: aluno_frequencia.php");
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
<title>GSE - Frequência de aluno</title>
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
		submit();
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
				<li class="breadcrumb-item">Alunos</li>
				<li class="breadcrumb-item active">Frequência</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body" style="border-style: solid; border-width: 1px; border-color: #b3b8bd;">
						<form method="post" style="margin-left: 1.1rem;margin-right: 1rem;" action="<?=$_SERVER['PHP_SELF'];?>">
						<input type="hidden" name="turma" value="<?php echo $turma_id; ?>" />
							<input type="hidden" name="materia" value="<?php echo $materia_id; ?>" />
							<div class="form-group">
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
								
								<?php
        if (! empty($db_turma_fetch)) {
            echo "<span style=\"font-weight: bold;\">Turma: </span>" . $db_turma_fetch[0]['NOME_TURMA'] . "<br>";
            echo "<span style=\"font-weight: bold;\">Matéria: </span>" . $db_materia_fetch[0]['NOME'] . "<br>";
        } else {
            echo "<span>Essa turma não possui alunos cadastrados!<br><br>";
        }
        ?>
								<input type="hidden" name="turma" value="<?php echo $turma_id;?>" />
								<input type="hidden" name="cadastro_frequencia" id="cadastro_frequencia" value="false" />
								<br>
								<div style="overflow-x: auto"> 
								<table cellpadding="3" border="1">	
								<?php	
								$dataSetado = false;			
									foreach ($db_pessoa_frequencia_fetch as $single_aluno) {
									    if (!$dataSetado){
                                            echo "<tr>";
                                            echo "<td style=\"text-align:center; width:110px;\">Data</td>";
                                            $dataSetado = true;
                                        }
                                        echo "<td style=\"text-align:center\">" . $single_aluno['NOME'] . ' ' . $single_aluno['SOBRENOME'] . "</td>";
									}
									echo "</tr>";
									
									foreach ($db_frequencia_fetch as $single_row1) {
									    $print_data = false;
									    echo "<tr>";
									    foreach ($db_pessoa_frequencia_fetch as $single_aluno) {
									        if (! $print_data) {
									            echo "<td style=\"text-align:center\">" . $single_row1['DATA'] . "</td>";
									            $print_data = true;
									        }
									        
									        echo "<td style=\"text-align:center\"><input type=\"checkbox\" " . checkCheckbox($single_row1['ID'], $single_aluno['ID'], $mapaFrequencias) . " name=\"" . $single_row1['ID'] . "_" . $single_aluno['ID'] . "\"/> </td>";
									    }
									    echo "</tr>";
									}
									?>
								</table>	
								</div>	
								</div>
								<br>
							</div>
					
        					<?php
							if (!empty($db_turma_fetch)){
							    echo "<a class=\"btn btn-primary btn-block\" onclick=\"validateAndSubmitForm()\"> Alterar frequência</a>";
               					 
							}
        					?>
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
$db3->close();
$db4->close();
$db5->close();
?>

<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4,
    1
);
ControleAcesso::validar($papeisPermitidos);
$pessoa = unserialize($_SESSION['loggedGSEUser']);

$turma_id = $_POST['turma'];
$materia_id = $_POST['materia'];

$IdPessoa = $pessoa->id;
$tipoPessoaIdentificador = $pessoa->tipo_pessoa;

$showErrorMessage = null;
$showSuccessMessage = false;

$db0 = new db();
$db1 = new db();
$db2 = new db();
$db3 = new db();
$db4 = new db();
$db5 = new db();
$db6 = new db();

if ($tipoPessoaIdentificador == 2) {
    $db_materia_professor_fetch = $db4->query("SELECT tm.ID_MATERIA, tm.ID_TURMA, ma.NOME FROM TURMA_MATERIA tm
	JOIN MATERIA ma ON (ma.ID = tm.ID_MATERIA)
    WHERE tm.ID_TURMA = ?", $turma_id)->fetchAll();
} else {
    $db_materia_professor_fetch = $db3->query("SELECT tm.ID_MATERIA, tm.ID_TURMA, ma.NOME FROM TURMA_MATERIA tm
	JOIN MATERIA ma ON (ma.ID = tm.ID_MATERIA)
    WHERE tm.ID_TURMA = ? and tm.ID_PROFESSOR = ?", $turma_id, $IdPessoa)->fetchAll();
}

$db_turma_fetch = $db0->query("SELECT PE.ID, PE.NOME, PE.SOBRENOME, TU.NOME_TURMA FROM TURMA TU JOIN TURMA_PESSOA TU_PE ON (TU_PE.ID_TURMA = TU.ID) JOIN PESSOA PE ON (TU_PE.ID_PESSOA = PE.ID)
WHERE PE.TIPO_PESSOA = 3 AND TU.ID = ? ORDER BY PE.NOME, PE.SOBRENOME", $turma_id)->fetchAll();

$sqlmateria = "select ma.NOME from MATERIA ma 
WHERE ma.ID = ? ";
$db_materia_fetch = $db2->query($sqlmateria, $materia_id)->fetchAll();

$sql_turma_materia_sql = "select ID 'ID_NOTAS', INSTRUMENTO_AVALIACAO,  DATE_FORMAT(DATA, '%Y-%m-%d') as DATA  from NOTAS WHERE ID_TURMA = ? AND ID_MATERIA = ?";
$db_notas_materia_turma_fetch = $db5->query($sql_turma_materia_sql, $turma_id, $materia_id)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['dessa_pagina'])) {
    foreach ($db_turma_fetch as $single_row1) {
        foreach ($db_notas_materia_turma_fetch as $single_notas_materia_turma_fetch_single) {
            $fieldName =  $single_row1['ID']  . "_" . $single_notas_materia_turma_fetch_single['ID_NOTAS'];
            if (!empty($_POST[$fieldName])){
                $nota = $_POST[$fieldName];
                $alunoId = $single_row1['ID'];
                $notaId = $single_notas_materia_turma_fetch_single['ID_NOTAS'];
                $db1 = new db();
                try {
                    $result = $db1->query("INSERT INTO NOTA_PESSOA (ID_NOTA, ID_PESSOA, NOTA) VALUES (?,?,?) 
                   ON DUPLICATE KEY UPDATE ID_NOTA=VALUES(ID_NOTA), ID_PESSOA=VALUES(ID_PESSOA), NOTA=VALUES(NOTA)"
                        ,$notaId, $alunoId, $nota)->query_count;
                        if ($result == 1){
                            $_SESSION['notaCadastradaSucesso'] = true;
                            header("Location: aluno_notas.php");
                            $showSuccessMessage = true;
                        }
                } finally {
                    $db1->close();
                }
            } else {
                $nota = null;
                $alunoId = $single_row1['ID'];
                $notaId = $single_notas_materia_turma_fetch_single['ID_NOTAS'];
                $db1 = new db();
                try {
                    $result = $db1->query("INSERT INTO NOTA_PESSOA (ID_NOTA, ID_PESSOA, NOTA) VALUES (?,?,?)
                   ON DUPLICATE KEY UPDATE ID_NOTA=VALUES(ID_NOTA), ID_PESSOA=VALUES(ID_PESSOA), NOTA=VALUES(NOTA)"
                        ,$notaId, $alunoId, $nota)->query_count;
                        if ($result == 1){
                            $_SESSION['notaCadastradaSucesso'] = true;
                            header("Location: aluno_notas.php");
                            $showSuccessMessage = true;
                        }
                } finally {
                    $db1->close();
                }
            }
        }
    }
    
}



$notas_map_sql = "select CONCAT(CONCAT(ID_NOTA, '_'), ID_PESSOA) 'KEY', NOTA 'VALUE'
from NOTA_PESSOA np JOIN NOTAS nota ON (nota.ID = np.ID_NOTA)
WHERE nota.ID_MATERIA = ? and nota.ID_TURMA = ?";


$notas_map_fetch =  $db6->query($notas_map_sql, $materia_id, $turma_id)->fetchAll();

$notasMap = array();

foreach ($notas_map_fetch as $notas_map_fetch_single){
    $key = $notas_map_fetch_single['KEY'];
    $value = $notas_map_fetch_single['VALUE'];
    $notasMap[$key] = $value;
}

function getNota($nota, $idAluno, $notasMap){
    $key = $nota . "_" . $idAluno;
    error_log($key);
    if (array_key_exists($key, $notasMap)){
        return $notasMap[$key];
    }
    return "";
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
		submit();
	}

	function isNotBlank(value){
		if (value == null){
			return false;
		}
		return value.trim().length !== 0;
	}

	function validateNotas(element){
		var value = element.value;
		if (value === "") {
			element.value="";
		} else {
			var floatValue = parseFloat(value.replace(',','.'));
			var stringValueLength = value.trim().length;
			if (stringValueLength <= 3 && floatValue >= 0.0  && floatValue <= 10.0) {
				element.value=floatValue;
				element.oldValue=floatValue;
			} else {
				element.value=element.oldValue;
			}
		}		
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
			<ul class="navbar-nav navbar-sidenav" id="exampleAccordion"
				style="overflow-y: auto">
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Alunos</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse" id="collapseExamplePages">

						<li><a href="aluno_cadastro.php">Cadastro</a></li>
						<li><a href="aluno_visualizar.php">Visualizar</a></li>
					</ul>
				</li>
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
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages1"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Disciplinas</span>
				</a><?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages1">
						<li><a href="disciplina_cadastro.php">Cadastro</a></li>
						<li><a href="disciplina_visualizar.php">Visualizar</a></li>
					</ul>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages2"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Frequência</span>
				</a><?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages2">
						<li><a href="frequencia_cadastro.php">Cadastro</a></li>
					</ul>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages3"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Notas</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages3">
						<li><a href="aluno_notas.php">Cadastro</a></li>
					</ul>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages4"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Plano de aula</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages4">
						<li><a href="plano_aula_cadastro.php">Cadastro</a></li>
						<li><a href="plano_aula_visualizar.php">Visualizar</a></li>
					</ul>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7,6))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages5"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Ocorrências</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages5">
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,1,7))) { ?>
						<li><a href="ocorrencias_cadastro_busca.php">Cadastro</a></li>
						<?php } ?>
						<?php if (ControleAcesso::validarPapelFuncao(array(2,4,7,6))) { ?>
						<li><a href="ocorrencias_visualizar.php">Visualizar</a></li>
						<?php } ?>
					</ul>
				</li>
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
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages6"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Servidores</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
						<li><a href="servidores_visualizar.php">Visualizar</a></li>
					</ul>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(2,4))) { ?>
					<a class="nav-link nav-link-collapse collapsed"
					data-toggle="collapse" href="#collapseExamplePages7"
					data-parent="#exampleAccordion"> <i class="fa fa-fw fa-file"></i> <span
						class="nav-link-text">Turmas</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages7">
						<li><a href="turma_cadastro.php">Cadastro</a></li>
						<li><a href="turma_visualizar.php">Visualizar</a></li>
					</ul>
				</li>
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
					<div class="card-body"
						style="border-style: solid; border-width: 1px; border-color: #b3b8bd;">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
						    <input type="hidden" name="dessa_pagina" />
							<input type="hidden" name="turma" value="<?php echo $turma_id; ?>" />
							<input type="hidden" name="materia" value="<?php echo $materia_id; ?>" />
							<div class="form-group">
								<div class="col-md-6"
									style="flex: none; max-width: 100%; padding: 0px;">
								<?php
        if (! empty($db_turma_fetch)) {
            echo "<span style=\"font-weight: bold;\">Turma: </span>" . $db_turma_fetch[0]['NOME_TURMA'] . "<br>";
            echo "<br>";
            echo "<span style=\"font-weight: bold;\">Matéria: </span>" . $db_materia_fetch[0]['NOME'] . "<br>";
        } else {
            echo "<span>Essa turma não possui alunos cadastrados!<br><br>";
        }
        ?>
								<input type="hidden" name="cadastro_notas" id="cadastro_notas"
										value="false" /> <br> <br>
		<table cellpadding="3" border="1">
								<?php
        $alunoSetado = false;
        foreach ($db_notas_materia_turma_fetch as $single_notas_materia_turma_fetch_single) {
                if (!$alunoSetado){
                    echo "<tr>";
                    echo "<td style=\"text-align:center\">Aluno</td>";
                    $alunoSetado = true;
                }
                echo "<td style=\"text-align:center\">" . $single_notas_materia_turma_fetch_single['INSTRUMENTO_AVALIACAO'] . "<br>" . $single_notas_materia_turma_fetch_single['DATA'] . "</td>";
        }
        echo "</tr>";
        foreach ($db_turma_fetch as $single_row1) {
            $print_nome_aluno = false;
            echo "<tr>";
            foreach ($db_notas_materia_turma_fetch as $single_notas_materia_turma_fetch_single) {            
                if (! $print_nome_aluno) {
                    echo "<td style=\"text-align:center\">" . $single_row1['NOME'] . ' ' . $single_row1['SOBRENOME'] . "</td>";
                    $print_nome_aluno = true;
                }

                echo "<td style=\"text-align:center\"><input type=\"number\" min=\"0\" max=\"10\" maxlength=\"3\" step=\"0.1\" onfocus=\"this.oldValue = this.value;\"  oninput=\"validateNotas(this);\"  name=\"" . $single_row1['ID'] . "_" . $single_notas_materia_turma_fetch_single['ID_NOTAS']  ."\" id=\"". $single_row1['ID']  . "_" . $single_notas_materia_turma_fetch_single['ID_NOTAS'] . "\" 
               value=\"" . getNota($single_notas_materia_turma_fetch_single['ID_NOTAS'], $single_row1['ID'], $notasMap) ."\" /> </td>";
            }
            echo "</tr>";
        }

        ?>
								</table>
									<div id="cadastro_notasErro"
										style="display: none; font-size: 10pt; color: red">Campo
										obrigatório!</div>

								</div>
								<br>
							</div>
							<?php
    if (! empty($db_turma_fetch)) {
        echo "<a class=\"btn btn-primary btn-block\" onclick=\"validateAndSubmitForm()\">Cadastrar notas</a>";
    }
    ?>
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
$db2->close();
$db3->close();
$db4->close();
$db5->close();
?>

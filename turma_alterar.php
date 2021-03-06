<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';

use bo\Sessao;
use bo\ControleAcesso;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4
);
ControleAcesso::validar($papeisPermitidos);
$turmaID = $_POST['turmaId'];

$db = new db();
$db1 = new db();
$db2 = new db();
$db3 = new db();
$db4 = new db();
$db5 = new db();

$professor_db = $db2->query("select p.ID, p.NOME, p.SOBRENOME, p.EMAIL from PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Professor(a)') ORDER BY p.NOME, p.SOBRENOME");

$sqlTurma = "SELECT tu.ID, tu.NOME_TURMA, CONCAT(CONCAT(pe.NOME, ' '),pe.SOBRENOME) AS 'NOME', tu.ID_PESSOA_PROFESSOR_REGENTE FROM TURMA tu
	join PESSOA pe on (tu.ID_PESSOA_PROFESSOR_REGENTE = pe.ID)
    where tu.ID = ?";
$db_pessoa_fetch = $db->query($sqlTurma, $turmaID)->fetchAll();
$materia_db = $db3->query("SELECT ID, NOME FROM MATERIA ORDER BY NOME")->fetchAll();
$sqlMateria = "SELECT ID_MATERIA, ID_PROFESSOR FROM gestaose.TURMA_MATERIA where ID_TURMA = ?";
$db_materia_professor_fetch = $db4->query($sqlMateria, $turmaID)->fetchAll();

$arrayMaterias = array();
$arrayProfessores = array();
foreach ($db_materia_professor_fetch as $singleMateriaRow){
    array_push($arrayMaterias, $singleMateriaRow['ID_MATERIA']);
    $idMateriaIdProfessor = $singleMateriaRow['ID_MATERIA'] . '-' . $singleMateriaRow['ID_PROFESSOR'];
    error_log("Array inicial: " . $idMateriaIdProfessor);
    array_push($arrayProfessores, $idMateriaIdProfessor);  
}

function listar_professores($id, $arrayProfessores){
    $db6 = new db();
    try {
        $professores = $db6->query("select p.ID, p.NOME, p.SOBRENOME, p.EMAIL from PESSOA p JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and tp.NOME = 'Professor(a)') ORDER BY p.NOME, p.SOBRENOME")->fetchAll();
        $listaProfessorCriada = "<select name=\"professor_disciplina_" . $id . "\">";
        foreach ($professores as $professor){
            $idMateriaIdProfessor = $id . '-' . $professor['ID'];
            error_log("Verificação: " . $idMateriaIdProfessor);
            if (in_array($idMateriaIdProfessor, $arrayProfessores)){
                $listaProfessorCriada .= "<option value=\"" . $professor['ID'] . "\" selected \>" . $professor['NOME'] . " " . $professor['SOBRENOME'] . " (" . $professor['EMAIL'] . ")</option>";
            } else {
                 $listaProfessorCriada .= "<option value=\"" . $professor['ID'] . "\"\>" . $professor['NOME'] . " " . $professor['SOBRENOME'] . " (" . $professor['EMAIL'] . ")</option>";
                
            }
            
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
<title>GSE - Alteração de turma</title>
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
					<?php if (ControleAcesso::validarPapelFuncao(array(1,2,4,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages2" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Frequência</span>
				</a><?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages2">
						<li><a href="frequencia_cadastro.php">Cadastro</a></li>
						<li><a href="aluno_frequencia.php">Alteração das frequências</a></li>
					</ul></li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages">
					<?php if (ControleAcesso::validarPapelFuncao(array(1,2,4,7))) { ?>
					<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages3" data-parent="#exampleAccordion">
						<i class="fa fa-fw fa-file"></i> <span class="nav-link-text">Notas</span>
				</a> <?php } ?>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages3">
						<li><a href="avaliacao.php">Avaliação</a></li>
						<li><a href="avaliacao_visualizar.php">Avaliação visualizar</a></li>
						<li><a href="aluno_notas.php">Cadastro de notas</a></li>
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
						<?php if (ControleAcesso::validarPapelFuncao(array(1,2,4,6,7))) { ?>
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
				<li class="breadcrumb-item active">Alteração</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body" style="padding: 0px;">
						<form method="post" action="atualizarTurma.php" enctype="multipart/form-data">
							<input type="hidden" name="turmaId" id="turmaId" value="<?php echo $turmaID; ?>">
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6" style="width:100%; max-width: 100%; flex: none;">
										<label for="exampleInputName">Nome da turma*</label> <input
											class="form-control" id="nomeTurma" type="text"
											aria-describedby="nameHelp" placeholder="Nome da turma"
											name="nome_turma" required maxlength="255" value="<?php echo trim($db_pessoa_fetch[0]['NOME_TURMA']);?>">
											<div id="nome_turma_erro" style="display: none;font-size: 10pt; color:red">Campo obrigatório!</div>
          							</div>
								</div>
								<br>
								<div class="col-md-6" style="padding-left: 0px;padding-right: 0px;width:100%; max-width: 100%;">
										<label for="exampleInputLastName">Professor regente*</label>
											<input type="hidden" name="professor_responsavel" id="professorResp" value="false" >
										<br>
										<select class="form-control" id="professorResp" aria-describedby="nameHelp" name="professor_responsavel">
										<?php
											$professor_db_fetch = $professor_db->fetchAll();
											 foreach ($professor_db_fetch as $single_row0) {
											     
											     if ($single_row0['ID'] == $db_pessoa_fetch[0]['ID_PESSOA_PROFESSOR_REGENTE']){
											         echo "<option value=\"" . $single_row0['ID'] . "\" selected \>" . $single_row0['NOME'] . " " . $single_row0['SOBRENOME']
											         . " (" . $single_row0['EMAIL'] . ")" . "</option>";
											         
											     } else {
    											     echo "<option value=\"" . $single_row0['ID'] . "\"\>" . $single_row0['NOME'] . " " . $single_row0['SOBRENOME']
	   										     . " (" . $single_row0['EMAIL'] . ")" . "</option>";
											         
											     }
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
                                                if(in_array($single_row1['ID'], $arrayMaterias)){
                                                    echo "<td>" . "<input type=\"checkbox\" checked id=\"materia_" . $single_row1['ID'] . "\" name=\"". $single_row1['ID'] . "\" /> </td>";
                                                } else {
                                                    echo "<td>" . "<input type=\"checkbox\" id=\"materia_" . $single_row1['ID'] . "\" name=\"". $single_row1['ID'] . "\" /> </td>";
                                                }
                                                echo "<td>" . $single_row1['NOME'] . "</td>";
                                                echo "<td>" . listar_professores($single_row1['ID'], $arrayProfessores) . "</td>";
                                                echo "</tr>";
                                            } 
                                        ?>
										</table>
								</div>
								<br>		
										<div class="form-group">
										<?php
										
										$dbAlunosCadastradosSql = $db5->query("SELECT concat(concat(P.NOME, ' '), P.SOBRENOME) AS NOME_ALUNO, PR1.EMAIL FROM TURMA_PESSOA TP
                                        JOIN PESSOA P ON (P.ID = TP.ID_PESSOA AND P.TIPO_PESSOA = '3')
                                        JOIN PESSOA PR1 ON (PR1.ID = P.RESPONSAVEL_1)
                                        WHERE TP.ID_TURMA = ? ORDER BY P.NOME, P.SOBRENOME", $turmaID);
										
										$alunosQuantidade = $dbAlunosCadastradosSql->numRows();
										$db_pessoa_fetch = $dbAlunosCadastradosSql->fetchAll();
										
										?>
										
										
											<label for="exampleInputEmail1">Alunos cadastrados: <?php echo $alunosQuantidade; ?></label><br>
											<?php if ($alunosQuantidade <> 0){?>
											<table border="1px" cellpadding="3px" style="width:100%" >
											<thead style="font-weight: bold">
												<td style="text-align:center; width:50%">Nome</td>
												<td style="text-align:center; width:50%">Endereço de e-mail do responsável 1</td>
											</thead>
											<?php
											foreach ($db_pessoa_fetch as $singleRowAluno) {
											echo "<tr>";
											echo "<td style=\"text-align:center\">" . $singleRowAluno['NOME_ALUNO'] . "</td>";
											echo "<td style=\"text-align:center\">" . $singleRowAluno['EMAIL'] . "</td>";
											echo "</tr>";
										     }
											?>
											</table>												
											<?php } ?>
										</div>
									</div>
								</div>
								<a class="btn btn-primary btn-block"
									onclick= "validateAndSubmitForm()">Alterar</a>
						
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
$db->close();
$db1->close();
$db2->close();
$db3->close();
$db4->close();
$db5->close();
?>
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

$db0 = new db();
$db1 = new db();
$db3 = new db();
$db4 = new db();

$tipoPessoaId = $pessoa->tipo_pessoa;
$sqlTurmas = "";
if ($tipoPessoaId == 2 ){
    $sqlTurmas = "SELECT ID, NOME_TURMA FROM TURMA ORDER BY NOME_TURMA";
    
} else {
    $sqlTurmas = "SELECT t.ID, t.NOME_TURMA FROM PESSOA p JOIN TURMA_MATERIA tm ON (tm.ID_PROFESSOR = p.ID)
    JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and (tp.NOME = 'Professor(a)' OR tp.NOME = 'Diretor(a)'))
    JOIN TURMA t ON (t.ID = tm.ID_TURMA) ";
    $sqlTurmas .= " where p.ID = " . $pessoa->id;
    $sqlTurmas .= " ORDER BY t.NOME_TURMA";
}

if ($tipoPessoaId == 2 ){
    $sqlMateria = "SELECT ID, NOME FROM MATERIA ORDER BY NOME";
    
} else {
    $sqlTurmas = "SELECT t.ID, t.NOME_TURMA FROM PESSOA p JOIN TURMA_MATERIA tm ON (tm.ID_PROFESSOR = p.ID)
    JOIN TIPO_PESSOA tp ON (tp.ID = p.TIPO_PESSOA and (tp.NOME = 'Professor(a)' OR tp.NOME = 'Diretor(a)'))
    JOIN TURMA t ON (t.ID = tm.ID_TURMA) ";
    $sqlTurmas .= " where p.ID = " . $pessoa->id;
    $sqlTurmas .= " ORDER BY t.NOME_TURMA";
}

error_log($sqlTurmas);

$db_turma_fetch = $db0->query($sqlTurmas)->fetchAll();

if (isset($_POST['turma']) and
    isset($_POST['planoAula']) and
    isset($_POST['materia'])){
        $turma = $_POST['turma'];
        $planoAula = $_POST['planoAula'];
        $materia = $_POST['materia'];
        
        if (!empty(trim($turma)) and
            !empty(trim($planoAula))){
                try {
                    $result = $db1->query("INSERT INTO PLANO_AULA (ID_TURMA, DESCRICAO, ID_AUTOR, ID_MATERIA)
                          VALUES (?,?, ?, ?) "
                        , $turma
                        , $planoAula 
                        , $pessoa->id
                        , $materia
                        )->query_count;
                        if ($result == 1){
                            $showSuccessMessage = true;
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
<title>GSE - Cadastro de Plano de aula</title>
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
<script src="vendor/jquery/jquery.min.js"></script>

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
		var turma = document.getElementById("turma");
		var planoAula = document.getElementById("planoAula");
		var camposPreenchidos = true;
		 
		if (!isNotBlank(turma.value)){
			camposPreenchidos = false;
			document.getElementById("turmaErro").style.display = "block";
		} else {	
			document.getElementById("turmaErro").style.display = "none";
		}

		
		if (!isNotBlank(planoAula.value)){
			camposPreenchidos = false;
			document.getElementById("planoAulaErro").style.display = "block";
		} else {	
			document.getElementById("planoAulaErro").style.display = "none";
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
  <style type="text/css">
    textarea:focus {
        outline: none;
    }
  </style>
  
  <script type="text/javascript">

            $(document).ready(function(){
                $('#turma').on('change', function(){
                    var turmaId = $(this).val();
                    if(turmaId){
                        $.ajax({
                            type:'POST',
                            url:"carregarMateria.php",
                            data:'turma_id='+turmaId,
                            success: function(html) {
                                $('#materia').html(html);
                            }
                        });
                    }
                });
            });

            function carregaMateria(){
                var turmaId = $('#turma').val();
                if(turmaId){
                    $.ajax({
                        type:'POST',
                        url:"carregarMateria.php",
                        data:'turma_id='+turmaId,
                        success: function(html) {
                            $('#materia').html(html);
                        }
                    });
                }
                
            }    
             

</script>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top" onload="carregaMateria()">
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
				<li class="breadcrumb-item">Plano de aula</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
							<div class="form-group">
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="turma">Turma*</label> 
									<select
										class="form-control"
										aria-describedby="nameHelp" placeholder="Turma" id="turma" name="turma">
									
									<?php
                                            foreach ($db_turma_fetch as $single_row1) {
                                                echo "<option value=\"" . $single_row1['ID'] . "\">" . $single_row1['NOME_TURMA'] . "</option>";
                                            } 
                                        ?>
										
									</select>
									<br>
									<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
									<label for="turma">Materia*</label> 
									<select
										class="form-control"
										aria-describedby="nameHelp" id="materia" name="materia">
									</select>
									<div id="turmaErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
								</div>
								<br>
								<div class="col-md-6" style="flex: none;max-width: 100%; padding: 0px;">
								<label for="planoAula">Plano de aula*</label> 
									<textarea rows="10" cols="30" style="width: 100%; max-width:100%;border: 1px solid #ced4da" maxlength="100000" id="planoAula" name="planoAula"></textarea>
									<div id="planoAulaErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
								</div>
							</div>
					
        					<a class="btn btn-primary btn-block" onclick="validateAndSubmitForm()">Cadastrar</a>
					</form>
					</div>
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
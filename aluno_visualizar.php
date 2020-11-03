<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';
require 'PessoaDao.php';
require 'TipoPessoaCons.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;
use model\TipoPessoa;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4
);
ControleAcesso::validar($papeisPermitidos);

$showMessage=isset($_GET["s"]) and $_GET["s"] == 1;


$senha = '123456';

$db = new db();
$db0 = new db();

$showErrorMessage = null;
$showSuccessMessage = (isset($_SESSION['alunoAtualizadoComSucesso']) and $_SESSION['alunoAtualizadoComSucesso']);
$_SESSION['alunoAtualizadoComSucesso'] = null;

$pessoa_db = "select pe.ID, CONCAT(CONCAT(pe.NOME, ' '),pe.SOBRENOME) AS 'NOME', pe.DATA_NASCIMENTO, sex.SEXO, pe.RESPONSAVEL_1, pe.RESPONSAVEL_2,
	   CONCAT(CONCAT(resp1.NOME, ' '),resp1.SOBRENOME) AS 'NOME_RESP1', resp1.CPF AS 'CPF_RESP1', resp1.TELEFONE AS 'TELEFONE_RESP1', resp1.EMAIL AS 'EMAIL_RESP1', resp1.DATA_NASCIMENTO AS 'DATA_NASCIMENTO_RESP1', resp1.TIPO_SEXO AS 'TIPO_SEXO_RESP1',
	   CONCAT(CONCAT(resp2.NOME, ' '),resp2.SOBRENOME) AS 'NOME_RESP2', resp2.CPF AS 'CPF_RESP2', resp2.TELEFONE AS 'TELEFONE_RESP2', resp2.EMAIL AS 'EMAIL_RESP2', resp2.DATA_NASCIMENTO AS 'DATA_NASCIMENTO_RESP2', resp2.TIPO_SEXO AS 'TIPO_SEXO_RESP2' from PESSOA pe
			JOIN PESSOA resp1 ON (pe.RESPONSAVEL_1 = resp1.ID)
            left JOIN PESSOA resp2 ON (pe.RESPONSAVEL_2 = resp2.ID)
            JOIN SEXO sex ON (sex.ID = pe.TIPO_SEXO)
            ORDER BY NOME";

$db_pessoa_fetch = $db0->query($pessoa_db)->fetchAll();



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
<title>GSE - Cadastro de aluno</title>
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

<style>

th, td {
    vertical-align: middle !important;
} 
.active_pagina_atual {
    background-color: #e9ecef;
    border-color: #ced4da;
    color: #212529;
}

.active_pagina_atual:hover, .active_pagina_atual:focus {
    background-color: #212529 !important;
    border-color: #212529;
    color: white;
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
function abrirDetalhe(pessoaID){
	document.forms[0].pessoaID.value = pessoaID;
	document.forms[0].submit();
}

var data = [
	<?php
	foreach ($db_pessoa_fetch as $single_row1) {
        $data = "\t{\n";
        $data .= "\t\tdetail: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\"><span style=\"font-family:none; font-size: 18pt;\">&#9998;</span></a></center>',\n";
        $data .= "\t\tnomeAluno: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['NOME'] . "</a></center>',\n";
        $data .= "\t\tdataNascimentoAluno: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['DATA_NASCIMENTO'] . "</a></center>',\n";
        $data .= "\t\tsexoAluno: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['SEXO'] . "</a></center>',\n";
        
        $data .= "\t\tnomeResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['NOME_RESP1'] . "</a></center>',\n";
        $data .= "\t\tcpfResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['CPF_RESP1'] . "</a></center>',\n";
        $data .= "\t\ttelefoneResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TELEFONE_RESP1'] . "</a></center>',\n";
        $data .= "\t\temailResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['EMAIL_RESP1'] . "</a></center>',\n";
        $data .= "\t\tdataNascimentoResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['DATA_NASCIMENTO_RESP1'] . "</a></center>',\n";
        $data .= "\t\tsexoResp1: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TIPO_SEXO_RESP1'] . "</a></center>',\n";
        
        $data .= "\t\tnomeResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['NOME_RESP2'] . "</a></center>',\n";
        $data .= "\t\tcpfResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['CPF_RESP2'] . "</a></center>',\n";
        $data .= "\t\ttelefoneResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TELEFONE_RESP2'] . "</a></center>',\n";
        $data .= "\t\temailResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['EMAIL_RESP2'] . "</a></center>',\n";
        $data .= "\t\tdataNascimentoResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['DATA_NASCIMENTO_RESP2'] . "</a></center>',\n";
        $data .= "\t\tsexoResp2: '<center><a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TIPO_SEXO_RESP2'] . "</a></center>',\n";
        $data .= "\t},\n";
        echo $data;
}
?>    
]



var columns = {
	detail: '<center>Alterar</center>',
    nomeAluno: '<center>Nome do aluno</center>',
    // dataNascimentoAluno: 'Data de nascimento Aluno',
    sexoAluno: '<center>Sexo do aluno</center>',
    nomeResp1: '<center>Nome do responsável 1</center>',
    // cpfResp1: '<center>CPF Responsavel 1</center>',
    telefoneResp1: '<center>Telefone do responsável 1</center>',
    //emailResp1: 'E-Mail Responsavel 1',
    //dataNascimentoResp1: 'Data de nascimento Responsavel 1',
    //sexoResp1: 'Sexo Responsavel 1',
    nomeResp2: '<center>Nome do responsável 2</center>',
    //cpfResp2: 'CPF Responsavel 2',
    telefoneResp2: '<center>Telefone do responsável 2</center>',
    //emailResp2: 'E-Mail Responsavel 2',
    //dataNascimentoResp2: 'Data de nascimento Responsavel 2',
    //sexoResp2: 'Sexo Responsavel 2'
}

	function submit() {
		document.forms[0].submit();
	}

	function atualizarPagina(){
		document.forms[0].action = window.location.href;
		return false;
	} 
	
  </script>

</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
	<!-- Navigation-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"
		id="mainNav">
		<a class="navbar-brand" href="index.php">GSE - Gestão sócio educacional</a>
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
    if (isset($showErrorMessage)) {
        ?>
						<div style="color: red; text-align: center;"><?php echo $showErrorMessage ?> </br></br></div>
					<?php
    }

    if ($showSuccessMessage and ! isset($showErrorMessage)) {
        ?>
					    <div style="color: green; text-align: center;">Registro alterado
				com sucesso!</br></br></div>
					<?php
    }

    ?>
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Alunos</li>
				<li class="breadcrumb-item active">Visualizar</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body" style="padding-top: 0px;">
						<form method="post" action="aluno_alterar.php">
							<input type="hidden" id="pessoaID" name="pessoaID" />
							<div class="page-container" style="padding: 0px;">
								<div class="container">
									<div class="row mt-5 mb-3 align-items-center">
										<div class="col-md-5">
											<!--<button class="btn btn-primary btn-sm" id="rerender">Re-Render</button> 
                                            <button class="btn btn-primary btn-sm" id="distory">Distory</button> -->
											<button class="btn btn-primary btn-sm" id="refresh"
												style="background: #e9ecef; border-color: #ced4da; color: #212529;font-size: 1rem;" onclick="atualizarPagina();">Atualizar</button>
										</div>
										<div class="col-md-3">
											<input type="text" class="form-control"
												placeholder="Procure..." id="searchField">
										</div>
										<div class="col-md-2 text-right">
											<span class="pr-3">Registros por página:</span>
										</div>
										<div class="col-md-2">
											<div class="d-flex justify-content-end">
												<select class="custom-select" name="rowsPerPage"
													id="changeRows">
													<option value="1">1</option>
													<option value="5" selected>5</option>
													<option value="10">10</option>
													<option value="15">15</option>
												</select>
											</div>
										</div>
									</div>
									<div id="root"></div>
								</div>
							</div>	
							
						<script src="./table-sortable.js"></script>
							<script>
        var table = $('#root').tableSortable({
            data,
            columns,
            searchField: '#searchField',
            responsive: {
                1100: {
                    columns: {
                    	nomeAluno: 'Nome Aluno',
                        dataNascimentoAluno: 'Data de nascimento Aluno',
                        sexoAluno: 'Sexo Aluno',
                        nomeResp1: 'Nome Responsavel 1',
                        cpfResp1: 'CPF Responsavel 1',
                        telefoneResp1: 'Telefone Responsavel 1',
                        emailResp1: 'E-Mail Responsavel 1',
                        dataNascimentoResp1: 'Data de nascimento Responsavel 1',
                        sexoResp1: 'Sexo Responsavel 1',
                        nomeResp2: 'Nome Responsavel 2',
                        cpfResp2: 'CPF Responsavel 2',
                        telefoneResp2: 'Telefone Responsavel 2',
                        emailResp2: 'E-Mail Responsavel 2',
                        dataNascimentoResp2: 'Data de nascimento Responsavel 2',
                        sexoResp2: 'Sexo Responsavel 2'
                    },
                },
            },
            rowsPerPage: 5,
            pagination: true,
            tableWillMount: () => {
                console.log('table will mount')
            },
            tableDidMount: () => {
                console.log('table did mount')
            },
            tableWillUpdate: () => console.log('table will update'),
            tableDidUpdate: () => console.log('table did update'),
            tableWillUnmount: () => console.log('table will unmount'),
            tableDidUnmount: () => console.log('table did unmount'),
            onPaginationChange: function(nextPage, setPage) {
                setPage(nextPage);
            }
        });

        $('#changeRows').on('change', function() {
            table.updateRowsPerPage(parseInt($(this).val(), 10));
        })

        $('#rerender').click(function() {
            table.refresh(true);
        })

        $('#distory').click(function() {
            table.distroy();
        })

        $('#refresh').click(function() {
            table.refresh();
        })

        $('#setPage2').click(function() {
            table.setPage(1);
        })
    </script>
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
$db0->close();
?>
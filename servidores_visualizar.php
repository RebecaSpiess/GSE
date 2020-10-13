<?php
require 'bo/Sessao.php';
require 'bo/ControleAcesso.php';
require 'database/db.php';
require 'PessoaDao.php';

use bo\Sessao;
use bo\ControleAcesso;
use model\Pessoa;

Sessao::validar();

$papeisPermitidos = array(
    2,
    4
);
ControleAcesso::validar($papeisPermitidos);

$db = new db();
$db0 = new db();
$db1 = new db();

$pessoas_db = "select pe.ID, CONCAT(CONCAT(pe.NOME, ' '),pe.SOBRENOME) AS 'NOME', pe.EMAIL, pe.DATA_NASCIMENTO, sex.SEXO, pe.TELEFONE, pe.CPF, tp.NOME AS 'TIPO_PESSOA' from PESSOA pe 
			JOIN TIPO_PESSOA tp ON (pe.TIPO_PESSOA = tp.ID)
            JOIN SEXO sex ON (sex.ID = pe.TIPO_SEXO)
            where pe.TIPO_PESSOA <> 3 and pe.TIPO_PESSOA <> 5 ORDER BY NOME";

$db_servidores_fetch = $db0->query($pessoas_db)->fetchAll();

$showErrorMessage = null;
$showSuccessMessage = false;


if (isset($_POST['cpf']) and isset($_POST['telefone']) and isset($_POST['tipo_pessoa']) and isset($_POST['nome']) and isset($_POST['sobrenome']) and isset($_POST['email']) and isset($_POST['data_nascimento'])) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];
    $sexo = $_POST['sexoServidor'];
    $tipo_pessoa = $_POST['tipo_pessoa'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];

    if (! empty(trim($nome)) and ! empty(trim($sobrenome)) and ! empty(trim($email))
        and ! empty(trim($data_nascimento)) 
        and ! empty(trim($cpf)) and ! empty(trim($telefone))) {
        $pessoaDao = new PessoaDao();
        $pessoa = new Pessoa();
        $pessoa->nome = $nome;
        $pessoa->sobrenome = $sobrenome;
        $pessoa->email = $email;
        $pessoa->data_nascimento = $data_nascimento;
        $pessoa->sexo = intval($sexo);
        $pessoa->senha = hash('sha512', 'Start1234' . 'GSE'); // Senha padrão
        $pessoa->tipo_pessoa = intval($tipo_pessoa);
        $pessoa->cpf = $cpf;
        $pessoa->telefone = $telefone;
        $pessoa->responsavel1 = null;
        $pessoa->responsavel2 = null;
        try {
            $resultado = $pessoaDao->adicionarServidor($pessoa);
            if ($resultado) {
                $showSuccessMessage = true;
            }
        } catch (Exception $ex) {
            error_log($ex);
            $error_code = $ex->getMessage();
            if ($error_code == 1062) {
                $showErrorMessage = "Já existe um registro com o e-mail informado!";
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
<title>GSE - Visualizar servidor</title>
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


<script type="text/javascript">


function abrirDetalhe(pessoaID){
	document.forms[0].pessoaID.value = pessoaID;
	document.forms[0].submit();
}

var data = [
	<?php
	foreach ($db_servidores_fetch as $single_row1) {
        $data = "\t{\n";
        $data .= "\t\tdetail: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\"><center>&#9998;</center></a>',\n";
        $data .= "\t\tnome: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['NOME'] . "</a>',\n";
        $data .= "\t\temail: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['EMAIL'] . "</a>',\n";
        $data .= "\t\tdataNascimento: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['DATA_NASCIMENTO'] . "</a>',\n";
        $data .= "\t\tsexo: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['SEXO'] . "</a>',\n";
        $data .= "\t\ttelefone: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TELEFONE'] . "</a>',\n";
        $data .= "\t\tcpf: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['CPF'] . "</a>',\n";
        $data .= "\t\ttipoPessoa: '<a onclick=\"abrirDetalhe(" . $single_row1['ID'] . ")\">" . $single_row1['TIPO_PESSOA'] . "</a>',\n";
        $data .= "\t},\n";
        echo $data;
}
?>    
]



var columns = {
	detail: '<center>Alterar</center>',
    nome: 'Nome',
    email: 'E-Mail',
    dataNascimento: 'Data de nascimento',
    sexo: 'Sexo',
    telefone: 'Telefone',
    cpf: 'CPF',
    tipoPessoa: 'Cargo'
}

	function submit() {
		document.forms[0].submit();
	}

	function atualizarPagina(){
		document.forms[0].action = window.location.href;
		return false;
	}
	
  </script>
  
 <style type="text/css">

.active_pagina_atual {
    background-color: #e9ecef;
    border-color: #ced4da;
    color: #212529;
}

.active_pagina_atual:hover {
     background-color: #212529;
    border-color: #212529;
    color: white;
}

.mt-5, .my-5 {
    margin-top: 0px!important;
}
textarea:focus {
	outline: none;
}
</style> 
  
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
					href="#collapseExamplePages1" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Disciplinas</span>
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
					href="#collapseExamplePages2" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Frequência</span>
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
					href="#collapseExamplePages3" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Notas</span>
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
					href="#collapseExamplePages4" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Plano de
							aula</span>
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
					href="#collapseExamplePages5" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Ocorrências</span>
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
					href="#collapseExamplePages6" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Servidores</span>
				</a>
					<ul class="sidenav-second-level collapse"
						id="collapseExamplePages6">
						<li><a href="servidores_cadastro.php">Cadastro</a></li>
					</ul></li>
						<?php } ?>
				<li class="nav-item" data-toggle="tooltip" data-placement="right"
					title="Example Pages"><a
					class="nav-link nav-link-collapse collapsed" data-toggle="collapse"
					href="#collapseExamplePages7" data-parent="#exampleAccordion"> <i
						class="fa fa-fw fa-file"></i> <span class="nav-link-text">Turmas</span>
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
    if (isset($showErrorMessage)) {
        ?>
						<div style="color: red; text-align: center;"><?php echo $showErrorMessage ?> </br></br></div>
					<?php
    }

    if ($showSuccessMessage and ! isset($showErrorMessage)) {
        ?>
					    <div style="color: green; text-align: center;">Registro criado
					com sucesso!</br></br></div>
					<?php
    }

    ?>
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Servidores</li>
				<li class="breadcrumb-item active">Visualizar</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post" action="servidores_alterar.php">
							<input type="hidden" id="pessoaID" name="pessoaID" />
							<div class="page-container" style="padding: 0px;">
								<div class="container">
									<div class="row mt-5 mb-3 align-items-center">
										<div class="col-md-5">
											<!--<button class="btn btn-primary btn-sm" id="rerender">Re-Render</button> 
                                            <button class="btn btn-primary btn-sm" id="distory">Distory</button> -->
											<button class="btn btn-primary btn-sm" id="refresh"
												style="background: #e9ecef; border-color: #ced4da; color: #212529;" onclick="atualizarPagina();">Atualizar</button>
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
                        formTurma: 'Turma',
                        descricacao: 'Descrição',
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
$db0->close();
$db1->close();
?>

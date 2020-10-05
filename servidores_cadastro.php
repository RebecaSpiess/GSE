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
    4
);
ControleAcesso::validar($papeisPermitidos);

$db = new db();
$db1 = new db();

$tipo_pessoas_db = $db->query("SELECT * FROM TIPO_PESSOA WHERE ID <> 3 ORDER BY NOME");

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
        $pessoa = new Pessoa();
        $pessoa->nome = $nome;
        $pessoa->sobrenome = $sobrenome;
        $pessoa->email = $email;
        $pessoa->data_nascimento = $data_nascimento;
        $pessoa->sexo = intval($sexo);
        $pessoa->senha = 'Start1234'; // Senha padrão
        $enc_senha = hash('sha512', $pessoa->senha . 'GSE');
        $pessoa->tipo_pessoa = intval($tipo_pessoa);
        error_log("sexo");
        error_log($pessoa->sexo);
        
        error_log("tipo_pessoa");
        error_log($pessoa->tipo_pessoa);
        $pessoa->cpf = $cpf;
        $pessoa->telefone = $telefone;
        $pessoa->responsavel1 = null;
        $pessoa->responsavel2 = null;
        try {
            $result = $db1->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA, CPF, TELEFONE, RESPONSAVEL_1, RESPONSAVEL_2)
                          VALUES (?,?,?,?,?,?,?,?,?,?,?)", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $enc_senha, $pessoa->cpf, $pessoa->telefone, $pessoa->responsavel1, $pessoa->responsavel2)->query_count;
            if ($result == 1) {
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
<title>GSE - Cadastro de servidor</title>
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
		var nome = document.getElementById("nome");
		var sobreNome = document.getElementById("sobrenome");
		var email = document.getElementById("emailServidor");
		var dataNascimento = document.getElementById("data_nascimento");
		var sexo = document.getElementById("sexoServidor");
		var telefone = document.getElementById("telefone");
		var cpf = document.getElementById("cpf");
		var tipoPessoa = document.getElementById("tipoPessoa");

		
		var camposPreenchidos = true; 
		if (!isNotBlank(nome.value)){
			camposPreenchidos = false;
			document.getElementById("nomeErro").style.display = "block";
		} else {
			document.getElementById("nomeErro").style.display = "none";
		}
		
		if (!isNotBlank(sobreNome.value)){
			camposPreenchidos = false;
			document.getElementById("sobrenomeErro").style.display = "block";
		} else {
			document.getElementById("sobrenomeErro").style.display = "none";
		} 	

		if (!isNotBlank(email.value)){
			camposPreenchidos = false;
			document.getElementById("emailServidorErro").style.display = "block";
		} else {
			document.getElementById("emailServidorErro").style.display = "none";
		} 	

		if (!isNotBlank(dataNascimento.value)){
			camposPreenchidos = false;
			document.getElementById("data_nascimentoErro").style.display = "block";
		} else {
			document.getElementById("data_nascimentoErro").style.display = "none";
		} 

		if (!isNotBlank(telefone.value)){
			camposPreenchidos = false;
			document.getElementById("telefoneErro").style.display = "block";
		} else {
			document.getElementById("telefoneErro").style.display = "none";
		}  

		if (!isNotBlank(cpf.value)){
			camposPreenchidos = false;
			document.getElementById("cpfErro").style.display = "block";
		} else {
			document.getElementById("cpfErro").style.display = "none";
		}

		if (!isNotBlank(tipoPessoa.value)){
			camposPreenchidos = false;
		}
		
		if (camposPreenchidos){
			if (validateEmail(email.value)){
				replaceAll(cpf, ".","");
				replaceAll(cpf, "-","");

				replaceAll(telefone, "-","");
				replaceAll(telefone, "(","");
				replaceAll(telefone, ")","");
				submit();
			} else {
				alert('Você informou um endereço de e-mail inválido!');
			}		
		} else {
			alert('Preencha todos os campos obrigatórios!');
		}			
	}

	function replaceAll(campo, valor, replace){
		var stringFinal = campo.value;
		for(i = 0; i < stringFinal.length; i++){
			stringFinal = stringFinal.replace(valor, replace);
		}	
		campo.value = stringFinal;  
	}	
	
	function isNotBlank(value){
		if (value == null){
			return false;
		}
		return value.trim().length !== 0;
	}	

	function fMasc(objeto,mascara) {
		obj=objeto
		masc=mascara
		setTimeout("fMascEx()",1)
	}
	
	function fMascEx() {
		obj.value=masc(obj.value)
	}
	
	function mTel(tel) {
		tel=tel.replace(/\D/g,"")
		tel=tel.replace(/^(\d)/,"($1")
		tel=tel.replace(/(.{3})(\d)/,"$1)$2")
		if(tel.length == 9) {
			tel=tel.replace(/(.{1})$/,"-$1")
		} else if (tel.length == 10) {
			tel=tel.replace(/(.{2})$/,"-$1")
		} else if (tel.length == 11) {
			tel=tel.replace(/(.{3})$/,"-$1")
		} else if (tel.length == 12) {
			tel=tel.replace(/(.{4})$/,"-$1")
		} else if (tel.length > 12) {
			tel=tel.replace(/(.{4})$/,"-$1")
		}
		return tel;
	}

	function mCPF(cpf){
		cpf=cpf.replace(/\D/g,"")
		cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
		cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
		cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
		return cpf
	}

    function validateEmail(mail) { 
         if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
         }
         return false;
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
		<div class="container-fluid">
			<!-- Breadcrumbs-->
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Servidores</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container">
				<div>
					<div class="card-body">
						<form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6">
										<label for="exampleInputName">Nome*</label> <input
											class="form-control" id="nome" type="text"
											aria-describedby="nameHelp" placeholder="Nome" name="nome"
											maxlength="250">
										<div id="nomeErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
								<div class="col-md-6">
									<label for="exampleInputLastName">Sobrenome*</label> <input
										class="form-control" id="sobrenome" type="text"
										aria-describedby="nameHelp" placeholder="Sobrenome"
										name="sobrenome" maxlength="250">
									<div id="sobrenomeErro"
										style="display: none; font-size: 10pt; color: red">Campo
										obrigatório!</div>
								</div>
							</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Endereço de E-Mail*</label> <input
							class="form-control" id="emailServidor" type="email"
							aria-describedby="emailHelp" placeholder="exemplo@exemplo.com"
							maxlength="250" name="email">
						<div id="emailServidorErro"
							style="display: none; font-size: 10pt; color: red">Campo
							obrigatório!</div>
					</div>

					<div class="form-group">
						<div class="form-row">
							<div class="col-md-6">
								<label for="exampleInputName">Data de nascimento*</label> <input
									class="form-control" id="data_nascimento" type="date"
									aria-describedby="nameHelp" placeholder="Data de nascimento"
									name="data_nascimento">
								<div id="data_nascimentoErro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
							<div class="col-md-6">
								<label for="typeSexo">Sexo*</label><br> <input type="radio" 
								name="sexoServidor" id="sexoServidor" value="1" checked required> Masculino<br> 
								<input type="radio" name="sexoServidor" value="0" id="sexoServidor" required> Feminino<br> 
								<input type="radio" name="sexoServidor" value="2" id="sexoServidor" required> Não deseja informar<br>
								<input type="radio" name="sexoServidor" value="3" id="sexoServidor" required> Outro<br>
								<div id="sexoServidor" style="display: none; font-size: 10pt; color: red">Campo obrigatório!</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-row">
							<div class="col-md-6">
								<label for="exampleInputName">Telefone*</label> <input
									class="form-control" id="telefone" type="text"
									placeholder="Telefone" name="telefone" maxlength="13"
									onkeydown="javascript: fMasc( this, mTel );">
								<div id="telefoneErro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
							<div class="col-md-6">
								<label for="exampleInputLastName">CPF*</label> <input
									class="form-control cpf-mask" id="cpf" type="text"
									placeholder="000.000.000-00" name="cpf" maxlength="14"
									onkeydown="javascript: fMasc( this, mCPF );">
								<div id="cpfErro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
						
							<div class="col-md-6">
								<br>
								<label for="exampleInputLastName">Tipo pessoa*</label> <select
									class="form-control" id="tipoPessoa"
									aria-describedby="nameHelp" name="tipo_pessoa">
											<?php
        $tipo_pessoas_db_fetch = $tipo_pessoas_db->fetchAll();
        foreach ($tipo_pessoas_db_fetch as $single_row) {
            echo "<option value=\"" . $single_row['ID'] . "\">" . $single_row['NOME'] . "</option>";
        }
        ?>

										</select>
							</div>
						</div>
					</div>
					<a class="btn btn-primary btn-block"
						onclick="validateAndSubmitForm()">Cadastrar</a>
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
$db->close();
$db1->close();
?>

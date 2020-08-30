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

$showErrorMessage = null;
$showSuccessMessage = false;

if (isset($_POST['nome']) and isset($_POST['sobrenome']) and isset($_POST['email1']) and isset($_POST['data_nascimento']) and isset($_POST['sexo'])) {

    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email1'];
    $data_nascimento = $_POST['data_nascimento'];
    $sexo = $_POST['sexo'];

    if (! empty(trim($nome)) and ! empty(trim($sobrenome)) and ! empty(trim($email)) and ! empty(trim($data_nascimento))) {
        echo("E0");
        $pessoa = new Pessoa();
        $pessoa->nome = $nome;
        $pessoa->sobrenome = $sobrenome;
        $pessoa->email = $email;
        $pessoa->data_nascimento = $data_nascimento;
        $pessoa->sexo = $sexo;
        $pessoa->senha = '123456'; // Senha padrão para se logar no sistema
        $enc_senha = hash('sha512', $pessoa->senha . 'GSE');
        $pessoa->tipo_pessoa = 3; // Aluno
        try {
            //$result = $db->query("INSERT INTO PESSOA (NOME, SOBRENOME, EMAIL, DATA_NASCIMENTO, TIPO_SEXO, TIPO_PESSOA, SENHA)
            //              VALUES (?,?,?,?,?,?,?) ", $pessoa->nome, $pessoa->sobrenome, $pessoa->email, $pessoa->data_nascimento, $pessoa->sexo, $pessoa->tipo_pessoa, $enc_senha)->query_count;
            //if ($result == 1) {
            //    $showSuccessMessage = true;
            //}
            $pessoaDao = new PessoaDao();
            $pessoaDao->adicionar($pessoa);
        } catch (Exception $ex) {
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

<script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}
  
	function validateAndSubmitForm() {
		var nome = document.getElementById("nome");
		var sobreNome = document.getElementById("sobrenome");
		var data_nascimento = document.getElementById("nascimento");
		var sexo = document.getElementById("sexo");
		var nomeResponsavel1 = document.getElementById("nomeResponsavel1");
		var sobrenomeResponsavel1 = document.getElementById("sobrenomeResponsavel1");
		var cpf = document.getElementById("cpf");
		var data_nascimentoResp1 = document.getElementById("nascimentoResp1");
		var sexoResp1 = document.getElementById("sexoResp1");
		var email1 = document.getElementById("email1");
		var camposPreenchidos = true; 
		if (!isNotBlank(nome.value)){
			camposPreenchidos = false;
			document.getElementById("name").style.display = "block";
		} else {
			camposPreenchidos = true;
			document.getElementById("name").style.display = "none";
		}	

		if (!isNotBlank(sobreNome.value)){
			camposPreenchidos = false;
			document.getElementById("sobrenomeErro").style.display = "block";
		} else {
			camposPreenchidos = true;				
			document.getElementById("sobrenomeErro").style.display = "none";
		}	

		if (!isNotBlank(data_nascimento.value)){
			camposPreenchidos = false;
			document.getElementById("dataNascimentoErro").style.display = "block";
		} else {		
			camposPreenchidos = true;		
			document.getElementById("dataNascimentoErro").style.display = "none";
		}

		if (!isNotBlank(sexo.value)){
			camposPreenchidos = false;
			document.getElementById("sexoErro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("sexoErro").style.display = "none";
		}
		
		if (!isNotBlank(nomeResponsavel1.value)){
			camposPreenchidos = false;
			document.getElementById("nomeResponsavel1Erro").style.display = "block";
		} else {
			camposPreenchidos = true;
			document.getElementById("nomeResponsavel1Erro").style.display = "none";
		}	

		if (!isNotBlank(sobrenomeResponsavel1.value)){
			camposPreenchidos = false;
			document.getElementById("sobrenomeResponsavel1Erro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("sobrenomeResponsavel1Erro").style.display = "none";
		}	

		if (!isNotBlank(data_nascimentoResp1.value)){
			camposPreenchidos = false;
			document.getElementById("dataNascimentoResp1Erro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("dataNascimentoResp1Erro").style.display = "none";
		}

		if (!isNotBlank(sexoResp1.value)){
			camposPreenchidos = false;
			document.getElementById("sexoRespErro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("sexoRespErro").style.display = "none";
		}
		
		if (!isNotBlank(email1.value)){
			camposPreenchidos = false;
			document.getElementById("emailValidacaoErro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("emailValidacaoErro").style.display = "none";
		}
			
		if (!isNotBlank(cpf.value)){
			camposPreenchidos = false;
			document.getElementById("cpfErro").style.display = "block";
		} else {	
			camposPreenchidos = true;			
			document.getElementById("cpfErro").style.display = "none";
		}

		if (camposPreenchidos){
			if (validateEmail(email1.value)){
				submit();
			} else {
				alert('Você informou um endereço de e-mail inválido!');
			}		
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
				<li class="breadcrumb-item">Alunos</li>
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
											required maxlength="250">
										<div id="name"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
									<div class="col-md-6">
										<label for="exampleInputLastName">Sobrenome*</label> <input
											class="form-control" id="sobrenome" type="text"
											aria-describedby="nameHelp" placeholder="Sobrenome"
											name="sobrenome" required maxlength="250">
										<div id="sobrenomeErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6">
										<label for="exampleInputName">Data de nascimento*</label> <input
											class="form-control date-mask" id="nascimento"
											name="data_nascimento" type="date"
											aria-describedby="nameHelp" placeholder="Data de nascimento"
											required>
										<div id="dataNascimentoErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
									<div class="col-md-6">
										<label for="typeSexo">Sexo*</label><br> <input type="radio"
											name="sexo" id="sexo" value="1" checked required> Masculino<br>
										<input type="radio" name="sexo" value="0" id="sexo" required> Feminino<br>
										<div id="sexoErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
								</div>
							</div>
						<!--  </form> -->
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Resposáveis</li>
				<li class="breadcrumb-item active">Cadastro</li>
			</ol>
			<div class="container" style="padding-left: 30px;">
			<!--   <form method="post" action="<?=$_SERVER['PHP_SELF'];?>"> -->
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputLastName">Nome do responsável 1*</label>
							<input class="form-control" id="nomeResponsavel1" type="text"
								aria-describedby="nameHelp" placeholder="Nome responsável 1"
								name="nomeResponsavel1" required maxlength="250">
							<div id="nomeResponsavel1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="exampleInputLastName">Sobrenome do responsável 1*</label>
							<input class="form-control" id="sobrenomeResponsavel1"
								type="text" aria-describedby="nameHelp"
								placeholder="Nome responsável 1" name="sobrenomeResponsavel1"
								required maxlength="250">
							<div id="sobrenomeResponsavel1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1"> CPF do responsável 1*</label> <input
						class="form-control cpf-mask" id="cpf" type="text"
						placeholder="000.000.000-00" name="cpf" maxlength="14"
						onkeydown="javascript: fMasc( this, mCPF );">
					<div id="cpfErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputName">Data de nascimento*</label> <input
								class="form-control date-mask" id="nascimentoResp1"
								name="data_nascimentoResp1" type="date" aria-describedby="nameHelp"
								placeholder="Data de nascimento" required>
							<div id="dataNascimentoResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="typeSexo">Sexo*</label><br> <input type="radio"
								name="sexoResp1" id="sexoResp1" value="1" checked required> Masculino<br>
							<input type="radio" name="sexoResp1" value="0" id="sexoResp1" required> Feminino<br>
							<div id="sexoRespErro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1">Endereço de E-Mail do responsável
						1*</label> <input class="form-control" id="email1" type="text"
						name="email1" aria-describedby="emailHelp"
						placeholder="E-Mail usado para encaminhamento de comunicados"
						required maxlength="250">
					<div id="emailValidacaoErro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputLastName">Nome do responsável 2</label> <input
								class="form-control" id="nomeResponsavel2" type="text"
								aria-describedby="nameHelp" placeholder="Nome responsável 2"
								name="nomeResponsavel1" required maxlength="250">
						</div>
						<div class="col-md-6">
							<label for="exampleInputLastName">Sobrenome do responsável 2</label>
							<input class="form-control" id="sobrenomeResponsavel2"
								type="text" aria-describedby="nameHelp"
								placeholder="Nome responsável 2" name="sobrenomeResponsavel2"
								required maxlength="250">
						</div>
						
					</div>
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1"> CPF do responsável 2</label> <input
						class="form-control cpf-mask" id="cpf2" type="text"
						placeholder="000.000.000-00" name="cpf2" maxlength="14"
						onkeydown="javascript: fMasc( this, mCPF );">
				</div>
								<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputName">Data de nascimento</label> <input
								class="form-control date-mask" id="nascimentoResp2"
								name="data_nascimentoResp2" type="date" aria-describedby="nameHelp"
								placeholder="Data de nascimento" required>
						</div>
						<div class="col-md-6">
							<label for="typeSexo">Sexo</label><br> <input type="radio"
								name="sexoResp2" id="sexoResp2" value="1" checked required> Masculino<br>
							<input type="radio" name="sexoResp2" value="0" id="sexoResp2" required> Feminino<br>
						</div>
					</div>
				</div>
				<a class="btn btn-primary btn-block"
					onclick="validateAndSubmitForm()">Cadastrar</a>

				</form>
			</div>
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

	</div>
</body>

</html>

<?php
$db->close();
?>
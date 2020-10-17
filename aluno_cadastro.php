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

function verificaResp2() {
    if (isset($_POST['nomeResp2'])
        or isset($_POST['sobrenomeResp2'])
        or isset($_POST['cpfResp2'])
        or isset($_POST['dataNascimentoResp2'])
        or isset($_POST['emailResp2'])
        or isset($_POST['telefoneResp2'])){
            return (error_log("true")
            and isset($_POST['nomeResp2'])
            and isset($_POST['sobrenomeResp2'])
            and isset($_POST['cpfResp2'])
            and isset($_POST['dataNascimentoResp2'])
            and isset($_POST['emailResp2'])
            and isset($_POST['telefoneResp2']));
    } else {
        return true;
    }
}

$showMessage=isset($_GET["s"]) and $_GET["s"] == 1;


$senha = '123456';

$db = new db();

$showErrorMessage = null;
$showSuccessMessage = false;

if (
    isset($_POST['nomeAluno']) 
    and isset($_POST['sobrenomeAluno'])
    and isset($_POST['dataNascimentoAluno'])
    and isset($_POST['nomeResp1'])
    and isset($_POST['sobrenomeResp1'])
    and isset($_POST['cpfResp1'])
    and isset($_POST['dataNascimentoResp1'])
    and isset($_POST['emailResp1'])
    and isset($_POST['telefoneResp1'])
    and verificaResp2()) {
        
        
        $nomeResp1 = $_POST['nomeResp1'];
        $sobrenomeResp1 = $_POST['sobrenomeResp1'];
        $dataNascimentoResp1 = $_POST['dataNascimentoResp1'];
        $cpfResp1 = $_POST['cpfResp1'];
        $sexoResp1 = $_POST['sexoResp1'];
        $emailResp1 = $_POST['emailResp1'];
        $telefoneResp1 = $_POST['telefoneResp1'];
        
        $nomeResp2 = $_POST['nomeResp2'];
        $sobrenomeResp2 = $_POST['sobrenomeResp2'];
        $dataNascimentoResp2 = $_POST['dataNascimentoResp2'];
        $cpfResp2 = $_POST['cpfResp2'];
        $sexoResp2 = $_POST['sexoResp2'];
        $emailResp2 = $_POST['emailResp2'];
        $telefoneResp2 = $_POST['telefoneResp2'];
        
        
        $nomeAluno = $_POST['nomeAluno'];
        $sobrenomeAluno = $_POST['sobrenomeAluno'];
        $dataNascimentoAluno = $_POST['dataNascimentoAluno'];
        $sexoAluno = $_POST['sexoAluno'];
        
        if (!empty(trim($nomeAluno)) 
            and !empty(trim($sobrenomeAluno)) 
            and !empty(trim($dataNascimentoAluno))
            and !empty(trim($nomeResp1))
            and !empty(trim($sobrenomeResp1))
            and !empty(trim($dataNascimentoResp1))
            and !empty(trim($cpfResp1))
            and !empty(trim($emailResp1))
            and !empty(trim($telefoneResp1))
            ) {
            
            $pessoaDao = new PessoaDao();
                
            $enc_senha = hash('sha512', $senha . 'GSE');
                
            $responsavel1Cadastro = new Pessoa();
            $responsavel1Cadastro->nome = $nomeResp1;
            $responsavel1Cadastro->sobrenome = $sobrenomeResp1;
            $responsavel1Cadastro->data_nascimento = $dataNascimentoResp1;
            $responsavel1Cadastro->cpf = $cpfResp1;
            $responsavel1Cadastro->telefone = $telefoneResp1;
            $responsavel1Cadastro->sexo = intval($sexoResp1);
            $responsavel1Cadastro->senha = $enc_senha;
            $responsavel1Cadastro->email = $emailResp1;
            $responsavel1Cadastro->tipo_pessoa = TipoPessoaCons::RESPONSAVEL;
            $responsavel1Cadastro->responsavel1 = null;
            $responsavel1Cadastro->responsavel2 = null;
            
            $responsavel2Cadastro = new Pessoa();
            if (isset($emailResp2) and !empty($emailResp2)){
                
                $responsavel2Cadastro->nome = $nomeResp2;
                $responsavel2Cadastro->sobrenome = $sobrenomeResp2;
                $responsavel2Cadastro->data_nascimento = $dataNascimentoResp2;
                $responsavel2Cadastro->cpf = $cpfResp2;
                $responsavel2Cadastro->telefone = $telefoneResp2;
                $responsavel2Cadastro->sexo = intval($sexoResp2);
                $responsavel2Cadastro->senha = $enc_senha;
                $responsavel2Cadastro->email = $emailResp2;
                $responsavel2Cadastro->tipo_pessoa = TipoPessoaCons::RESPONSAVEL;
                $responsavel2Cadastro->responsavel1 = null;
                $responsavel2Cadastro->responsavel2 = null;
            }
            
            try {
                $pessoaDao->adicionar($responsavel1Cadastro, true);
                
                if (isset($emailResp2) and !empty($emailResp2)){
                    $pessoaDao->adicionar($responsavel2Cadastro, true);
                }
                
                $alunoCadastro = new Pessoa();
                
                $alunoCadastro->nome = $nomeAluno;
                $alunoCadastro->sobrenome = $sobrenomeAluno;
                $alunoCadastro->data_nascimento = $dataNascimentoAluno;
                $alunoCadastro->sexo = $sexoAluno;
                $alunoCadastro->senha = $enc_senha;
                $alunoCadastro->tipo_pessoa = TipoPessoaCons::ALUNO;
                $alunoCadastro->responsavel1 = $responsavel1Cadastro->id;
                if (!empty($emailResp2)){
                    $alunoCadastro->responsavel2 = $responsavel2Cadastro->id;
                }
                $pessoaDao->adicionar($alunoCadastro, false);
                $showSuccessMessage = true;
            } catch (Exception $ex) {
                $error_code = $ex->getMessage();
                error_log($ex);
                if ($error_code == 1062) {
                    $showErrorMessage = "Já existe um registro com o e-mail informado!";
                } else {
                    $showErrorMessage = "Ocorreu um erro interno! Contate o administrador do sistema!";
                }
            }
        } else {
            error_log("Existem campos vazios que devem ser preenchidos!");
        }
    } else {
        error_log("Existem campos não setados que devem ser preenchidos e enviados pelo form!");
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

<style type="text/css">

.btn-primary {
    color: black !important;
    background-color: #e9ecef !important;
    border-color: black !important;
}

</style>

<script type="text/javascript">
	function submit() {
		document.forms[0].submit();
	}

	function fieldVazio(field){
		return field != null && !isNotBlankWithoutTrim(field.value, false); 
	}	
		
	
	function existeAlgumCampoPreenchidoResp2(nomeResp2, sobrenomeResp2, cpfResp2, dataNascimentoResp2, 
			emailResp2, telefoneResp2){
		var work = null;

		if (!fieldVazio(nomeResp2)){
			return true;
		}

		if (!fieldVazio(sobrenomeResp2)){
			return true;
		}	

		if (!fieldVazio(cpfResp2)){
			return true;
		}

		if (!fieldVazio(dataNascimentoResp2)){
			return true;
		}

		if (!fieldVazio(emailResp2)){
			return true;
		}

		if (!fieldVazio(telefoneResp2)){
			return true;
		}
		return false;
	}	

	
	
	function validateAndSubmitForm() {
		//campos do aluno
		var nomeAluno = document.getElementById("nomeAluno");
		var sobreNomeAluno = document.getElementById("sobrenomeAluno");
		var dataNascimentoAluno = document.getElementById("dataNascimentoAluno");
		var sexoAluno = document.getElementById("sexoAluno");

		//campos do responsável 1
		var nomeResp1 = document.getElementById("nomeResp1");
		var sobrenomeResp1 = document.getElementById("sobrenomeResp1");
		var cpfResp1 = document.getElementById("cpfResp1");
		var dataNascimentoResp1 = document.getElementById("dataNascimentoResp1");
		var sexoResp1 = document.getElementById("sexoResp1");
		var emailResp1 = document.getElementById("emailResp1");
		var telefoneResp1 = document.getElementById("telefoneResp1");

		//campos do responsável 2
		var nomeResp2 = document.getElementById("nomeResp2");
		var sobrenomeResp2 = document.getElementById("sobrenomeResp2");
		var cpfResp2 = document.getElementById("cpfResp2");
		var dataNascimentoResp2 = document.getElementById("dataNascimentoResp2");
		var sexoResp2 = document.getElementById("sexoResp2");
		var emailResp2 = document.getElementById("emailResp2");
		var telefoneResp2 = document.getElementById("telefoneResp2");

		var validarCamposResp2 = existeAlgumCampoPreenchidoResp2(nomeResp2, sobrenomeResp2, cpfResp2, dataNascimentoResp2, 
				emailResp2, telefoneResp2);
			
		var camposPreenchidos = true; 
		if (!isNotBlank(nomeAluno.value)){
			camposPreenchidos = false;
			document.getElementById("nomeAlunoErro").style.display = "block";
		} else {
			document.getElementById("nomeAlunoErro").style.display = "none";
		}	

		if (!isNotBlank(sobreNomeAluno.value)){
			camposPreenchidos = false;
			document.getElementById("sobrenomeAlunoErro").style.display = "block";
		} else {
			document.getElementById("sobrenomeAlunoErro").style.display = "none";
		}	

		if (!isNotBlank(dataNascimentoAluno.value)){
			camposPreenchidos = false;
			document.getElementById("dataNascimentoAlunoErro").style.display = "block";
		} else if (!validateInputDate(dataNascimentoAluno.value)) {
			camposPreenchidos = false;
			document.getElementById("dataNascimentoAlunoErro").innerHTML = "Data informada não pode estar no futuro!";
			document.getElementById("dataNascimentoAlunoErro").style.display = "block";	
		} else {		
			document.getElementById("dataNascimentoAlunoErro").style.display = "none";
		}

		if (!isNotBlank(sexoAluno.value)){
			camposPreenchidos = false;
			document.getElementById("sexoAlunoErro").style.display = "block";
		} else {	
			document.getElementById("sexoAlunoErro").style.display = "none";
		}
		
		if (!isNotBlank(nomeResp1.value)){
			camposPreenchidos = false;
			document.getElementById("nomeResp1Erro").style.display = "block";
		} else {
			document.getElementById("nomeResp1Erro").style.display = "none";
		}	

		if (!isNotBlank(sobrenomeResp1.value)){
			camposPreenchidos = false;
			document.getElementById("sobrenomeResp1Erro").style.display = "block";
		} else {	
			document.getElementById("sobrenomeResp1Erro").style.display = "none";
		}	

		if (!isNotBlank(dataNascimentoResp1.value)){
			camposPreenchidos = false;
			document.getElementById("dataNascimentoResp1Erro").style.display = "block";
		} else if (!validateInputDate(dataNascimentoResp1.value)) {
			camposPreenchidos = false;
			document.getElementById("dataNascimentoResp1Erro").innerHTML = "Data informada não pode estar no futuro!";
			document.getElementById("dataNascimentoResp1Erro").style.display = "block";	
		} else {	
			document.getElementById("dataNascimentoResp1Erro").style.display = "none";
		}

		if (!isNotBlank(sexoResp1.value)){
			camposPreenchidos = false;
			document.getElementById("sexoResp1Erro").style.display = "block";
		} else {	
			document.getElementById("sexoResp1Erro").style.display = "none";
		}
		
		if (!isNotBlank(emailResp1.value)){
			camposPreenchidos = false;
			document.getElementById("emailResp1Erro").style.display = "block";
		} else if (!validateEmail(emailResp1.value)){
			document.getElementById("emailResp1Erro").innerHTML = "Você informou um endereço de e-mail inválido!"; 
			document.getElementById("emailResp1Erro").style.display = "block";
			camposPreenchidos = false;
	    } else {	
			document.getElementById("emailResp1Erro").style.display = "none";
		}
			
		if (!isNotBlank(cpfResp1.value)){
			camposPreenchidos = false;
			document.getElementById("cpfResp1Erro").style.display = "block";
		} else {	
			document.getElementById("cpfResp1Erro").style.display = "none";
		}

		if (!isNotBlank(telefoneResp1.value)){
			camposPreenchidos = false;
			document.getElementById("telefoneResp1Erro").style.display = "block";
		} else {	
			document.getElementById("telefoneResp1Erro").style.display = "none";
		}

		if (validarCamposResp2){
			if (!isNotBlank(nomeResp2.value)){
				camposPreenchidos = false;
				document.getElementById("nomeResp2Erro").style.display = "block";
			} else {
				document.getElementById("nomeResp2Erro").style.display = "none";
			}	

			if (!isNotBlank(sobrenomeResp2.value)){
				camposPreenchidos = false;
				document.getElementById("sobrenomeResp2Erro").style.display = "block";
			} else {	
				document.getElementById("sobrenomeResp2Erro").style.display = "none";
			}	

			if (!isNotBlank(dataNascimentoResp2.value)){
				camposPreenchidos = false;
				document.getElementById("dataNascimentoResp2Erro").style.display = "block";
			} else if (!validateInputDate(dataNascimentoResp2.value)) {
				camposPreenchidos = false;
				document.getElementById("dataNascimentoResp2Erro").innerHTML = "Data informada não pode estar no futuro!";
				document.getElementById("dataNascimentoResp2Erro").style.display = "block";	
			} else {	
				document.getElementById("dataNascimentoResp2Erro").style.display = "none";
			}

			if (!isNotBlank(sexoResp2.value)){
				camposPreenchidos = false;
				document.getElementById("sexoResp2Erro").style.display = "block";
			} else {	
				document.getElementById("sexoResp2Erro").style.display = "none";
			}
			
			if (!isNotBlank(emailResp2.value)){
				camposPreenchidos = false;
				document.getElementById("email2ValidacaoErro").style.display = "block";
			} else if (!validateEmail(emailResp2.value)){
				document.getElementById("email2ValidacaoErro").innerHTML = "Você informou um endereço de e-mail inválido!"; 
				document.getElementById("email2ValidacaoErro").style.display = "block";
				camposPreenchidos = false;
		    } else {	
				document.getElementById("email2ValidacaoErro").style.display = "none";
			}
				
			if (!isNotBlank(cpfResp2.value)){
				camposPreenchidos = false;
				document.getElementById("cpfResp2Erro").style.display = "block";
			} else {	
				document.getElementById("cpfResp2Erro").style.display = "none";
			}

			if (!isNotBlank(telefoneResp2.value)){
				camposPreenchidos = false;
				document.getElementById("telefoneResp2Erro").style.display = "block";
			} else {	
				document.getElementById("telefoneResp2Erro").style.display = "none";
			}
		}	

		if (camposPreenchidos){
			submit();
		}		
	}

	function replaceAll(campo, valor, replace){
		var stringFinal = campo.value;
		for(i = 0; i < stringFinal.length; i++){
			stringFinal = stringFinal.replace(valor, replace);
		}	
		campo.value = stringFinal;  
	}	

	function isNotBlankWithoutTrim(value){
		if (value == null){
			return false;
		}
		return value.length !== 0;	
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
		return cpf;
	}

	function mTelefone(telefone){
		telefone=telefone.replace(/\D/g,"")
		telefone=telefone.replace(/(\d{3})(\d)/,"$1.$2")
		telefone=telefone.replace(/(\d{3})(\d)/,"$1.$2")
		telefone=telefone.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
		return telefone;
	}
	
    function validateEmail(mail) { 
         if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return true;
         }
         return false;
    }

    function validateInputDate(inputDate){
        var agora = new Date();
        var diaInserido = new Date(inputDate);
		if (diaInserido > agora) {
			return false;
		}	
        return true;
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
											class="form-control" id="nomeAluno" type="text"
											aria-describedby="nameHelp" placeholder="Nome" name="nomeAluno"
											required maxlength="250">
										<div id="nomeAlunoErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
									<div class="col-md-6">
										<label for="exampleInputLastName">Sobrenome*</label> <input
											class="form-control" id="sobrenomeAluno" type="text"
											aria-describedby="nameHelp" placeholder="Sobrenome"
											name="sobrenomeAluno" required maxlength="250">
										<div id="sobrenomeAlunoErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="form-row">
									<div class="col-md-6">
										<label for="exampleInputName">Data de nascimento*</label> <input
											class="form-control date-mask" id="dataNascimentoAluno"
											name="dataNascimentoAluno" type="date"
											aria-describedby="nameHelp" placeholder="Data de nascimento"
											required>
										<div id="dataNascimentoAlunoErro"
											style="display: none; font-size: 10pt; color: red">Campo
											obrigatório!</div>
									</div>
									<div class="col-md-6">
										<label for="typeSexo">Sexo*</label><br> <input type="radio"
											name="sexoAluno" id="sexoAluno" value="1" checked required> Masculino<br>
										<input type="radio" name="sexoAluno" value="0" id="sexo" required> Feminino<br>
										<input type="radio" name="sexoAluno" value="2" id="sexo" required> Não deseja informar<br>
										<input type="radio" name="sexoAluno" value="3" id="sexo" required> Outro<br>
										<div id="sexoAlunoErro"
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
							<input class="form-control" id="nomeResp1" type="text"
								aria-describedby="nameHelp" placeholder="Nome" name="nomeResp1"
								required maxlength="250">
							<div id="nomeResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="exampleInputLastName">Sobrenome do responsável 1*</label>
							<input class="form-control" id="sobrenomeResp1" type="text"
								aria-describedby="nameHelp" placeholder="Sobrenome"
								name="sobrenomeResp1" required maxlength="250">
							<div id="sobrenomeResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="inputCpfResp1"> CPF do responsável 1*</label> <input
								class="form-control cpf-mask" id="cpfResp1" type="text"
								placeholder="000.000.000-00" name="cpfResp1" maxlength="14"
								onkeydown="javascript: fMasc( this, mCPF );">
							<div id="cpfResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="inputTelefoneResp1"> Telefone do responsável 1*</label>
							<input class="form-control" id="telefoneResp1" type="text"
								placeholder="(00) 0000-0000" name="telefoneResp1" maxlength="14"
								onkeydown="javascript: fMasc( this, mTel );">
							<div id="telefoneResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1">Endereço de e-mail do responsável
						1*</label> <input class="form-control" id="emailResp1" type="text"
						name="emailResp1" aria-describedby="emailHelp"
						placeholder="E-mail usado para encaminhamento de comunicados"
						required maxlength="250">
					<div id="emailResp1Erro"
						style="display: none; font-size: 10pt; color: red">Campo
						obrigatório!</div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputName">Data de nascimento*</label> <input
								class="form-control date-mask" id="dataNascimentoResp1"
								name="dataNascimentoResp1" type="date"
								aria-describedby="nameHelp" placeholder="Data de nascimento"
								required>
							<div id="dataNascimentoResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="typeSexo">Sexo*</label><br> 
							<input type="radio" name="sexoResp1" id="sexoResp1" value="1" checked required> Masculino<br> 
							<input type="radio" name="sexoResp1" value="0" id="sexoResp1" required> Feminino<br> 
							<input type="radio" name="sexoResp1" value="2" id="sexo" required> Não deseja informar<br>
							<input type="radio" name="sexoResp1" value="3" id="sexo" required> Outro<br>
							<div id="sexoResp1Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
				</div>
				<br>
				<br>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-6">
							<label for="exampleInputLastName">Nome do responsável 2</label> <input
								class="form-control" id="nomeResp2" type="text"
								aria-describedby="nameHelp" placeholder="Nome" name="nomeResp2"
								required maxlength="250">
							<div id="nomeResp2Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
						<div class="col-md-6">
							<label for="exampleInputLastName">Sobrenome do responsável 2</label>
							<input class="form-control" id="sobrenomeResp2" type="text"
								aria-describedby="nameHelp" placeholder="Sobrenome"
								name="sobrenomeResp2" required maxlength="250">
							<div id="sobrenomeResp2Erro"
								style="display: none; font-size: 10pt; color: red">Campo
								obrigatório!</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-row">
							<div class="col-md-6">
								<label for="exampleInputEmail1"> CPF do responsável 2</label> <input
									class="form-control cpf-mask" id="cpfResp2" type="text"
									placeholder="000.000.000-00" name="cpfResp2" maxlength="14"
									onkeydown="javascript: fMasc( this, mCPF );">
								<div id="cpfResp2Erro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
							<div class="col-md-6">
								<label for="inputTelefoneResp1"> Telefone do responsável 2*</label>
								<input class="form-control" id="telefoneResp2" type="text"
									placeholder="(00) 0000-0000" name="telefoneResp2"
									maxlength="14" onkeydown="javascript: fMasc( this, mTel );">
								<div id="telefoneResp2Erro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Endereço de e-mail do responsável
							2</label> <input class="form-control" id="emailResp2" type="text"
							name="emailResp2" aria-describedby="emailHelp"
							placeholder="E-mail usado para encaminhamento de comunicados"
							required maxlength="250">
						<div id="email2ValidacaoErro"
							style="display: none; font-size: 10pt; color: red">Campo
							obrigatório!</div>
					</div>
					<div class="form-group">
						<div class="form-row">
							<div class="col-md-6">
								<label for="exampleInputName">Data de nascimento</label> <input
									class="form-control date-mask" id="dataNascimentoResp2"
									name="dataNascimentoResp2" type="date"
									aria-describedby="nameHelp" placeholder="Data de nascimento"
									required>
								<div id="dataNascimentoResp2Erro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
							<div class="col-md-6">
								<label for="typeSexo">Sexo</label><br> <input type="radio"
									name="sexoResp2" id="sexoResp2" value="1" checked required>
								Masculino<br> <input type="radio" name="sexoResp2" value="0"
									id="sexoResp2" required> Feminino<br> <input type="radio"
									name="sexoResp2" value="2" id="sexoResp2" required> Não deseja
								informar<br> <input type="radio" name="sexoResp2" value="3"
									id="sexoResp2" required> Outro<br>
								<div id="sexoResp2Erro"
									style="display: none; font-size: 10pt; color: red">Campo
									obrigatório!</div>
							</div>
						</div>
					</div>

					<a class="btn btn-primary btn-block" style="margin-left: 1.1rem;margin-right: 1rem;" onclick="validateAndSubmitForm()">Cadastrar</a>
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
?>
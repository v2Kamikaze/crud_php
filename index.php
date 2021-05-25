<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title></title>
	<link href="estilo.css" rel="stylesheet">
</head>
<body>
<?php

date_default_timezone_set('America/Sao_Paulo');
echo date('d/m/Y');

?>
<div id="entradas">
<h1>Cadastro</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<label>Nome:</label><br>
	<input type="text" name="nome" placeholder="Informe seu nome aqui"><br>
	<label>Telefone:</label><br>
	<input type="text" name="telefone" placeholder="Informe seu telefone"><br>
	<label>Email:</label><br>
	<input type="email" name="email" placeholder="seuemail@email.com"><br>
	<input type="submit" name="cadastrar" value="Cadastrar"><br>
</form>
<h1>Atualizar informação</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<label>Digite o email para atualizar os dados:</label><br>
	<input type="email" name="up_email" placeholder="seuemail@email.com"><br>
	<label>Novo telefone:</label><br>
	<input type="text" name="up_telefone" placeholder="Novo telefone"><br>
	<input type="submit" name="atualizar" value="Atualizar"><br>
</form>
<h1>Excluir usuário</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<label>Digite o email do usuário: </label><br>
	<input type="email" name="dl_email" placeholder="seuemail@email.com"><br>
	<input type="submit" name="excluir" value="Excluir"><br>
</form>
</div>

<div id="tabela">
<caption><h1>Banco de dados</h1></caption>
<table>
	<thead>
		<tr>  <!-- Linha-->
			<th>Nome</th> <!--Colunas-->
			<th>Telefone</th>
			<th>E-mail</th>
		</tr> 
	</thead>
	<tbody>
		<tr>

<?php
require_once('crud.php');
/* Conexão com o bando de dados*/
$conn = new ConexaoDb("mysql","cadastro","localhost","root","");
$conn->conectar();

/* ===================================================== */
/* =============== Cadastro do usuário ================= */
/* ===================================================== */
if(isset($_POST["cadastrar"])){
	if($conn->estaContido($_POST["email"])){
		echo "<h2>Este email já foi cadastrado!</h2>";
	}else{
		$conn->create($_POST["nome"],$_POST["telefone"],$_POST["email"]);
	}
}
/* ===================================================== */
/* ========== Atualizar dados do usuário =============== */
/* ===================================================== */
if(isset($_POST["atualizar"])){
	if($conn->estaContido($_POST["up_email"])){
		$conn->update($_POST["up_telefone"],$_POST["up_email"]);
	}else{
		echo "<h2>Email não cadastrado!</h2>";
	}
}

/* ===================================================== */
/* =============== Deletar um usuário ================== */
/* ===================================================== */

if(isset($_POST["excluir"])){
	if($conn->estaContido($_POST["dl_email"])){
		$conn->del($_POST["dl_email"]);
	}else{
		echo "<h2>Email não encontrado!</h2>";
	}
}

/* ===================================================== */
/* = Leitura dos dados e criação da tabela de usuários = */
/* ===================================================== */
$data = $conn->read();
foreach ($data as $d) {
	echo "<td>".$d['nome']."</td>";
	echo "<td>".$d['telefone']."</td>";
	echo "<td>".$d['email']."</td>";
	echo "</tr>";
}
echo "
	<tbody>
</table>";

?>
</div>
</body>
</html>





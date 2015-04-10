<html>
<body>
<?php

	include("config.php");
	// inicia sessão para passar variaveis entre ficheiros php
	
	if($_GET["msg"]=="okleiloes") {
		echo("<p><b><h2>Inscrito com sucesso nos leiloes.</h2></b></p>");
	}
	if(!isset($_COOKIE["user"]) && !isset($_POST["username"])) {
		header('Location: login.htm');
	}
	if(!isset($_COOKIE["user"])) {
		// Função para limpar os dados de entrada
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		// Carregamento das variáveis username e pin do form HTML através do metodo POST;
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$username = test_input($_POST["username"]);
			$pin = test_input($_POST["pin"]);
		}
		echo("<p>Valida Pin da Pessoa $username</p>\n");
		// Variáveis de conexão à BD
		
		echo("<p>Projeto Base de Dados Parte II</p>\n");
		$sql = "SELECT * FROM pessoa WHERE nif=" . $username;
		$result = $connection->query($sql);
		if (!$result) {
			echo("<p> Erro na Query:($sql)<p>");
			exit();
		}
		foreach($result as $row){
			$safepin = $row["pin"];
			$nif = $row["nif"];
		}
		if ($safepin != $pin ) {
		echo "<p>Pin Invalido! Exit!</p>\n";
		$connection = null;
		exit;
		}
		echo "<p>Pin Valido! </p>\n";
		// passa variaveis para a sessao;
		//$_SESSION['username'] = $username;
		//$_SESSION['nif'] = $nif;
		setcookie("user", $username, time() + (3600), "/");
		header('Location: registo.php');
	
	} 
	if(isset($_COOKIE["user"])) {
		$nif = $_COOKIE["user"];
	// Apresenta os leilões
	$sql = "SELECT * FROM leilao";
	$result = $connection->query($sql);
	echo("<h2><a href='logout.php'>Fazer Logout</a></h2>");
	echo("<h2>Leiloes Existentes</h2>");
	echo("<table border=\"1\">\n");
	echo("<tr><td>ID</td><td>nif</td><td>Dia do Leilao</td><td>NrDoLeilaoNoDia</td><td>nome</td><td>tipo</td><td>valo
	rbase</td></tr>\n");
	$idleilao = 0;
	foreach($result as $row){
	$idleilao = $idleilao +1;
	echo("<tr><td>");
	echo($idleilao); echo("</td><td>");
	echo($row["nif"]); echo("</td><td>");
	echo($row["dia"]); echo("</td><td>");
	echo($row["nrleilaonodia"]); echo("</td><td>");
	echo($row["nome"]); echo("</td><td>");
	echo($row["tipo"]); echo("</td><td>");
	echo($row["valorbase"]); echo("</td>");
	$leilao[$idleilao]= array($row["nif"],$row["diahora"],$row["nrleilaonodia"]);
}
	echo("</table>\n");
	
	$sql2 = "SELECT * FROM leilaor WHERE lid IN ( SELECT leilao FROM concorrente WHERE pessoa=$nif)";
	$result = $connection->query($sql2);
	if (!$result) {
		echo("<p> Erro na Query:($sql2)<p>");
		exit();
	}
?>
<h2>Leiloes Inscrito</h2>
<table border="1"><tr><td>Leilao NR</td><td>Dia de Inicio</td><td>Dias restantes</td><td>Lance Máximo</td></tr>
<?php
foreach($result as $row){
	$sql = "SELECT MAX(valor) FROM lance WHERE leilao=".$row["lid"]."";
	$data1 = strtotime($row["dia"]. ' + ' .$row["nrdias"]. 'days');
	$data_agora = strtotime('now');
	$data = intval(($data1 - $data_agora)/(3600*24));
	//$data = intval(abs($data_agora - $data1)/(3600*24));
	$result = $connection->query($sql);
	$arr = $result->fetch(PDO::FETCH_ASSOC);
	$temp =  $arr["MAX(valor)"];
	if($temp=="")  $temp="0"; 
	echo("<tr>");
	echo("<td>".$row["lid"]."</td>");
	echo("<td>".$row["dia"]."</td>");
	echo("<td>".$data."</td>");
	echo("<td>".$temp."</td>");
	echo("</tr>");
} 

?>
</table>
<form action="lance.php" method="post">
<h2>Lance sobre leilao em curso</h2>
<p><table border="1"><tr><td><b>Leilão NR:</b></td><td><b>Valor:</b></td></tr>
<tr><td><input type="text" name="lid" /></td><td><input type="text" name="valor" /></td></tr></table></p>
<p><input type="submit" /></p>
</form>
<form action="leilao.php" method="post">
<h2>Escolha o ID do leilao que pretende concorrer</h2>
<p>ID : <input type="text" name="lid" /></p>
<p><input type="submit" /></p>
</form>
<form action="leilao_dia.php" method="post">
<h2>Escolha o dia do leilao (inscricao em varios leiloes em simultaneo)</h2>
<p>Data: <input type="text" name="dia" /></p>
<p><input type="submit" /></p>
</form>
</body>
</html>
<?php } ?>
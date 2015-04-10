<html>
<body>
<?php
if(isset($_COOKIE["user"])) {
	include("config.php");
	// inicia sessão para passar variaveis entre ficheiros php
	$nif = $_COOKIE["user"];
	$username = $nif;
	// Função para limpar os dados de entrada
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	// Carregamento das variáveis username e pin do form HTML através do metodo POST;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$lid = test_input($_POST["lid"]);
	}
	$sql = "SELECT * FROM leilaor WHERE lid=".$lid."";
	$result = $connection->query($sql);
	$arr = $result->fetch(PDO::FETCH_ASSOC);
	$data1 = strtotime($arr["dia"]. ' + ' .$arr["nrdias"]. 'days');
	$data_agora = strtotime('now');
	$data = intval(($data1 - $data_agora)/(3600*24));
	if($data <= 0) {
		echo("<p> Leilao ja expirou. <p>");
		exit();
	}
	
	// Conexão à BD
	$sql = "INSERT INTO concorrente (pessoa,leilao) VALUES ($nif,$lid)";
	$result = $connection->query($sql);
	if (!$result) {
		echo("<p> Pessoa nao registada: Erro na Query:($sql) <p>");
		exit();
	}
	echo("<p> Pessoa ($username), nif ($nif) Registada no leilao ($lid)</p>\n");
	// to be continued….
} else {
	header('Location: login.htm'); } ?>
<h2><a href="registo.php">Voltar</a></h2>
</body>
</html>
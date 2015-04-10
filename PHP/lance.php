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
		$valor = test_input($_POST["valor"]);
	}
	
	//regista a pessoa no leilão. Exemplificativo apenas.....
	$sql = "INSERT INTO lance (pessoa,leilao,valor) VALUES ($nif,$lid,$valor)";
	$result = $connection->query($sql);
	if (!$result) {
		echo("<p> Lance nao registado: Erro na Query:($sql) <p>");
		exit();
	}
	echo("<p> Pessoa ($username), nif ($nif), fez um lance de ($valor) no leilao ($lid)</p>\n");
	// to be continued….
} else {
	header('Location: login.htm'); }
?>
<h2><a href="registo.php">Voltar</a></h2>
</body>
</html>
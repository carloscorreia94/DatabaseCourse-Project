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
		$dial = test_input($_POST["dia"]);
	}
	$dia = preg_replace('/-|:/', null, $dial);
	$sql = "SELECT * FROM leilao natural join leilaor WHERE dia=$dia and lid not in(select leilao from concorrente where pessoa=$nif)";
	$result = $connection->query($sql);
	
	echo("<h2><b>Inscricao em Leiloes Ativos no dia $dial</b></h2><form action='leilao_dia.php#anchor' method='post'><input type='hidden' name='dia' value='$dia'><table border=\"1\">\n");
	echo("<tr><td>ID</td><td>nif</td><td>NrDoLeilaoNoDia</td><td>nome</td><td>tipo</td><td>valorbase</td><td>Inscrever</td></tr>\n");
	
	foreach($result as $row){
	$data1 = strtotime($row["dia"]. ' + ' .$row["nrdias"]. 'days');
	$data_agora = strtotime('now');
	$data = intval(($data1 - $data_agora)/(3600*24));
	if($data <= 0) {
        continue;
	}
	echo("<tr><td>");
	echo($row["lid"]); echo("</td><td>");
	echo($row["nif"]); echo("</td><td>");
	echo($row["nrleilaonodia"]); echo("</td><td>");
	echo($row["nome"]); echo("</td><td>");
	echo($row["tipo"]); echo("</td><td>");
	echo($row["valorbase"]); echo("</td>");
	echo('<td><input type="checkbox" name="check_list[]" value="'.$row["lid"].'"></td>');
}
	echo("</table>\n");
?>
<p><input type="submit" value="Inscrever" /></p></form>
<a name="anchor"></a>
<?php
	//tratar checkbox
	if(!empty($_POST['check_list'])) {
		try {
			$connection->beginTransaction();
			$stmt = $connection->prepare ("INSERT INTO concorrente(pessoa,leilao) values (?,?)");
			foreach($_POST['check_list'] as $check) {
				$stmt->execute(array($nif,$check));  
			}
			$connection->commit();
			header("Location: registo.php?msg=okleiloes");
		}	
		catch (Exception $e) { 
			if (isset ($connection)) 
				$connection->rollback ();
			echo "Erro: Não inscrito nos leilões.  " . $e; 
		}
	}
	
} else {
	header('Location: login.htm'); } ?>
<h2><a href="registo.php">Voltar</a></h2>
</body>
</html>
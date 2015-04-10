<?php
if(isset($_COOKIE["user"])) {
	setcookie("user", "test", time() - 3600, '/');
	header('Location: login.htm');
}
?>
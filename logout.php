<?php
require_once "conexao.php";

$_SESSION = [];
session_destroy();

header("Location: paginainicial.php");
exit;
?>
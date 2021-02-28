<?php
include_once('seguranca.php');
require_once("00 - BD/bd_conexao.php");
$login = $_POST['login'];
$senha = $_POST['senha'];
$sql = "SELECT * FROM administrador where Nome ='$login' and Senha = '$senha'";
$infoUsuario = search($sql, $con);
if (!empty($infoUsuario)) {
    setcookie("1828568", "logado", (time() + (22 * 3600)));
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=recebeProduto.php'>";
} else
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=index.php?error'>";

fecharConexao($con);

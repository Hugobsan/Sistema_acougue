<?php
$id_dia = $_GET['id'];
include_once("seguranca.php");
require_once("00 - BD/bd_conexao.php");
$sql = "DELETE FROM caixa WHERE id_dia = '$id_dia'";
if ($con->query($sql) == TRUE) {
    fecharConexao($con);

    header("Location: fechaCaixa.php?success02");
} else {
    fecharConexao($con);

    header("Location: fechaCaixa.php?errorBD02");
}

?>
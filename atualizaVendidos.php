<?php
require_once('00 - BD/bd_conexao.php');
$sql = "UPDATE compra SET vendido = 2 WHERE vendido IS NULL ";
$con->query($sql) or die("Erro!!!!!");
$sql2 = "UPDATE compra SET vendido = 2 WHERE vendido != 1";
$con->query($sql2) or die("Erro 2 !!!!!");

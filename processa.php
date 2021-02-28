<?php
session_start();
ob_start();
include_once("seguranca.php");

//Criar a conexao com BD
require_once("00 - BD/bd_conexao.php");
$conn=$con;

//Incluir o arquivo que gerar o backup
include_once("gerar_backup.php");
if($fechamento==1){
    header("Location: fechaCaixa.php?success");
}
elseif($fechamento==2){
    header("Location: fechaCaixa.php?errorBD");
}
else{
header("Location: config.php");
}

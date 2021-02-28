<?php
session_start();
ob_start();
include_once("seguranca.php");
//Criando backup do BD
    //Criar a conexao com BD
    require_once("00 - BD/bd_conexao.php");
    $conn=$con;

    //Incluir o arquivo que gerar o backup
    include_once("gerar_backup.php");


$arquivo = 'E:/backup_BD/bd_backup.sql';

$servidor='localhost';
$usuario='root';
$senha='';
$dbname='acougue';
$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

//Ler os dados do arquivo e converter em string
$dados = file_get_contents($arquivo);
//echo $dados;

//Executar as query do backup

if(mysqli_multi_query($conn, $dados)){
    $_SESSION['msg2'] = "<b><span style='color: green'>Base de dados restaurada com sucesso!</span><b><br>";
}else{
    $_SESSION['msg2'] = "<b><span style='color: green'>Ocorreu um erro ao restaurar o Banco de Dados!</span><b><br>";
}
header("Location: config.php");





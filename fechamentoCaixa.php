<?php
include_once("seguranca.php");
if(isset($_POST['ok'])){
    require_once('00 - BD/bd_conexao.php');
    $data=date('Y-m-d');
    $valor=$_POST['valorCaixa'];
    $valor=str_replace(',','.', $valor);
    $sql=" INSERT INTO caixa VALUES(null, '$valor', '$data')";

    if ($con->query($sql) == TRUE) {  
        $fechamento=1;
        include_once("processa.php");
        fecharConexao($con);
    } else {
        $fechamento=2;
        include_once("processa.php");
        fecharConexao($con);
    }
}

?>
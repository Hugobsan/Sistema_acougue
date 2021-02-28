<?php
include_once("seguranca.php");

if (isset($_POST['redefPass'])) {
    $passAtual = $_POST['passAntiga'];
    $novaPass = $_POST['passNova'];
    
    if(empty($passAtual) || empty($novaPass)){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?error09'>";
        return 0;
    }
    require_once('00 - BD/bd_conexao.php');
    $sql1 = " SELECT Senha FROM administrador";
    
    $resultado = $con->query($sql1);
    if ($administrador = mysqli_fetch_object($resultado)) {
        if ($administrador->Senha == $passAtual) {
            $sql = "UPDATE administrador SET Senha = '$novaPass' WHERE Senha LIKE '$passAtual' ";
        } 
        elseif ($administrador->Senha != $passAtual) {
            fecharConexao($con);
            echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?error10'>";
            return 0;
        }
    }
    if ($con->query($sql) == TRUE) {
        fecharConexao($con);
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?success07'>";
    } 
    else{
        fecharConexao($con);
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?errorBD'>";
    }
}
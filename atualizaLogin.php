<?php
include_once("seguranca.php");

if (isset($_POST['redefRoot'])) {
    $rootAtual = $_POST['rootAtual'];
    $novoRoot = $_POST['rootNovo'];
    
    if(empty($rootAtual) || empty($novoRoot)){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?error07'>";
        return 0;
    }
    require_once('00 - BD/bd_conexao.php');
    $sql1 = " SELECT Nome FROM administrador";
    
    $resultado = $con->query($sql1);
    if ($administrador = mysqli_fetch_object($resultado)) {
        if ($administrador->Nome == $rootAtual) {
            $sql = "UPDATE administrador SET Nome = '$novoRoot' WHERE Nome LIKE '$rootAtual' ";
        } 
        elseif ($administrador->Nome != $rootAtual) {
            fecharConexao($con);
            echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?error08'>";
            return 0;
        }
    }
    if ($con->query($sql) == TRUE) {
        fecharConexao($con);
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?success06'>";
    } 
    else{
        fecharConexao($con);
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=config.php?errorBD'>";
    }
}
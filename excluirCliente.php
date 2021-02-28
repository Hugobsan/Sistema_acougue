<?php
    include_once("seguranca.php");
    if (!isset($_GET['id'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=cadernetas.php'>";
    }
    $id=$_GET['id'];
    require_once("00 - BD/bd_conexao.php");
    $sql = "DELETE FROM cliente WHERE id_cliente LIKE '$id' ";

    if ($con->query($sql) == TRUE) {
        fecharConexao($con);

        header("Location: cadernetas.php?success02");
    } else {
        fecharConexao($con);

        header("Location: cadernetas.php?error01");
    }
?>
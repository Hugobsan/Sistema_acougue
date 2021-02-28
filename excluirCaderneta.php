<?php
    include_once("seguranca.php");
    $id_cliente=$_GET['id'];
    if (!isset($_GET['id'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=cadernetas.php?id=".$id_cliente."'>";
    }
    require_once("00 - BD/bd_conexao.php");
    $id_cad= $_GET['id_cad'];
    $sql = "DELETE FROM caderneta WHERE id_caderneta LIKE '$id_cad' ";

    if ($con->query($sql) == TRUE) {
        fecharConexao($con);

        header("Location: cadernetaCliente.php?success03&id=".$id_cliente);
    } else {
        fecharConexao($con);

        header("Location: cadernetaCliente.php?error04&id=".$id_cliente);
    }
?>
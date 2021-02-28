<?php
    include_once("seguranca.php");
    $id_caderneta=$_GET['id'];
    if (!isset($_GET['id'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=order_carderneta_mes.php?id=".$id_caderneta."'>";
    }
    require_once("00 - BD/bd_conexao.php");
    
    $data_hoje=date('Y-m-d');
    $sql = "UPDATE caderneta SET status_caderneta ='fechada', data_fechamento ='$data_hoje' WHERE id_caderneta LIKE '$id_caderneta' ";
    $sql2 = "UPDATE compra SET vendido = 1 WHERE id_caderneta LIKE '$id_caderneta' ";


    if ($con->query($sql) == TRUE && $con->query($sql2) == TRUE) { 
        fecharConexao($con);
        header("Location: cadernetas.php?successFatura");
    } else {
        fecharConexao($con);

        header("Location: cadernetas.php?errorFatura");
    }
?>
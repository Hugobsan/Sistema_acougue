<?php
    include_once("seguranca.php");
    if (!isset($_GET['id_prod'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=estoque.php'>";
    }
    $id_prod=$_GET['id_prod'];
    require_once("00 - BD/bd_conexao.php");
    $sql = "DELETE FROM produto WHERE id_produto LIKE '$id_prod' ";

    if ($con->query($sql) == TRUE) {
        fecharConexao($con);

        header("Location: estoque.php?success03");
    } else {
        fecharConexao($con);

        header("Location: estoque.php?error03");
    }
?>
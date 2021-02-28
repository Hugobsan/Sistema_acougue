<?php
include_once("seguranca.php");

if (isset($_POST['enviaDados'])) {
    if (!empty($_POST['nomeProd']) && !empty($_POST['precoUni']) && !empty($_POST['estoque'])) {
        $id_prod = $_POST['idProduto'];
        $nomeProd = $_POST['nomeProd'];
        if(isset($_POST['prodUnitario'])){
            $prod_unitario=1;
        }
        else{
            $prod_unitario=0;
        }
        $precoUni = $_POST['precoUni'];
        $precoUni = str_replace(',','.', $precoUni);
        $estoque = $_POST['estoque'];
        $estoque = str_replace(',','.', $estoque);
        $old_id=$id_prod;
        require_once("00 - BD/bd_conexao.php");
        if($prod_unitario==1 && $id_prod<100){
            //Gerar uma ID válida
            $sql3="SELECT id_produto, produto_unitario FROM produto ORDER BY id_produto DESC";
            $result3=$con->query($sql3);
            while($infoId=mysqli_fetch_object($result3)){
                $id_prod=$infoId->id_produto;
                if($infoId->produto_unitario==1){ //Se o item com maior ID for um produto unitário incrementa-se o ID
                    $id_prod=$id_prod+1;
                }
                else{ // Se o item com maior ID for um produto por quilo adiciona-se 100 nesse ID;
                    $id_prod=$id_prod+100;
                }
                break;
            }
        }
        $sql = "UPDATE produto SET id_produto='$id_prod', nome_produto ='$nomeProd', preco_unidade ='$precoUni', estoque_atual = '$estoque', produto_unitario = '$prod_unitario'  WHERE id_produto LIKE '$old_id'";

        if ($con->query($sql) == TRUE) {
            fecharConexao($con);
            header("Location: estoque.php?success02");
        } else {
            fecharConexao($con);

            header("Location: estoque.php?errorBD");
        }
    } else {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=estoque.php?error01>"; //Digite todos os campos antes de enviar o formulário
        return 0;
    }
} else {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=estoque.php'>";
}

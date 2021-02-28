<?php
include_once("seguranca.php");

if (isset($_POST['enviaDados'])) {
    if (!empty($_POST['nomeProd']) && !empty($_POST['precoUni']) && !empty($_POST['estoque'])) {
        $id_prod = $_POST['idProduto'];
        $nomeProd = $_POST['nomeProd'];
        $precoUni = $_POST['precoUni'];
        if(isset($_POST['prodUnitario'])){
            $prod_unitario=1;
        }
        else{
            $prod_unitario=0;
        }
        $precoUni = str_replace(',','.', $precoUni);
        $estoque = $_POST['estoque'];
        $estoque = str_replace(',','.', $estoque);

        require_once("00 - BD/bd_conexao.php");
        if($prod_unitario==1){
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
        
        /* Verifica se já existe um produto com mesma ID e retorna erro expecífico*/
        $sql2="SELECT id_produto from produto where id_produto='$id_prod'";
        $result2=$con->query($sql2);
        if(mysqli_num_rows($result2)>0){
            fecharConexao($con);
            header("Location: dadosProduto.php?errorID");
            return 0;
        }
        
        /* Verifica se já existe um produto com mesmo NOME e retorna erro expecífico*/
        $sql1="SELECT nome_produto from produto where nome_produto='$nomeProd'";
        $result1=$con->query($sql1);
        if(mysqli_num_rows($result1)>0){
            fecharConexao($con);
            header("Location: dadosProduto.php?errorNome");
            return 0;
        }

        $sql = "INSERT INTO produto values('$id_prod', '$nomeProd', '$precoUni', '$estoque', '$prod_unitario')";

        if ($con->query($sql) == TRUE) {
            fecharConexao($con);
            header("Location: dadosProduto.php?success");
        } else {
            fecharConexao($con);

            header("Location: dadosProduto.php?errorBD");
        }
    } else {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=dadosProduto.php?error01'>"; //Digite todos os campos antes de enviar o formulário
        return 0;
    }
} else {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=estoque.php'>";
}

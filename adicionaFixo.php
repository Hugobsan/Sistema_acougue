<?php
$id_cliente = $_GET['id_cliente'];
include_once("seguranca.php");
if (!isset($_POST['btn_Ok'])) {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?id='" . $id_cliente . ">";
} else {
    //SETAR NO BD id_prod_comprado, peso_produto~~~~
    //id_compra=NULL, data_compra=Data Atual, cod_barras=NULL, nome_produto=produto->nome_produto, 
    //preco_unidade=produto->preco_unidade, valor_produto=peso_produto*preco_unidade, exibir_linha=1
    if (empty($_POST['valor_inserido'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?error01&id=". $id_cliente . "'>";
    } else {
        $id_caderneta = $_GET['id_cad'];
        $valor_produto = $_POST['valor_inserido'];
        
        $data_compra = date('Y-m-d');
        require_once('00 - BD/bd_conexao.php');

        $sql4 = " SELECT nome_cliente FROM cliente WHERE id_cliente = '$id_cliente'";
        $resultado2 = $con->query($sql4);
        while ($cliente = mysqli_fetch_object($resultado2)) {
            $nome_cliente = $cliente->nome_cliente;
        }
        $nome_produto="Valor Fixo";
        /*VERIFICAÇÃO SE TODAS AS VARIÁVEIS EXISTEM ---------------------------------------------------
            id compra=NULL, data_compra=$data_compra, id_prod_comprado=NULL, cod_barras=null,
             peso_produto=null, nome_produto="Valor Fixo", valor_produto=$valor_produto,
             id_cliente=$id_cliente, preco_unidade=null, id_caderneta=$id_caderneta, nome_cliente=$nome_cliente, exibir_linha=0, 
              */

        $sql = " INSERT INTO compra VALUES(null, '$data_compra', null, null, null, '$nome_produto', '$valor_produto', '$id_cliente', null, '$id_caderneta', '$nome_cliente', 0, 0)";

        if ($con->query($sql) == TRUE) {
            fecharConexao($con);

            header("Location: venderCliente.php?id=".$id_cliente);
        } else {
            fecharConexao($con);

            header("Location: venderCliente.php?errorFixo&id=" . $id_cliente);
        }
    }
}

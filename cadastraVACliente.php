<?php
include_once("seguranca.php");
$id_cliente=$_GET['id_cliente'];
if (!isset($_POST['btn_OkCadastraVenda']) && !isset($_POST['btn_OkCadastraVenda'])) {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=recebeProduto.php'>";
}
else{
    //SETAR NO BD id_prod_comprado, peso_produto~~~~
    //id_compra=NULL, data_compra=Data Atual, cod_barras=NULL, nome_produto=produto->nome_produto, 
    //preco_unidade=produto->preco_unidade, valor_produto=peso_produto*preco_unidade, exibir_linha=1


    if(isset($_POST['FinalizaCadastraVenda'])){
            require_once('00 - BD/bd_conexao.php');	
            $sql3=" UPDATE compra SET exibir_linha=0 WHERE exibir_linha=1";

            if ($con->query($sql3) == TRUE) {
                fecharConexao($con);
        
                header("Location: venderCliente.php?tbl_va&id=".$id_cliente);
            } else {
                fecharConexao($con);
        
                header("Location: venderCliente.php?errorBD01&tbl_va&id=".$id_cliente);
            }

        }

    else if(isset($_POST['btn_OkCadastraVenda'])){
        if(empty($_POST['barras'])){
            echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?error01&tbl_va&id=".$id_cliente."'>";
        }
        else{
            /*PADRÃO DO CÓDIGO DE BARRAS
            2CCCC0QQQQQQDV
            0,{1,2,3,4},5,{6.7,8,9,10,11},12;

            EX1:
            ID:2
            PESO:1,840
            Código de Barras:
            2000200018407

            */

            $cod_barras=$_POST['barras'];

            $id_prod_comprado=substr($cod_barras, 1, 4);
            $peso_produto= substr($cod_barras, 6, 6);
            $peso_produto= substr($peso_produto, 0, 3).".".substr($peso_produto, 3, 3);
            
            //Removendo os 0 inválidos
            $id_prod_comprado=$id_prod_comprado*1;
            $peso_produto=$peso_produto*1;


            $data_compra=date('Y-m-d');

            $id_caderneta=$_GET['id_cad'];
            
            require_once('00 - BD/bd_conexao.php');
            $sql1=" SELECT nome_produto, preco_unidade, estoque_atual, produto_unitario FROM produto WHERE id_produto = '$id_prod_comprado' ";
            $resultado = $con->query($sql1);
            if(mysqli_num_rows($resultado)==0){
                fecharConexao($con);
                header("Location: venderCliente.php?errorId&tbl_va&id=".$id_cliente);
                return 0;
                
            }
            while($produto = mysqli_fetch_object($resultado)){
                $nome_produto=$produto->nome_produto;
                $preco_unidade=$produto->preco_unidade;
                $estoque_atual=$produto->estoque_atual;
		$prod_unitario=$produto->produto_unitario;
            }
		if($prod_unitario==1){
			$peso_produto=$peso_produto*1000;
		}

            $sql5=" SELECT nome_cliente FROM cliente WHERE id_cliente = '$id_cliente'";
            $resultado2= $con->query($sql5);
            while($cliente = mysqli_fetch_object($resultado2)){
                $nome_cliente=$cliente->nome_cliente;
            }

            $valor_produto=$peso_produto*$preco_unidade;

            /*VERIFICAÇÃO SE TODAS AS VARIÁVEIS EXISTEM ---------------------------------------------------
            id compra=NULL, data_compra=$data_compra, id_prod_comprado=$id_prod_comprado, cod_barras=$cod_barras,
             peso_produto=$peso_produto, nome_produto=$nome_produto, valor_produto=$valor_produto,
             id_cliente=$id_cliente, preco_unidade=$preco_unidade, id_caderneta=$id_caderneta, nome_cliente=$nome_cliente, exibir_linha=1, 
              */
            
            $novo_estoque=$estoque_atual-$peso_produto;
            if($novo_estoque<0){
                $novo_estoque=0;
            }

            $sql2=" UPDATE produto SET estoque_atual='$novo_estoque' WHERE id_produto='$id_prod_comprado'";
            
            $sql=" INSERT INTO compra VALUES(null, '$data_compra', '$id_prod_comprado', '$cod_barras', '$peso_produto', '$nome_produto', '$valor_produto', '$id_cliente', '$preco_unidade', '$id_caderneta', '$nome_cliente', 1, 2)";
            
            if ($con->query($sql) == TRUE && $con->query($sql2) == TRUE) {
                fecharConexao($con);
        
                header("Location: venderCliente.php?tbl_va&id=".$id_cliente);
            } else {
                fecharConexao($con);
        
                header("Location: venderCliente.php?errorBD01&tbl_va&id=".$id_cliente);
            }
        }

    }
}



?>
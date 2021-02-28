 <?php
$id_cliente=$_GET['id_cliente'];
include_once("seguranca.php");
if (!isset($_POST['btn_OkCadastraVenda']) && !isset($_POST['btn_OkCadastraVenda'])) {
    echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?id='".$id_cliente.">";
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
        
                header("Location: venderCliente.php?tbl_vm&id=".$id_cliente);
            } else {
                fecharConexao($con);
        
                header("Location: venderCliente.php?errorBD01&tbl_vm&id=".$id_cliente);
            }

        }

    else if(isset($_POST['btn_OkCadastraVenda'])){
        if(empty($_POST['id_prod_comprado']) || empty($_POST['peso_produto']) ){
            
            echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?error01&tbl_vm&id=".$id_cliente."'>";
        }
        else{
            $id_caderneta=$_GET['id_cad'];
            $id_prod_comprado=$_POST['id_prod_comprado'];
            $peso_produto=$_POST['peso_produto'];
            $peso_produto=str_replace(',','.', $peso_produto);
            $data_compra=date('Y-m-d');
            require_once('00 - BD/bd_conexao.php');	
            $sql1=" SELECT nome_produto, preco_unidade, estoque_atual FROM produto WHERE id_produto = '$id_prod_comprado' ";
            $resultado = $con->query($sql1);
            if(mysqli_num_rows($resultado)==0){
                fecharConexao($con);
                header("Location: venderCliente.php?errorId2&tbl_va&id=".$id_cliente);
                return 0;
                
            }
            while($produto = mysqli_fetch_object($resultado)){
                $nome_produto=$produto->nome_produto;
                $preco_unidade=$produto->preco_unidade;
                $estoque_atual=$produto->estoque_atual;
            }
            
            $sql4=" SELECT nome_cliente FROM cliente WHERE id_cliente = '$id_cliente'";
            $resultado2= $con->query($sql4);
            while($cliente = mysqli_fetch_object($resultado2)){
                $nome_cliente=$cliente->nome_cliente;
            }

            $valor_produto=$peso_produto*$preco_unidade;
            /*VERIFICAÇÃO SE TODAS AS VARIÁVEIS EXISTEM ---------------------------------------------------
            id compra=NULL, data_compra=$data_compra, id_prod_comprado=$id_prod_comprado, cod_barras=null,
             peso_produto=$peso_produto, nome_produto=$nome_produto, valor_produto=$valor_produto,
             id_cliente=$id_cliente, preco_unidade=$preco_unidade, id_caderneta=$id_caderneta, nome_cliente=$nome_cliente, exibir_linha=1, 
              */
            
            $novo_estoque=$estoque_atual-$peso_produto;
            if($novo_estoque<0){
                $novo_estoque=0;
            }

            $sql2=" UPDATE produto SET estoque_atual='$novo_estoque' WHERE id_produto='$id_prod_comprado'";
            
            $sql=" INSERT INTO compra VALUES(null, '$data_compra', '$id_prod_comprado', null, '$peso_produto', '$nome_produto', '$valor_produto', '$id_cliente', '$preco_unidade', '$id_caderneta', '$nome_cliente', 1, 2)";
            
            if ($con->query($sql) == TRUE && $con->query($sql2) == TRUE) {
                fecharConexao($con);
        
                header("Location: venderCliente.php?tbl_vm&id=".$id_cliente);
            } else {
                fecharConexao($con);
        
                header("Location: venderCliente.php?errorBD01&tbl_vm&id=".$id_cliente);
            }
        }

    }
}



?>
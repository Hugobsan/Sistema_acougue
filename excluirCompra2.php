<?php
    include_once("seguranca.php");
    $id_compra=$_GET['id_compra'];
    $id_caderneta=$_GET['id_cad'];
    $id_cliente=$_GET['id_cl'];
    $decisao=$_POST['excluirOpcao'];
    $id_prod;
    $peso_produto;
    $total_estoque;
    

    if(empty($decisao) && $id_caderneta=='vc'){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=venderCliente.php?error06&tbl_vm&tbl_va&id=".$id_cliente."'>";
    }

    require_once("00 - BD/bd_conexao.php");

    if($decisao=="1"){//Se a decisão for devolver pro estoque
        $sql1= " SELECT peso_produto, id_prod_comprado FROM compra WHERE id_compra LIKE $id_compra";
        $resultado1=$con->query($sql1);
        if($tabelaCompra = mysqli_fetch_object($resultado1)){
                    $id_prod=$tabelaCompra->id_prod_comprado;
                    $peso_produto=$tabelaCompra->peso_produto;
                }
        $sql2=" SELECT estoque_atual FROM produto WHERE id_produto LIKE $id_prod";
        $resultado2=$con->query($sql2);
        if($tabelaProduto = mysqli_fetch_object($resultado2)){
            $total_estoque=$tabelaProduto->estoque_atual;
        }
        $total_estoque=$total_estoque+$peso_produto;
    
        $sql = "UPDATE produto SET estoque_atual = '$total_estoque' WHERE id_produto LIKE $id_prod ";//Atualiza os dados do estoque devolvendo os produtos
     
        $del="DELETE from compra WHERE id_compra LIKE $id_compra";
        if ($con->query($sql) == TRUE && $con->query($del) == TRUE) { 
            fecharConexao($con);
            header("Location: venderCliente.php?success05&tbl_vm&tbl_va&id=".$id_cliente."");

        } else {
            fecharConexao($con);
            header("Location: venderCliente.php?error07&tbl_vm&tbl_va&id=".$id_cliente."");
    
        }
    }
    else if($decisao=="2"){
        $del="DELETE from compra WHERE id_compra LIKE $id_compra";
        if ($con->query($del) == TRUE) { 
            fecharConexao($con);
            header("Location: venderCliente.php?success05&tbl_vm&tbl_va&id=".$id_cliente."");
        } else {
            fecharConexao($con);
            header("Location: venderCliente.php?error07&tbl_vm&tbl_va&id=".$id_cliente."");
    
        }
    }
?>
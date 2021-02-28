<?php
    include_once("seguranca.php");
    $id_compra=$_GET['id_compra'];
    $id_caderneta=$_GET['id_cad'];
    $decisao=$_POST['excluirOpcao'];
    $id_prod;
    $peso_produto;
    $total_estoque;
    if (!isset($_POST['excluiVenda']) && $id_caderneta!='estoque' ) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=order_carderneta_mes.php?id=".$id_caderneta."'>";
    }
    
    if(empty($decisao) && $id_caderneta!='estoque' && $id_caderneta!='rp'){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=order_carderneta_mes.php?error06&id=".$id_caderneta."'>";
    }

    if(empty($decisao) && $id_caderneta=='estoque'){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=estoque.php?error06'>";
    }
    elseif(empty($decisao) && $id_caderneta=='rp'){
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=recebeProduto.php?error06&tbl_vm&tbl_va'>";
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
            if($id_caderneta!='estoque' && $id_caderneta!='rp'){
                header("Location: order_carderneta_mes.php?success05&id=".$id_caderneta);
            }
            elseif($id_caderneta=='estoque'){
                header("Location: estoque.php?success05");
            }
            elseif($id_caderneta=='rp'){
                header("Location: recebeProduto.php?success05&tbl_vm&tbl_va");
            }
        } else {
            fecharConexao($con);
            if($id_caderneta!='estoque' && $id_caderneta!='rp'){
                header("Location: order_carderneta_mes.php?error07&id=".$id_caderneta);
            }
            elseif($id_caderneta=='estoque'){
                header("Location: estoque.php?error07");
            }
            elseif($id_caderneta=='rp'){
                header("Location: recebeProduto.php?error07&tbl_vm&tbl_va");
            }
        }
    }
    else if($decisao=="2"){
        $del="DELETE from compra WHERE id_compra LIKE $id_compra";
        if ($con->query($del) == TRUE) { 
            fecharConexao($con);
            if($id_caderneta!='estoque' && $id_caderneta!='rp'){
                header("Location: order_carderneta_mes.php?success05&id=".$id_caderneta);
            }
            elseif($id_caderneta=='estoque'){
                header("Location: estoque.php?success05");
            }
            elseif($id_caderneta=='rp'){
                header("Location: recebeProduto.php?success05&tbl_vm&tbl_va");
            }
        } else {
            fecharConexao($con);

            if($id_caderneta!='estoque' && $id_caderneta!='rp'){
                header("Location: order_carderneta_mes.php?error07&id=".$id_caderneta);
            }
            elseif($id_caderneta=='estoque'){
                header("Location: estoque.php?error07");
            }
            elseif($id_caderneta=='rp'){
                header("Location: recebeProduto.php?error07&tbl_vm&tbl_va");
            }
        }
    }
?>
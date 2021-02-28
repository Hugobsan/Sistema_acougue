<?php
//Insere a segurança
include_once("seguranca.php");

//Se o id do cliente veio de Recebe Produto; 
if(!empty($_POST['id_clienteM']) || !empty($_POST['id_clienteA'])){
    //Se o id do cliente for inserido de uma venda manual recebe o valor na váriavel $id_cliente
    if(!empty($_POST['id_clienteM'])){
        $id_cliente=$_POST['id_clienteM'];
    }

    //senão o id do cliente foi inserido de uma venda automática e recebe o valor na váriavel $id_cliente
    else{
        $id_cliente=$_POST['id_clienteA'];
    }

    //Chamando a conexão com o BD
    require_once('00 - BD/bd_conexao.php');	
    
    //PEGANDO ID DA CADERNETA
    $sql6="SELECT id_caderneta FROM caderneta WHERE id_cliente='$id_cliente' AND status_caderneta='aberta'";
    $result6=$con->query($sql6);
    while($cad=mysqli_fetch_object($result6)){
        $id_caderneta=$cad->id_caderneta;
    }
    //A partir daqui o ID da caderneta, se existir, está na variável $id_caderneta
    // Se na busca por uma caderneta aberta do cliente não for encontrado nenhuma resposta, retorna à tela inicial com erro
    if(mysqli_num_rows($result6)==0){
        header("Location: recebeProduto.php?tbl_vm&errorAberta");
        return 0;
    }
    // PEGANDO NOME DO CLIENTE
    $sql7="SELECT nome_cliente FROM cliente WHERE id_cliente='$id_cliente'";
    $result7=$con->query($sql7);
    while($nome=mysqli_fetch_object($result7)){
        $nome_cliente=$nome->nome_cliente;
    }
    //A partir daqui, o nome do cliente, se existir, está na variável $nome_cliente
    //Atribuindo ao $nome_cliente todas as vendas sendo exibidas na tela (todas as vendas que tem exibir_linha==1)
    $sql5="UPDATE compra SET id_cliente='$id_cliente', id_caderneta='$id_caderneta', nome_cliente='$nome_cliente', exibir_linha=0, vendido=0 WHERE exibir_linha=1 ";
    //Esse código determina que todas as vendas exibidas vão para o cliente determinado e as vendas devem parar de aparecer na tela (em exibir_linha=0);
    //Se a execução dessa tarefa no BD der certo...
    if ($con->query($sql5) == TRUE) {
        fecharConexao($con);
        // Se o cliente foi atribuido de uma venda manual, retornar mantendo a venda manual aberta 
        if(!empty($_POST["id_clienteM"])){
            header("Location: recebeProduto.php?tbl_vm&successCliente");
            return 0;
        }
        // Se o cliente foi atribuido de uma venda automática, retornar mantendo a venda automática aberta
        else{
            header("Location: recebeProduto.php?tbl_va&successCliente");
            return 0;
        }
    } 
    //Se a conexão dessa tarefa no BD der errado...
    else {
        fecharConexao($con);
        // Se o cliente foi atribuido de uma venda manual, retornar mantendo a venda manual aberta 
        if(!empty($_POST["id_clienteM"])){
            header("Location: recebeProduto.php?errorBD01&tbl_vm&id=".$id_cliente);
            return 0;
        }
        // Se o cliente foi atribuido de uma venda automática, retornar mantendo a venda automática aberta
        else{
            header("Location: recebeProduto.php?errorBD01&tbl_va&id=".$id_cliente);
            return 0;

        }
    }
}
//Se o formulário veio de uma tabela de venda automática de um cliente específico
if(isset($_GET['tbl_va'])&&isset($_GET['vc'])){
    $id_cliente=$_GET['id'];
    //A partir daqui, o id do cliente vai para $id_cliente;
    require_once('00 - BD/bd_conexao.php');	
    //Onde tiver compra sendo exibida na tela, setar pra não ser exibida mais;
    $sql3=" UPDATE compra SET exibir_linha=0 WHERE exibir_linha=1";

    //Se a execução dessa tarefa no BD der certo...
    if ($con->query($sql3) == TRUE) { 
        fecharConexao($con);
        //Retorna pra página de Venda para o Cliente em questão, com a tabela limpa; 
        header("Location:venderCliente.php.?id=".$id_cliente);
    } 
    //Se a execução dessa tarefa no BD der errado...
    else {
        fecharConexao($con);
        //Retorna pra página de Venda para o Cliente em questão, com a tabela limpa e aviso de erro; 
        header("Location: venderCliente.php?errorBD01&id=".$id_cliente);
    }

}
//Se o formulário veio de uma tabela de venda automática à vista
elseif(isset($_GET['tbl_va'])&&isset($_GET['rp'])){
    require_once('00 - BD/bd_conexao.php');	
    //Onde tiver compra sendo exibida na tela, setar pra não ser exibida mais;
    $sql3=" UPDATE compra SET exibir_linha=0 WHERE exibir_linha=1";

    if ($con->query($sql3) == TRUE) {
        fecharConexao($con);

        header("Location: recebeProduto.php?tbl_va");
    } else {
        fecharConexao($con);

        header("Location: recebeProduto.php?errorBD01&tbl_va");
    }

}
//Se o formulário veio de uma tabela de venda manual para um cliente específico
elseif(isset($_GET['tbl_vm'])&&isset($_GET['vc'])){
    $id_cliente=$_GET['id'];
    //A partir daqui, o id do cliente vai para $id_cliente;
    require_once('00 - BD/bd_conexao.php');	
    //Onde tiver compra sendo exibida na tela, setar pra não ser exibida mais;
    $sql3=" UPDATE compra SET exibir_linha=0 WHERE exibir_linha=1";
    if ($con->query($sql3) == TRUE) {
        fecharConexao($con);

        header("Location: venderCliente.php?id=".$id_cliente);
    } else {
        fecharConexao($con);

        header("Location:  venderCliente.php?errorBD01&id=".$id_cliente);
    }

}

elseif(isset($_GET['tbl_vm'])&&isset($_GET['rp'])){
    require_once('00 - BD/bd_conexao.php');	
    $sql3=" UPDATE compra SET exibir_linha=0 WHERE exibir_linha=1";

    if ($con->query($sql3) == TRUE) {
        fecharConexao($con);

        header("Location: recebeProduto.php?tbl_vm");
    } else {
        fecharConexao($con);

        header("Location: recebeProduto.php?errorBD01&tbl_vm");
    }
}


?>
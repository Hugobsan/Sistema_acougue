<?php
include_once("seguranca.php");
$id=$_GET['id'];
if (isset($_POST['okCadastroCliente'])) {
    require_once("00 - BD/bd_conexao.php");

    $nome_cliente = $_POST['nomeCliente'];
    if(!empty($_POST['cpfCliente'])){
        $cpf_cliente = $_POST['cpfCliente'];
    }
    elseif(!empty($_POST['cnpjCliente'])){
        $cpf_cliente = $_POST['cnpjCliente'];
    }
    $tel_cliente = $_POST['telCliente'];
    $end_cliente = $_POST['enderecoCliente'];
    
    if(!empty($nome_cliente) && !empty($cpf_cliente) && !empty($tel_cliente) && !empty($end_cliente)){
        //   PASSANDO OS DADOS PARA O BD
        $sql = " UPDATE cliente SET nome_cliente='$nome_cliente', cpf_cliente='$cpf_cliente', telefone_cliente='$tel_cliente', endereco_cliente='$end_cliente' WHERE id_cliente='$id'";

    if ($con->query($sql) == TRUE) {
        fecharConexao($con);

        header("Location: cadernetas.php?success");
    } else {
        fecharConexao($con);

        header("Location: cadernetas.php?errorBD");
    }

    
    }
    else{
        header("Location: cadernetas.php?error");
    }

}
else{
    header("Location: cadernetas.php?id=".$id."");
}



?>
<?php
    include_once("seguranca.php");
    if (!isset($_POST['okCadastroCliente'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=cadernetas.php'>";
        return 0;
    }


    require_once("00 - BD/bd_conexao.php");
    
    //Recebendo os dados em variaveis
    
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
        $sql = " INSERT INTO cliente VALUES(null, '$nome_cliente','$cpf_cliente', '$tel_cliente', '$end_cliente')";

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


?>
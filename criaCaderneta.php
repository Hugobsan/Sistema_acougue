<?php
    include_once("seguranca.php");
	//recebe o ID do cliente
    $id_cliente=$_GET['id'];
	//Verifica se o botão foi clicado
    if (!isset($_POST['btnCriarCaderneta'])) {
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=cadernetaCliente.php?id=".$id_cliente."'>";
    }
	//Inicia conexão com o BD
    require_once("00 - BD/bd_conexao.php");
    $data_hoje=date('Y-m-d');
	//variave
    /* Busca no BD se já existe uma caderneta aberta */
    $verificacao = "SELECT id_caderneta FROM caderneta WHERE status_caderneta = 'aberta' AND id_cliente = '$id_cliente'";
    $resultadoVerificacao= $con->query($verificacao) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos.");
    $row = $resultadoVerificacao->fetch_row();//busca as colunas 
       
    /* Fim da Busca */

    if(!($row[0] > 0)){//se o array de colunas buscadas não for maior que zero ele cria a caderneta nova
        $sql = "INSERT INTO caderneta VALUES(null, 'aberta', '$data_hoje', '$id_cliente', null)";
        if ($con->query($sql) == TRUE) {
            fecharConexao($con);

            header("Location: cadernetaCliente.php?success&id=".$id_cliente);
        } else {
            fecharConexao($con);
    
            header("Location: cadernetaCliente.php?errorBD&id=".$id_cliente);
        }
    }
    else{ //se o array de colunas buscadas for maior que zero ele não cria a caderneta nova
        echo "<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=cadernetaCliente.php?error03&id=".$id_cliente."'>";
    }



?>
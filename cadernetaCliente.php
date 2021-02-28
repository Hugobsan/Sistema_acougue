<?php include_once("seguranca.php");  ?>
<!doctype html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="css/global_css.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <title>Caderneta Cliente</title>
    <script> /* Confirmador de Saída */
        function confirmar(url){
        event.preventDefault();  
        var resposta = confirm("Deseja mesmo excluir essa caderneta?");
        if (resposta == true){
            window.location.href = url;
        }
        }
    </script>    
</head>

<?php
    $id = $_GET['id'];
    require_once('00 - BD/bd_conexao.php');	
    $sql = " SELECT nome_cliente, cpf_cliente, telefone_cliente, endereco_cliente FROM cliente WHERE id_cliente LIKE $id " ;
    $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
    while($informacaoCliente = mysqli_fetch_object($resultado)){
        $nome= $informacaoCliente->nome_cliente;
        $cpf= $informacaoCliente->cpf_cliente;
        $tel= $informacaoCliente->telefone_cliente;
        $endereco= $informacaoCliente->endereco_cliente;
    }
?>

<body>
    <?php include_once("menu.php")  ?>
    <div class="container">
        <h2 class="text-center"> Últimas Cadernetas </h2>
    <hr />
    <div class="row">   
        <div class="col-lg-2 col-sm-4"> <b>ID do Cliente:</b> <?php echo $id;?></div>
        <div class="col-lg-3 col-sm-5"><b> Nome: </b> <?php echo $nome;?></div>
        <div class="col-lg-3 col-sm-5"><td><b> CPF/CNPJ: </b> <?php echo $cpf;?></div>
        <div class="col-lg-3 col-sm-5"><td><b> Telefone: </b> <?php echo $tel;?></div>
    </div>
    <br />
    <div class="row"> 
        <div class="col-lg-10 col-sm-12"><b> Endereço: </b> <?php echo $endereco;?> <a href="https://www.google.com.br/maps/@-16.6163607,-42.18379,19z" target="_blank">(Clique para pesquisar no Maps)</div></a>
    </div>
    <br />
    <a href="criaCaderneta.php?id=<?php echo $id;?>"><button class=" btn btn-success">Adicionar Caderneta</button></a>
    <a href="cadernetas.php?id=<?php echo $id;?>"><button class=" btn btn-danger">Editar Dados</button></a>
    <?php
    require_once('00 - BD/bd_conexao.php');	
    $sql = " SELECT id_caderneta, data_abertura, data_fechamento, status_caderneta FROM caderneta WHERE id_cliente like '$id' ORDER BY id_caderneta DESC";
    $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
    
    
    ?>
    <hr />
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Aberta em</th>
                    <th scope="col">Fechada em</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ação</th>

                </tr>
            </thead>
            <tbody>
            
            <?php 
            while ($cadernetas = mysqli_fetch_object($resultado)) { 
            $id_cad=$cadernetas->id_caderneta;
            $atraso=0;
            /* Convertendo datas para formato agradável */
            $data_abertura=$cadernetas->data_abertura;
            $data_fechamento=$cadernetas->data_fechamento;
            $timestamp = strtotime($data_abertura);

            // Comparando a data de hoje com a data de criação da caderneta
            $data_hj=date('Y-m-d');
            $intervalo= strtotime($data_hj) - strtotime($data_abertura);
            $days= floor($intervalo / (60 * 60 * 24));
            if($days>=30){
                $atraso=1;
            }
            // Fim da comparação  
            $data_abertura = date("d-m-Y", $timestamp);
            if(!empty($data_fechamento)){
                $timestamp = strtotime($data_fechamento);
                $data_fechamento = date("d-m-Y", $timestamp);
            }
            /* Fim da conversão */

                ?>
                <tr class="linhaTabela">
                    <th scope="row"><?php echo $cadernetas->id_caderneta; ?></th>
                    <td><?php echo $data_abertura; ?></td>
                    <td><?php echo $data_fechamento; if($atraso==1 && $cadernetas->status_caderneta=="aberta"){ echo'<div class="btn bg-danger text-white"> Caderneta em Atraso </div>';}?></td>
                    <?php
                        
                        if($cadernetas->status_caderneta=="aberta"){
                            echo'<td class="text-success">Aberta</td>';
                            echo'
                            <td>
                                <a href="order_carderneta_mes.php?id='.$id_cad.'"><button class="btn btn-primary">Acessar Caderneta</button></a>';
                            
                        }
                        elseif($cadernetas->status_caderneta=="fechada"){
                            echo'<td class="text-danger">Fechada</td>';
                            echo'
                            <td>
                                <a href="order_carderneta_mes.php?id='.$id_cad.'">Acessar Caderneta Fechada  </a>';
                            
                        }
                        ?> <a href="excluirCaderneta.php?<?php echo 'id='.$id.'&id_cad='.$id_cad;?>" onclick="confirmar('excluirCaderneta.php?<?php echo 'id='.$id.'&id_cad='.$id_cad;?>')"><button class="btn btn-primary"><img src="imgs/excluir.png" alt="Excluir" class="imgOpcao"></button></a></td>
                </tr>
                <?php
              } //while
              ?>
            </tbody>
        </table>
    </div>









    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>

<?php
  if (isset($_GET['error03'])) {
    echo '<script> alert("Não foi possível criar uma nova caderneta pois já existe uma caderneta aberta") </script>';
  }
  elseif (isset($_GET['errorBD'])) {
    echo '<script> alert("Ocorreu um erro ao tentar criar uma nova caderneta no Banco de Dados.") </script>';
  }
  elseif (isset($_GET['success'])) {
    echo '<script> alert("Nova Caderneta Cadastrada Com Sucesso!") </script>';
  }

  if(isset($_GET['error04'])){
    echo '<script> alert("Ocorreu um erro ao tentar excluir a Caderneta do Banco de Dados") </script>';
  }
  elseif(isset($_GET['success03'])){
    echo '<script> alert("Caderneta excluída com sucesso") </script>';
  }

?>
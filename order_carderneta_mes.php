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


    <title>Acesso de caderneta - Açougue do Povão</title>
    <script> /* Confirmador de Saída */
    function confirmar(url){
      event.preventDefault();  
      var resposta = confirm("Deseja mesmo excluir essa compra?");
      if (resposta == true){
        window.location.href = url;
      }
  }
</script>    
</head>
<body>
<?php 
//Id da caderneta vai para $id
$id = $_GET['id'];  
//chama o BD
require_once('00 - BD/bd_conexao.php');

//Busca alguns dados na tabela Compra onde a compra tiver o ID da caderneta atual;
$sql = " SELECT id_compra, data_compra, nome_produto, preco_unidade, id_prod_comprado, valor_produto, peso_produto FROM compra where id_caderneta LIKE '$id' ORDER BY id_compra DESC";
$sql6 = " SELECT id_cliente FROM caderneta where id_caderneta LIKE '$id'";
//Busca alguns dados na tabela Caderneta onde a caderneta tiver o ID da caderneta atual;
$sql2 = " SELECT data_abertura, id_cliente, status_caderneta FROM caderneta WHERE id_caderneta like '$id'";
$resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
$resultado3 = $con->query($sql6);
$data= $con->query($sql2) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
$total=0;
// Insere o valor de "nome_cliente" de uma compra qualquer na variável $nome_cliente;
if( $infoCompra2=mysqli_fetch_object($resultado3)){
  $id_cliente=$infoCompra2->id_cliente;
}
$sql7="SELECT nome_cliente FROM cliente where id_cliente = '$id_cliente'";
$resultado4 = $con->query($sql7);
if( $infoCliente3=mysqli_fetch_object($resultado4)){
  $nome_cliente=$infoCliente3->nome_cliente;
}
//a partir daqui a variavel $nome_cliente tem o nome do cliente, $id tem o id da caderneta e $total tem 0;

?>
    <?php include_once("menu.php")  ?>
    <?php
            while ($informacaoCaderneta = mysqli_fetch_object($data)) { 
            /* Convertendo data para formato agradável */
            $data_abertura=$informacaoCaderneta->data_abertura;
            $timestamp = strtotime($data_abertura);
            $data_abertura = date("d-m-Y", $timestamp);
            /* Fim da conversão */
            //O status da caderneta vai pra variavel abaixo
            $status_caderneta=$informacaoCaderneta->status_caderneta;
            ?>
    <div class="container">
        <h4> Nome do Cliente: <span class="text-success"> <?php echo $nome_cliente; ?> </span></h4>
        <h4> ID de Caderneta: <?php echo $id; ?> </h4>
        <p> aberta em : <?php echo $data_abertura ?> </p>
        <?php
              }  //while $informacaoCaderneta
              ?>

        
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Data</th>
                    <th scope="col">Produto</th>
                    <th scope="col">Preço Unitário</th>
                    <th scope="col">Peso</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Ação</th>
                </tr>
            </thead>
            <?php 
            //SELECT id_compra, data_compra, nome_produto, preco_unidade, id_prod_comprado, valor_produto, peso_produto, nome_cliente FROM compra where id_caderneta LIKE '$id' ORDER BY id_compra DESC
            while ($informacaoCompra = mysqli_fetch_object($resultado)) { 
                    //Recolhendo os valores em variáveis
                    $id_compra=$informacaoCompra->id_compra;
                    $prod=$informacaoCompra->nome_produto;
                    $preco_unitario=$informacaoCompra->preco_unidade;
                    $peso=$informacaoCompra->peso_produto;
                    $valor=$informacaoCompra->valor_produto;
                    
                    /* Convertendo data para formato agradável */
                    $data_compra=$informacaoCompra->data_compra;
                    $timestamp = strtotime($data_compra);
                    $data_compra = date("d-m-Y", $timestamp);
                    /* Fim da conversão */      
            ?>
            <tbody>
                <tr class="linhaTabela">
                    <th scope="row"><?php echo $id_compra;?></th>
                        <td><?php echo $data_compra;?></td>
                        <td><?php echo $prod;?></td>
                        <td>R$ <?php echo $preco_unitario;?></td>
                        <td><?php echo $peso;?> kg</td>
                        <td>R$ <?php echo $valor;?></td>
                        <td>
                        <form action="excluirCompra.php?id_compra=<?php echo $id_compra;?>&id_cad=<?php echo $id;?>" method="POST">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="excluirOpcao" value="1"/> Devolver ao Estoque
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="excluirOpcao"  value="2"/> Sem Devolver 
                        </div>
                       <button class="btn btn-danger" type="submit" name="excluiVenda" >Excluir</button>
                        </form></td>
                    </tr>
                    <?php $total+=$valor; ?>
                    <?php
              }  //while $informacaoCompra
              ?>    
            
            </tbody>

        </table>
        <?php 
        $total_pre=$total;
        $total=number_format($total, 2, ',','');//convertendo o Total para uma aparencia agradável?>
        <p class="text-danger"> 
            <spam class="btn btn-warning"><strong > TOTAL:$R$ </strong> <?php echo $total; ?> </spam>
            <?php if($status_caderneta!="fechada"){?>
            <a class="btn btn-primary" href="venderCliente.php?id=<?php echo $id_cliente;?>">Adicionar venda</a>
            
            <form method="POST" action="order_carderneta_mes.php?id=<?php echo $id;?>">
              <label for="valor_pago"><h5 class="text-danger">Digite o Valor Pago:</h5></label>
              <input type="number" placeholder="0,00" step="any" name="valor" id="valor_pago" class="col-2">
              <input type="submit" value="Calcular Troco" class="btn btn-warning"><!-- Posso usar display:none para ocultar -->
            </form>
            
            <?php
            if(isset($_POST['valor'])){
              $pago=$_POST['valor'];
              $troco=$pago-$total_pre;  
                if($troco<0){
                  echo '<script> alert("Valor pago é insuficiente!") </script>';
                  echo '<spam class="btn btn-primary text-danger"><strong class="text-danger">TROCO: '.$troco.'</strong></spam>';
                }
                else{
                  echo '<spam class="btn btn-primary text-dark"><strong class="text-white">TROCO: '.$troco.'</strong></spam>';
                }
              } 
            ?>
            <a class="btn btn-success" href="pagaFatura.php?id=<?php echo $id;?>">Pagar conta</a>
            <?php } ?>
        </p>
        


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
  if (isset($_GET['error05'])) {
    echo '<script> alert("Ocorreu um erro ao tentar fechar a caderneta!") </script>';
  }
  else if (isset($_GET['error06'])) {
    echo '<script> alert("Por favor, especifique se o produto foi ou não devolvido para o estoque!") </script>';
  } 
  else if (isset($_GET['error07'])) {
    echo '<script> alert("Ocorreu um erro ao tentar excluir a compra") </script>';
  }
  elseif (isset($_GET['success04'])) {
    echo '<script> alert("Caderneta Fechada com sucesso") </script>';
  }
  elseif (isset($_GET['success05'])) {
    echo '<script> alert("Compra Excluida com sucesso") </script>';
  }
  else if (isset($_GET['errorBD01'])) {
    echo '<script> alert("Ocorreu um erro ao tentar finalizar a compra!") </script>';
  }
  else if (isset($_GET['errorFixo'])) {
    echo '<script> alert("Ocorreu um erro ao tentar lançar o valor Fixo!") </script>';
  }

?>
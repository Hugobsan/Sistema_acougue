<?php
include_once("seguranca.php");
?>
<!doctype html>
<html lang="pt-br">

<head>
  <title>Fechar Caixa - Açougue do Povão</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSS Personalizado -->
  <link rel="stylesheet" href="css/global_css.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
<script> /* Confirmador de Saída */
    function confirmar(url){
      event.preventDefault();  
      var resposta = confirm("Deseja mesmo excluir esse fechamento de caixa?");
      if (resposta == true){
        window.location.href = url;
      }
  }
</script>    
</head>

<body class="pb40">
  <?php include 'menu.php'; ?>
 
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12 col-lg-5 ">
      <h2>Fechar caixa</h2>
        <hr />
        <form action="fechamentoCaixa.php" method="POST">
            Digite o Valor: R$<input type="number" step="any" name="valorCaixa"> <input class="btn btn-success" type="submit" name="ok">
        </form>
        </div>
      <!-- ===========Painel de Cadastro de Cliente ========== -->
      <div class="col-sm-0 col-lg-1"></div><!-- Espaço entre as divs-->
      <!-- Formulário de Cadastro de Clientes -->
      <div class="col-sm-12 col-lg-4">
        <h1> Pesquisa de Historico de Caixa </h1>
        <hr />
        <form method="POST" action="fechaCaixa.php">
            de <input type="date" class="form-control" name="inicioPesquisa" style="width:300px;" onkeypress="$(this).mask('00/00/0000');" placeholder="Início"> 
            até <input type="date" class="form-control" name="finalPesquisa" style="width:300px;" onkeypress="$(this).mask('00/00/0000');" placeholder="Final"> 
            <button class="btn btn-primary" name="pesquisa" type="submit">Pesquisar</button>
        </form>
    
        <?php
        if(isset($_POST['pesquisa'])){
        require_once('00 - BD/bd_conexao.php');	
        $total = 0;
        if (empty($_POST['inicioPesquisa']) && isset($_POST['pesquisa'])) {//caso nada seja preenchido
            echo '<script> alert("Prreencha os campos corretamente antes de enviar") </script>';
        }

        else if(!empty($_POST['finalPesquisa']) && isset($_POST['pesquisa'])){
            $temp1=$_POST['inicioPesquisa'];
            $temp2=$_POST['finalPesquisa'];
            
            /* Convertendo datas para formato DATE */
            $timestamp = strtotime($temp1);
            $temp1 = date("Y-m-d", $timestamp);
            $timestamp = strtotime($temp2);
            $temp2 = date("Y-m-d", $timestamp);
            /* Fim da conversão */
            $sql = " SELECT id_dia, valor, data_fechamento FROM caixa where data_fechamento BETWEEN '$temp1' AND '$temp2' order by id_dia desc";

        }
        else if(!empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa']) && isset($_POST['pesquisa'])){
            $temp1=$_POST['inicioPesquisa'];
            /* Convertendo datas para formato DATE */
            $timestamp = strtotime($temp1);
            $temp1 = date("Y-m-d", $timestamp);
            /* Fim da conversão */
            $sql = " SELECT id_dia, valor, data_fechamento FROM caixa where data_fechamento LIKE '$temp1' order by id_dia desc";
        }
        $result= $con->query($sql) or die("Erro ao se conectar com o Banco de Dados.");
        $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados.");

        ?>
            <hr/>
            <?php while (isset($_POST['pesquisa']) && $infoCaixa = mysqli_fetch_object($result)) {
                $total+=$infoCaixa->valor;
                
            }
            $total=number_format($total, 2, ',','');
            ?>

                <tr>
                <td>Entradas totais: <button class="btn btn-success text-white">R$ <?php echo $total;?></button></td>
                </tr>
                <table class="table table-striped">
                <thead>
                <tr class="bg-primary">
                    <th scope="col">Valor</th>
                    <th scope="col">Data</th>
                    <th scope="col">Opções</th>
                </tr>
                </thead>
                <tbody>
                <?php while (isset($_POST['pesquisa']) && $infoCaixa = mysqli_fetch_object($resultado)) { 
                    
                    /* Convertendo datas para formato agradável */
                    $data_fechamento=$infoCaixa->data_fechamento;
                    $timestamp = strtotime($data_fechamento);
                    $data_fechamento = date("d-m-Y", $timestamp);   
                    $valor=number_format($infoCaixa->valor, 2, ',','');
                    $id_dia=$infoCaixa->id_dia;

                    /* Fim da conversão */
                    ?>
                    <tr class="linhaTabela">
                    <td> <?php echo $valor;?> </td>
                    <td> <?php echo $data_fechamento;?> </td>
                    <td>
                        <a onclick="confirmar('excluirCaixa.php?id=<?php echo $id_dia?>')"><button class="btn btn-danger" type="submit" name="excluiVenda" >Excluir</button></a>
                    </td>
                    
                    </tr>
                    <?php
                                } //while
                    ?>
                </tbody>
            </table> <hr />
            <?php }//if ?>

      </div>
    </div>
  </div>
  <!-- ===========FIM DO Painel de Cadastro de Cliente ========== -->

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="js/jquery-3.3.1.slim.min.js"></script>
  <script src="js/jquery.mask.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>

</html>

<?php
  if (isset($_GET['error'])) {
    echo '<script> alert("Preencha os campos corretamente e tente novamente.") </script>';
  }
  elseif (isset($_GET['errorBD'])) {
    echo '<script> alert("Ocorreu um erro ao tentar cadastrar no banco de dados.") </script>';
  }
  elseif (isset($_GET['success'])) {
    echo '<script> alert("Cadastro lançado com sucesso!") </script>';
  }

  else if (isset($_GET['errorBD02'])) {
    echo '<script> alert("Ocorreu um erro ao tentar excluir o fechamento!") </script>';
  }
  elseif (isset($_GET['success02'])) {
    echo '<script> alert("Fechamento excluído com sucesso!") </script>';
  }
  elseif (isset($_GET['errorVenda'])) {
    echo '<script> alert("Erro! O Cliente que você tentou vender não tem caderneta aberta no momento!") </script>';
  }
  elseif (isset($_GET['errorFatura'])) {
    echo '<script> alert("Ocorreu um erro ao tentar fechar a caderneta!") </script>';
  }
  elseif (isset($_GET['successFatura'])) {
    echo '<script> alert("Caderneta Fechada com sucesso!") </script>';
  }

?>
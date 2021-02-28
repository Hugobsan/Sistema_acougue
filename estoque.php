<?php
include_once("seguranca.php");
?>
<!doctype html>
<html lang="ptbr">

<head>
  <title>Estoque - Açougue do Povão</title>
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
      var resposta = confirm("Deseja mesmo excluir esse produto?");
      if (resposta == true){
        window.location.href = url;
      }
  }
</script>
</head>

<body>
  <?php include 'menu.php'; ?>
  <div class="row">
    <!-- Lista de Produtos -->
    <?php
    
    require_once('00 - BD/bd_conexao.php');	
    $sql = " SELECT id_produto, nome_produto, preco_unidade, estoque_atual FROM produto ORDER BY id_produto ASC ";
    $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
    ?>
    <div class="col-sm-12 col-lg-5">
      <a href="dadosProduto.php"><button class="btn btn-success col-12"> Adicionar Novo Produto </button></a><br />
      <table class="table table-striped">
        <thead>
          <tr class="bg-primary">
            <th scope="col">ID Produto</th>
            <th scope="col">Nome do Produto</th>
            <th scope="col">Preço Unitário</th>
            <th scope="col">Estoque do Produto</th>
            <th scope="col">Ação</th>
          </tr>
        </thead>
        <tbody>

        <?php
            while ($informacaoProduto = mysqli_fetch_object($resultado)) { 
              
            $preco_unidade=number_format($informacaoProduto->preco_unidade, 2, ',','');
            $estoque_atual=number_format($informacaoProduto->estoque_atual, 3, ',','');
              
              
              ?>
            <tr>
                  <td><?php echo $informacaoProduto->id_produto; ?> </td> 
                  <td><?php echo $informacaoProduto->nome_produto; ?></td>
                  <td>R$ <?php echo $preco_unidade; ?></td>
                  <th scope="row" class="bg-warning estoqueAtual"><?php echo $estoque_atual; ?>Kg</th>
                  <td>
                    <div class="d-flex">
                      <a href="dadosProduto.php?id_prod=<?php echo $informacaoProduto->id_produto;?>"><img src="imgs/lapis.png" alt="Editar" class="imgOpcao"></a>
                      <a href="excluiProduto.php?id_prod=<?php echo $informacaoProduto->id_produto;?>" onclick="confirmar('excluiProduto.php?id_prod=<?php echo $informacaoProduto->id_produto;?>')"><img src="imgs/excluir.png" alt="Excluir" class="imgOpcao""></a>
                    </div>
                  </td>
            </tr>

        <?php
              } //while
              ?>
        </tbody>
          </table> <hr />
    </div>
    <!-- Formulário de Pesquisa de Histórico -->
    <div class="col-sm-12 col-lg-7">
      <form action="estoque.php" method="POST">
        <h3> Pesquisa de histórico </h3>
        <input list="clientes" autocomplete="off" name="nomeCliente" class="form-control" id="select_clientes" placeholder="Pesquisar por Cliente">
        <datalist id="clientes">
        <?php  
          $sql3 = "SELECT nome_cliente FROM cliente ORDER BY nome_cliente ASC";
          $result3 = $con->query($sql3) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
          while($cliDados = mysqli_fetch_object($result3)){
            $nomeCliente=$cliDados->nome_cliente;
            ?>
            <option value="<?php echo $nomeCliente;?>" > 
            </option> 
            <?php
          }
          ?>
        </datalist><br />
        <input list="produtos" autocomplete="off" class="form-control" name="nomeProduto" id="select_prods" placeholder="Pesquisar por Produto">
          <datalist id="produtos">
          <?php  
          $sql3 = "SELECT nome_produto FROM produto ORDER BY nome_produto ASC";
          $result3 = $con->query($sql3) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
          while($prodDados = mysqli_fetch_object($result3)){
            $nomeProd=$prodDados->nome_produto;
            ?>
            <option value="<?php echo $nomeProd;?>" > 
            </option> 
            <?php
          }
          ?>
          </datalist><br />
        <label> Pesquisar por período </label><br />
        <input type="date" class="form-control" name="inicioPesquisa" style="width:300px;" onkeypress="$(this).mask('00/00/0000');" placeholder="Início"><br />
        <input type="date" class="form-control" name="finalPesquisa" style="width:300px;" onkeypress="$(this).mask('00/00/0000');" placeholder="Final"><br />
        <input type="checkbox" name="abertos"> Marque se deseja ver apenas vendas não pagas <br />
        <button class="btn btn-primary" name="pesquisa" type="submit">Pesquisar</button>
      </form>
      
      <?php
      $total = 0;
      $total_founds = 0;
      $total_peso = 0;
      if (!empty($_POST['nomeCliente'])&& empty($_POST['nomeProduto']) && empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])) {//CASO APENAS O NOME DA PESSOA SEJA INFORMADO 
        $nome_cliente = $_POST['nomeCliente'];
        if(isset($_POST['abertos'])){
          if($nome_cliente=="*"){
            $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra WHERE vendido=2 order by id_compra desc";
          }
          else{
                $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_cliente like '%$nome_cliente%' AND vendido=2 order by id_compra desc";
              }
        }
        
        else{
          if($nome_cliente=="*"){
            $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra order by id_compra desc";
          }
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_cliente like '%$nome_cliente%' order by id_compra desc";
        }
      }

      else if (empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])) {//CASO APENAS O NOME DO PRODUTO SEJA INFORMADO 
        if(isset($_POST['abertos'])){
          $temp1=$_POST['nomeProduto'];
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_produto like '%$temp1%' AND vendido=2 order by id_compra desc";
        }
        else{
          $temp1=$_POST['nomeProduto'];
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_produto like '%$temp1%' order by id_compra desc";
        }
      }
      else if (empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])){ //CASO APENAS O INICIO SEJA INFORMADO
        $temp1=$_POST['inicioPesquisa'];
        
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' order by id_compra desc";
        }
      }
      else if (!empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])){ //CASO O NOME E A DATA INICIAL SEJA INFORMADA
        $temp1=$_POST['inicioPesquisa'];
        $nome_cliente=$_POST['nomeCliente'];
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){//caso seja para mostrar apenas produtos não pagos
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra = '$temp1' AND nome_cliente = '%$nome_cliente%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND nome_cliente LIKE '%$nome_cliente%'  order by id_compra desc";
        }
      }
      else if (!empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && !empty($_POST['finalPesquisa'])) {//Caso o nome e as datas inicial e final sejam informadas 
        
        $nome_cliente=$_POST['nomeCliente'];
        $temp1=$_POST['inicioPesquisa'];
        $temp2=$_POST['finalPesquisa'];
        
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        $timestamp = strtotime($temp2);
        $temp2 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_cliente LIKE '%$nome_cliente%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_cliente LIKE '%$nome_cliente%' order by id_compra desc";
        }
        }
      //---------------------------------------------//
      else if (empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])){ //CASO O PRODUTO E DATA INICIAL SEJA INFORMADA
        $temp1=$_POST['inicioPesquisa'];
        $nome_produto=$_POST['nomeProduto'];
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND nome_produto LIKE '%$nome_produto%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND nome_produto LIKE '%$nome_produto%'  order by id_compra desc";
        }
      }
      else if (empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && !empty($_POST['finalPesquisa'])) {//Caso o produto e as datas inicial e final sejam informadas 
        
        $nome_produto=$_POST['nomeProduto'];
        $temp1=$_POST['inicioPesquisa'];
        $temp2=$_POST['finalPesquisa'];
        
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        $timestamp = strtotime($temp2);
        $temp2 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_produto LIKE '%$nome_produto%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_produto LIKE '%$nome_produto%' order by id_compra desc";
        }
        }
        //---------------------------------------------//
      else if (!empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])){ //CASO O CLIENTE E PRODUTO  E DATA INICIAL SEJA INFORMADA
        $temp1=$_POST['inicioPesquisa'];
        $nome_cliente=$_POST['nomeCliente'];
        $nome_produto=$_POST['nomeProduto'];
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra LIKE '$temp1' AND nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' order by id_compra desc";
        }
      }
      else if (!empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && !empty($_POST['finalPesquisa'])) {//Caso o produto e as datas inicial e final sejam informadas 
        
        $nome_cliente=$_POST['nomeCliente'];
        $nome_produto=$_POST['nomeProduto'];
        $temp1=$_POST['inicioPesquisa'];
        $temp2=$_POST['finalPesquisa'];
        
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        $timestamp = strtotime($temp2);
        $temp2 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' order by id_compra desc";
        }
        }

        else if (!empty($_POST['nomeCliente']) && !empty($_POST['nomeProduto']) && empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])) {//Caso o Cliente e Produto sejam inseidos
        
          $nome_cliente=$_POST['nomeCliente'];
          $nome_produto=$_POST['nomeProduto'];

          if(isset($_POST['abertos'])){
            $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' AND vendido=2 order by id_compra desc";
          }
          else{
            $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where nome_produto LIKE '%$nome_produto%' AND nome_cliente LIKE '%$nome_cliente%' order by id_compra desc";
          }
          }
  

        
      
      
      else if (empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && empty($_POST['inicioPesquisa']) && !empty($_POST['finalPesquisa'])) {//CASO APENAS O NOME DO PRODUTO SEJA INFORMADO 
        echo '<script> alert("Ao definir um Fim do período, você deve definir um Inicio!") </script>';
      }
      else if (empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && !empty($_POST['inicioPesquisa']) && !empty($_POST['finalPesquisa'])) {//CASO APENAS O NOME DO PRODUTO SEJA INFORMADO 
        
        $temp1=$_POST['inicioPesquisa'];
        $temp2=$_POST['finalPesquisa'];
        
        /* Convertendo datas para formato DATE */
        $timestamp = strtotime($temp1);
        $temp1 = date("Y-m-d", $timestamp);
        $timestamp = strtotime($temp2);
        $temp2 = date("Y-m-d", $timestamp);
        /* Fim da conversão */
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' AND vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra where data_compra BETWEEN '$temp1' AND '$temp2' order by id_compra desc";
        }
        }
      else if (empty($_POST['nomeCliente']) && empty($_POST['nomeProduto']) && empty($_POST['inicioPesquisa']) && empty($_POST['finalPesquisa'])) {//CASO NENHUM CAMPO SEJA INFORMADO
        if(isset($_POST['abertos'])){
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra WHERE vendido=2 order by id_compra desc";
        }
        else{
          $sql = " SELECT id_compra, data_compra, nome_produto, nome_cliente, peso_produto, valor_produto FROM compra order by id_compra desc";
        }
      }
      $result= $con->query($sql) or die("Erro ao se conectar com o Banco de Dados.");
      $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados.");

      ?>
        <hr/>
        <?php while (isset($_POST['pesquisa']) && $infoCompra = mysqli_fetch_object($result)) {
              $total+=$infoCompra->valor_produto;
              $total_founds++;
              $total_peso+=$infoCompra->peso_produto;
              
          }
          $total=number_format($total, 2, ',','');
          $total_peso=number_format($total_peso, 3, ',','');
          ?>

              <tr>
              <td>Foram encontrados <?php echo $total_founds; ?> produtos</td> <br>
              <td>Lucro total: <button class="btn btn-success text-white">R$ <?php echo $total;?></button></td>
              </tr>
              <tr>
                <td></td>
                <td>Peso total: <button class="btn btn-warning text-white"><?php echo $total_peso;?></button> </td>
              </tr>
              <table class="table table-striped">
              <thead>
              <tr class="bg-primary">
                  <th scope="col">ID Venda</th>
                  <th scope="col">Data</th>
                  <th scope="col">Produto</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Peso</th>  
                  <th scope="col">Valor</th>
                  <th scope="col">Ação</th>
              </tr>
              </thead>
              <tbody>
              <?php while (isset($_POST['pesquisa']) && $infoCompra = mysqli_fetch_object($resultado)) { 
                 
                 /* Convertendo datas para formato agradável */
                  $data_compra=$infoCompra->data_compra;
                  $timestamp = strtotime($data_compra);
                  $data_compra = date("d-m-Y", $timestamp);   
                  $valor_produto=number_format($infoCompra->valor_produto, 2, ',','');
                  $peso_produto=number_format($infoCompra->peso_produto, 3, ',','');

                  /* Fim da conversão */
                ?>
                <tr class="linhaTabela">
                  <td> <?php echo $infoCompra->id_compra;?> </td>
                  <td> <?php echo $data_compra;?> </td>
                  <td> <?php echo $infoCompra->nome_produto;?></td>
                  <td> <?php echo $infoCompra->nome_cliente;?></td>
                  <td> <?php echo $peso_produto;?> Kg</td>
                  <td>R$ <?php echo $valor_produto;?></td>
                  <td>
                    <div class="d-flex">
                    <form action="excluirCompra.php?id_compra=<?php echo $infoCompra->id_compra;?>&id_cad=estoque" method="POST">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="excluirOpcao" value="1"/> Devolver ao Estoque
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="excluirOpcao"  value="2"/> Sem Devolver 
                        </div>
                       <button class="btn btn-danger" type="submit" name="excluiVenda" >Excluir</button>
                        </form>
                    </div>
                  </td>
                  
                </tr>
                <?php
                            } //while
                ?>
              </tbody>
        </table> <hr />
      
    </div>
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
    if (isset($_GET['error06'])) {
      echo '<script> alert("Por favor, especifique se o produto foi ou não devolvido para o estoque!") </script>';
    } 
    else if (isset($_GET['error07'])) {
      echo '<script> alert("Ocorreu um erro ao tentar excluir a compra") </script>';
    }
    elseif (isset($_GET['success05'])) {
      echo '<script> alert("Compra Excluida com sucesso") </script>';
    }
    elseif (isset($_GET['success02'])) {
      echo '<script> alert("Produto Atualizado Com Sucesso!") </script>';
    }
    elseif (isset($_GET['error01'])) {
      echo '<script> alert("Preencha todos campos corretamente antes de enviar o formulário!") </script>';
    }
    elseif (isset($_GET['errorBD'])) {
      echo '<script> alert("Erro ao tentar editar produto!") </script>';
    }
    elseif (isset($_GET['success03'])) {
      echo '<script> alert("Produto excluído com sucesso!") </script>';
    }
    elseif (isset($_GET['error03'])) {
      echo '<script> alert("Ocorreu um erro ao tentar excluir o produto!") </script>';
    }

?>
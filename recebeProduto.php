<?php
  include_once("seguranca.php");
  require_once('00 - BD/bd_conexao.php');
?>

<!doctype html>
<html lang="ptbr">

<head>
  <title>Controle de Pagamento Açougue</title>
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
      var resposta = confirm("Deseja mesmo limpar a tabela?");
      if (resposta == true){
        window.location.href = url;
      }
  }
</script>

</head>

<body>
  <?php include_once 'menu.php'; 
  $total=0;
  ?>

  <div class="VendaManual select_venda">
      <a class="btn btn-dark col-8" data-toggle="collapse" href="#Conteudo01" role="button" aria-expanded="false" aria-controls="collapseExample">
        <h3 class="text-white text-left">Venda Manual</h3>
      </a>
      
      <div class="collapse <?php if(!isset($_GET["tbl_va"])){echo "show";}?> col-8 rounded" id="Conteudo01">
        <form action="cadastraVendaManual.php" method="POST" class="col-12">
          <!--SETAR NO BD id_prod_comprado, peso_produto, valor_produto ///id_compra=NULL, data_compra=Data Atual, cod_barras=NULL, nome_produto=produto->nome_produto, preco_unidade=produto->preco_unidade, valor_produto=peso_produto*preco_unidade, exibir_linha=1 -->
          <br />
          <label for="select_prods"> ID do Produto:</label>
          <input list="produtos" autocomplete="off" name="id_prod_comprado" id="select_prods">
          <datalist id="produtos">
          <?php  
          $sql3 = "SELECT id_produto, nome_produto FROM produto ORDER BY nome_produto ASC";
          $result3 = $con->query($sql3) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
          while($prodDados = mysqli_fetch_object($result3)){
            $idProd=$prodDados->id_produto;
            $nomeProd=$prodDados->nome_produto;
            ?>
            <option value="<?php echo $idProd;?>" > 
              <?php echo $nomeProd; ?>
            </option> 
            <?php
          }
          ?>
          </datalist><br />
          <label for="peso_produto"> Peso do Produto (Ou Quantidade)</label>
          <input type="number" name="peso_produto" id="peso_produto" placeholder="0,000" step="any" class="col-3"> (Kg)<br/>
          <button class="btn btn-success" name="btn_OkCadastraVenda" type="submit">OK</button> 
        </form>
        <br />
        <form method="POST" action="recebeProduto.php?tbl_vm">
          <label for="valor_pago"><h5 class="text-danger">Digite o Valor Pago:</h5></label>
          <input type="number" placeholder="0,00" step="any" name="valor" id="valor_pago" class="col-2">
          <input type="submit" value="Calcular Troco" class="btn btn-warning"><!-- Posso usar display:none para ocultar -->
        </form>
        <br />
        <form method="POST" action="limpaTabela.php?tbl_vm&btn&rp">
        <label for="select_cliente">Atribuir venda a Cliente:</label>
        <input list="clientes" autocomplete="off" name="id_clienteM" id="select_cliente" >
        <datalist id="clientes">
          <?php  
          $sql4 = "SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente ASC";
          $result4 = $con->query($sql4) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
          while($cliDados = mysqli_fetch_object($result4)){
            $idCliente=$cliDados->id_cliente;
            $nomeCliente=$cliDados->nome_cliente;
            ?>
            <option value="<?php echo $idCliente;?>" > 
              <?php echo $nomeCliente; ?>
            </option> 
            <?php
          }
          ?>
          </datalist><br />
        <button class="btn btn-danger" type="submit">Finalizar Venda</button>
        </form>
        <?php if (isset($_GET["tbl_vm"])) { 
      
          ?>

          <hr/> 
          <table class="table table-striped "> 
            <thead>
            <tr class="bg-primary">
              <th scope="col">ID Compra</th>
              <th scope="col">ID Produto</th>
              <th scope="col">Produto</th>
              <th scope="col">Preço Unitario</th>
              <th scope="col">Peso</th>
              <th scope="col">Valor Peça</th>
              <th scope="col">Ação</th>
            </tr>
            </thead>
            <tbody>
          <?php
          	  
          $sql = " SELECT id_compra, id_prod_comprado, nome_produto, peso_produto, preco_unidade, valor_produto FROM compra where exibir_linha=1 ORDER BY id_compra DESC";
          $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
          while ($produto = mysqli_fetch_object($resultado)) { 
          
          //Declaração e arrumação de variáveis
          $peso_produto=$produto->peso_produto;
          $peso_produto=number_format($peso_produto, 3, '.','');
          $valor_produto=$produto->valor_produto;
          $total=$total+$valor_produto;
          $valor_produto=number_format($valor_produto, 2, '.','');
          $preco_unidade=$produto->preco_unidade;
          $preco_unidade=number_format($preco_unidade, 2, '.','');
          $total=number_format($total, 2, '.','');
          //fim da declaração de variáveis
          ?>
            <tr class="linhaTabela">
                  <th scope="row"><?php echo $produto->id_compra; ?></th>
                  <td> <?php echo $produto->id_prod_comprado; ?></td>
                  <td> <?php echo $produto->nome_produto; ?></td>
                  <td>R$ <?php echo $preco_unidade; ?></td>
                  <td> <?php echo $peso_produto; ?>Kg</td>
                  <td>R$ <?php echo $valor_produto; ?></td>
                  <td>
                    <div class="d-flex">
                    <form action="excluirCompra.php?id_compra=<?php echo $produto->id_compra;?>&id_cad=rp" method="POST">
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

        <?php }//while 
        //cálculo de troco
        if(isset($_POST['valor'])){
          //Pega o valor digitado no input
          $valor_pago=$_POST['valor'];
          //Substitui as vírgulas por pontos
          $valor_pago = str_replace(',','.', $valor_pago);
          if($valor_pago<$total){
            echo '<script> alert("Valor pago é insuficiente!") </script>';
          }
          //Faz o cálculo
          $troco=$valor_pago-$total;
          //Formata para 2 casas decimais
          $troco=number_format($troco, 2, '.','');
        }
        ?>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php if(isset($_POST['valor'])){?><td class="bg-warning <?php if($troco<0){echo "text-danger";} else{echo"text-dark";}?> cellPreco"><b>Troco: R$ <?php echo $troco."</b>"; } else{ echo "<td>";}?></td>
                <td class="bg-success text-white cellPreco"><b>Total: R$ <?php echo $total."</b>" ?></td>
              </tr>
        
        </tbody>
        </table>
        <?php }//if ?>
        <hr />
      </div>
    </div>
  
  <!-- ----------VENDA COM CÓDIGO DE BARRAS-------------- -->
  <div class="VendaAutomatica select_venda">
    <a class="btn btn-dark col-8" data-toggle="collapse" href="#Conteudo02" role="button"  aria-expanded="false" aria-controls="collapseExample">
      <h3 class="text-white text-left">Venda Com Código de Barras</h3>
    </a>
    
    <div class="collapse <?php if(!isset($_GET["tbl_vm"])){echo "show";}?> col-8 rounded" id="Conteudo02">
    <br/>
    <form action="cadastraVendaAutomatica.php" method="post">
    <div class="form-group col-12">
      <label>Código de Barras</label>
      <input type="text" name="barras" placeholder="" class='col-8' <?php if(isset($_GET["tbl_va"])){ echo "autofocus";}?>><br/>
      <button class="btn btn-success" name="btn_OkCadastraVenda" type="submit">OK</button>
    </form>
    <br />
    <br />
        <form method="POST" action="recebeProduto.php?tbl_va">
          <label for="valor_pago"><h5 class="text-danger">Digite o Valor Pago:</h5></label>
          <input type="number" placeholder="0,00" step="any" name="valor" id="valor_pago" class="col-2">
          <input type="submit" value="Calcular Troco" class="btn btn-warning"><!-- Posso usar display:none para ocultar -->
        </form>
        <br />
    <form method="POST" action="limpaTabela.php?tbl_va&btn&rp">
        <label for="select_cliente2">Atribuir venda a Cliente:</label>
        <input list="clientes" autocomplete="off" name="id_clienteA" id="select_cliente2" >
        <datalist id="clientes">
          <?php  
          $sql4 = "SELECT id_cliente, nome_cliente FROM cliente ORDER BY nome_cliente ASC";
          $result4 = $con->query($sql4) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
          while($cliDados = mysqli_fetch_object($result4)){
            $idCliente=$cliDados->id_cliente;
            $nomeCliente=$cliDados->nome_cliente;
            ?>
            <option value="<?php echo $idCliente;?>" > 
              <?php echo $nomeCliente; ?>
            </option> 
            <?php
          }
          ?>
          </datalist><br />
        <button class="btn btn-danger" type="submit">Finalizar Venda</button>
        </form>
    
      <?php if (isset($_GET["tbl_va"])) { 
      
      ?>

      <hr/> 
      <table class="table table-striped "> 
        <thead>
        <tr class="bg-primary">
          <th scope="col">ID Compra</th>
          <th scope="col">ID Produto</th>
          <th scope="col">Produto</th>
          <th scope="col">Preço Unitario</th>
          <th scope="col">Peso</th>
          <th scope="col">Valor Peça</th>
          <th scope="col">Ação</th>
        </tr>
        </thead>
        <tbody>
      <?php
      require_once('00 - BD/bd_conexao.php');	  
      $sql = " SELECT id_compra, id_prod_comprado, nome_produto, peso_produto, preco_unidade, valor_produto FROM compra where exibir_linha=1 ORDER BY id_compra DESC";
      $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
      while ($produto = mysqli_fetch_object($resultado)) { 
      
      //Declaração e arrumação de variáveis
      $peso_produto=$produto->peso_produto;
      $peso_produto=number_format($peso_produto, 3, '.','');
      $valor_produto=$produto->valor_produto;
      $total=$total+$valor_produto;
      $valor_produto=number_format($valor_produto, 2, '.','');
      $preco_unidade=$produto->preco_unidade;
      $preco_unidade=number_format($preco_unidade, 2, '.','');
      $total=number_format($total, 2, '.','');
      //fim da declaração de variáveis
      ?>
        <tr class="linhaTabela">
              <th scope="row"><?php echo $produto->id_compra; ?></th>
              <td> <?php echo $produto->id_prod_comprado; ?></td>
              <td> <?php echo $produto->nome_produto; ?></td>
              <td>R$ <?php echo $preco_unidade; ?></td>
              <td> <?php echo $peso_produto; ?>Kg</td>
              <td>R$ <?php echo $valor_produto; ?></td>
              <td>
                <div class="d-flex">
                <form action="excluirCompra.php?id_compra=<?php echo $produto->id_compra;?>&id_cad=rp" method="POST">
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

    <?php }//while 
    //cálculo de troco
    if(isset($_POST['valor'])){
      //Pega o valor digitado no input
      $valor_pago=$_POST['valor'];
      //Substitui as vírgulas por pontos
      $valor_pago = str_replace(',','.', $valor_pago);
      if($valor_pago<$total){
        echo '<script> alert("Valor pago é insuficiente!") </script>';
      }
      //Faz o cálculo
      $troco=$valor_pago-$total;
      //Formata para 2 casas decimais
      $troco=number_format($troco, 2, '.','');
    }
    ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php if(isset($_POST['valor'])){?><td class="bg-warning <?php if($troco<0){echo "text-danger";} else{echo"text-dark";}?> cellPreco"><b>Troco: R$ <?php echo $troco."</b>"; } else{ echo "<td>";}?></td>
            <td class="bg-success text-white cellPreco">Total: R$ <?php echo $total ?></td>
          </tr>
    
    </tbody>
    </table>
    <?php }//if ?>
    <hr />
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
  elseif (isset($_GET['success05'])) {
    echo '<script> alert("Compra Excluida com sucesso!") </script>';
  }
  elseif (isset($_GET['errorConexao'])) {
    echo '<script> alert("Ocorreu um erro ao iniciar o Sistema. Reinicie o Computador!") </script>';
  }
  elseif(isset($_GET['errorBarcode'])){
    echo '<script> alert("Por favor verifique o código de barras inserido!") </script>';
  }
  elseif (isset($_GET['error01'])) {
    echo '<script> alert("Por favor, preencha todos os campos antes de dar OK!") </script>';
  }
  elseif (isset($_GET['errorBDs01'])) {
    echo '<script> alert("Desculpe, ocorreu um erro inesperado ao tentar cadastrar a compra!") </script>';
  }
  elseif (isset($_GET['errorBD01'])) {
    echo '<script> alert("Desculpe, ocorreu um erro inesperado no banco de dados!") </script>';
  }
  else if (isset($_GET['error07'])) {
    echo '<script> alert("Erro! O Código De Barras não foi preenchido!") </script>';
  }
  else if (isset($_GET['errorId'])) {
    echo '<script> alert("Erro! O Produto Lido ainda não foi cadastrado!") </script>';
  }
  else if (isset($_GET['errorId2'])) {
    echo '<script> alert("Erro! Não Existe Produto com o ID Inserido!") </script>';
  }
  else if (isset($_GET['errorAberta'])) {
    echo '<script> alert("Erro! O cliente selecionado não possui caderneta aberta!") </script>';
  }
  else if (isset($_GET['successCliente'])) {
    echo '<script> alert("Venda atribuida com sucesso!") </script>';
  }
?> 
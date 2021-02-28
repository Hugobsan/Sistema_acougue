<?php
  include_once("seguranca.php");
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

</head>

<body>
  <?php include_once 'menu.php'; 
  if(isset($_GET['id_prod'])){
    require_once('00 - BD/bd_conexao.php');	
    $id_produto=$_GET['id_prod'];
    $sql1="SELECT nome_produto, preco_unidade, estoque_atual, produto_unitario FROM produto where id_produto LIKE '$id_produto'";
    $result01=$con->query($sql1) or die("Erro ao se conectar com o Banco de Dados.");
    while($produto=mysqli_fetch_object($result01)){
        $nome_produto=$produto->nome_produto;
        $preco_unidade=$produto->preco_unidade;
        $preco_unidade=number_format($preco_unidade, 2, '.','');
        $estoque_atual=$produto->estoque_atual;
        $estoque_atual=number_format($estoque_atual, 3, '.','');
        $prod_unitario=$produto->produto_unitario;
    }
  }
  else{ // Variavel usada aqui apenas para decidir se o checkbox deve ou não iniciar ligado
    $prod_unitario=0;
  }
  
  ?>
  <div style="padding-left:20px;"> 
  <h1><?php if(!isset($_GET['id_prod'])){echo "Cadastro de "; } ?>Produto <?php if(!empty($id_produto)){echo ' : '.$nome_produto.'';} ?></h1> <!-- Se estiver editando algum produto, exibir nome do produto, senão, exibir "novo" -->
  <hr />
  <form action="<?php if(!isset($_GET['id_prod'])){echo"cadastraProduto.php";} else{echo"atualizaProduto.php";}?>" method="post">
    <label>ID Na Balança: </label><input type="number" name="idProduto" <?php if(!empty($id_produto)){echo 'readonly="true" value="'.$id_produto.'"';} ?>> <input type="checkbox"  id="prodUnitario" name="prodUnitario" <?php if($prod_unitario==1){echo ' checked';}?>> Ou marque se for um produto vendido por unidade (Produtos que não vão na balança) <br/>
    <label>Nome do Produto: </label><input type="text" name="nomeProd" <?php if(!empty($id_produto)){echo 'value="'.$nome_produto.'"';} ?>><br/>
    <label>Preço da Unidade:R$ </label><input type="number" step="any" name="precoUni" <?php if(!empty($id_produto)){echo 'value="'.$preco_unidade.'"';}?>><br/>
    <label>Estoque Atual:</label><input type="number" step="any" name="estoque" <?php if(!empty($id_produto)){echo 'value="'.$estoque_atual.'"';} ?>> kg ou un.<br/>
    <button class="btn btn-success" type="submit" name="enviaDados">Concluir</button> 

  </form>
</div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>

<?php
  if (isset($_GET['error01'])) {
    echo '<script> alert("Preencha todos campos corretamente antes de enviar o formulário!") </script>';
  }
  elseif (isset($_GET['errorBD'])) {
    echo '<script> alert("Ocorreu um erro ao tentar cadastrar o produto no banco de dados.") </script>';
  }
  elseif (isset($_GET['success'])) {
    echo '<script> alert("Novo Produto Cadastrado Com Sucesso!") </script>';
  }
  elseif (isset($_GET['errorNome'])) {
    echo '<script> alert("Erro! Já existe um produto com esse nome!") </script>';
  }
  elseif (isset($_GET['errorID'])) {
    echo '<script> alert("Erro! Já existe um produto com essa ID cadastrado!") </script>';
  }
  

?>
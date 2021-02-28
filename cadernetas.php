<?php
include_once("seguranca.php");
?>
<!doctype html>
<html lang="pt-br">

<head>
  <title>Cadernetas - Açougue do Povão</title>
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
      var resposta = confirm("Deseja mesmo excluir esse cliente?");
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
        <h1> Pesquisa de Clientes </h1>
        <hr />
        <form action="cadernetas.php" method="POST">
          <input type="text" name="pesquisado" class="form-control" placeholder="Digite o nome do cliente">
          <button class="btn btn-primary" name="pesquisa" type="submit">Pesquisar</button> 
        </form>
      <!-- =================EXIBINDO INFORMAÇÕES SEM PESQUISAR====================== -->
        <?php
        if(!isset($_POST['pesquisa'])){ /* Exibir sem pesquisar */
          
          require_once('00 - BD/bd_conexao.php');	
          $sql = " SELECT id_cliente, nome_cliente FROM cliente ORDER BY id_cliente DESC";
          $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
          ?>
          
          <hr/>
          <table class="table table-striped"> 
            <thead>
              <tr class="bg-primary">
                  <th scope="col">ID</th>
                  <th scope="col">Nome do Cliente</th>
                  <th scope="col">Ação</th>
              </tr>
            </thead>
            <tbody>
          
          <?php
            while ($informacaoCliente = mysqli_fetch_object($resultado)) { ?>
              <tr class="linhaTabela">
                  <th scope="row"><?php echo $informacaoCliente->id_cliente; ?></th>
                  <td> <?php echo $informacaoCliente->nome_cliente; ?></td>
                  <td>
                      <a href="cadernetaCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/caderneta.png" alt="Caderneta" class="imgOpcao"> </a>
                      <a href="venderCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/cifrao.png" alt="Vender Para" class="imgOpcao"> </a>
                      <a href="excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>" onclick="confirmar('excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>')"> <img src="imgs/excluir.png" alt="Excluir" class="imgOpcao"> </a>
                  </td>            
            </tr>
          <?php
              } //while
              ?>

      </tbody>

        </table>
        <?php 
        } //fecha if 
        ?>
        
  <!-- ===========Fim da exibição sem pesquisa========== -->

<!-- ===========Inicio de Exibição com pesquisa ========== -->
<?php
  require_once('00 - BD/bd_conexao.php');	  
  if (isset($_POST['pesquisa'])){
    if($_POST['pesquisado']!='atrasos'){
      $nomePesquisado=$_POST['pesquisado'];
      $sql = " SELECT id_cliente, nome_cliente FROM cliente where nome_cliente like '%$nomePesquisado%' ORDER BY id_cliente DESC";
      $resultado = $con->query($sql) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos... Se o problema presistir, contate o responsável pelo sistema");
      $atraso=1;
    }
    //Exibição de Clientes com atraso
    elseif($_POST['pesquisado']=='atrasos'){
      $status="aberta";
      $sql3="SELECT data_abertura, id_cliente FROM caderneta WHERE status_caderneta='$status' ORDER BY id_cliente DESC";
      $result03= $con->query($sql3) or die("Erro ao buscar os clientes com atraso!");
      ?>
      <table class="table table-striped"> 
      <h3 class="text-center text-danger"> Clientes com atraso </h3>
      <thead>
        <tr class="bg-primary">
            <th scope="col">ID</th>
            <th scope="col">Nome do Cliente</th>
            <th scope="col">Ação</th>
        </tr>
      </thead>
      <tbody><?php
    $lastid=0;

      while($cadernetas = mysqli_fetch_object($result03)){
      // Comparando a data de hoje com a data de criação da caderneta
        $data_abertura=$cadernetas->data_abertura;
        $data_hj=date('Y-m-d');
        $intervalo= strtotime($data_hj) - strtotime($data_abertura);
        $days= floor($intervalo / (60 * 60 * 24));
        if($days>=30){
          $id_cliente=$cadernetas->id_cliente;
        }
        else{
          $id_cliente=NULL;
        }
    
    $sql4="SELECT id_cliente, nome_cliente FROM cliente where id_cliente = '$id_cliente' ORDER BY id_cliente DESC";
    $result8=$con->query($sql4) or die("Erro ao se conectar com o Banco de Dados. Tente novamente em alguns minutos...");
    while ($informacaoCliente = mysqli_fetch_object($result8)) {
      if($lastid!=$informacaoCliente->id_cliente){
        ?>
        <tr class="linhaTabela">
            <th scope="row"><?php echo $informacaoCliente->id_cliente; ?></th>
            <td> <?php echo $informacaoCliente->nome_cliente; ?></td>
            <td>
                <a href="cadernetaCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/caderneta.png" alt="Caderneta" class="imgOpcao"> </a>
                <a href="venderCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/cifrao.png" alt="Vender Para" class="imgOpcao"> </a>
                <a href="excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>" onclick="confirmar('excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>')"> <img src="imgs/excluir.png" alt="Excluir" class="imgOpcao"> </a>
            </td>
        </tr>

    <?php
      }
    $lastid=$informacaoCliente->id_cliente;
      }} //while
        ?>

      </tbody>

      </table>
<?php
    } //Fim da Exibição de clientes com atraso
?> 
          <hr/>
          <?php if($_POST['pesquisado']!='atrasos'){?>
          <table class="table table-striped"> 
            <thead>
              <tr class="bg-primary">
                  <th scope="col">ID</th>
                  <th scope="col">Nome do Cliente</th>
                  <th scope="col">Ação</th>
              </tr>
            </thead>
            <tbody>
          
          <?php
            while ($informacaoCliente = mysqli_fetch_object($resultado)) {
              ?>
              <tr class="linhaTabela">
                  <th scope="row"><?php echo $informacaoCliente->id_cliente; ?></th>
                  <td> <?php echo $informacaoCliente->nome_cliente; ?></td>
                  <td>
                      <a href="cadernetaCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/caderneta.png" alt="Caderneta" class="imgOpcao"> </a>
                      <a href="venderCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>"> <img src="imgs/cifrao.png" alt="Vender Para" class="imgOpcao"> </a>
                      <a href="excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>" onclick="confirmar('excluirCliente.php?id=<?php echo $informacaoCliente->id_cliente; ?>')"> <img src="imgs/excluir.png" alt="Excluir" class="imgOpcao"> </a>
                  </td>
              </tr>

          <?php
            } //while
              ?>

      </tbody>

        </table>
        <?php 
          //fecha if 
        ?>
        <?php // VERIFICAR SE A PESQUISA GEROU ALGUM RESULTADO
          if (mysqli_num_rows($resultado) == 0) {
              echo "<h5>Nenhum cliente foi encontrado, talvez ele deva ser cadastrado.</h5>";
            }
          }}?>
           
      </div>
      <!-- ===========FIM de Exibição com pesquisa ========== -->
     
      <?php
        if(isset($_GET['id'])){
          $id=$_GET['id'];
          $sql2="SELECT nome_cliente, cpf_cliente, telefone_cliente, endereco_cliente FROM cliente WHERE id_cliente = '$id'";
          $result01=$con->query($sql2) or die("Erro ao se conectar com o Banco de Dados.");
          while($cliente=mysqli_fetch_object($result01)){
          $nome_cliente=$cliente->nome_cliente;
          $cpf=$cliente->cpf_cliente;
          $tel=$cliente->telefone_cliente;
          $endereco=$cliente->endereco_cliente;
          }
        }
        fecharConexao($con); //else   
      ?>

      <!-- ===========Painel de Cadastro de Cliente ========== -->
      <div class="col-sm-0 col-lg-1"></div><!-- Espaço entre as divs-->
      <!-- Formulário de Cadastro de Clientes -->
      <div class="col-sm-12 col-lg-4">
        <h1> Cadastro de Clientes </h1>
        <hr />
        <form method="POST" <?php if(isset($_GET['id'])){echo 'action=atualizaCliente.php?id='.$id.'';} else{ echo 'action="cadastraCliente.php"';}?>>
          <label> Nome do Cliente: </label>
          <input type="text" class="form-control" name="nomeCliente" placeholder="Nome Completo" <?php if(isset($_GET['id'])){echo 'value="'.$nome_cliente.'"';}?>><br />
          <label> CPF </label>
          <input type="text" class="form-control" name="cpfCliente" style="width:300px;" onkeypress="$(this).mask('000.000.000-00');" placeholder="EX: 999.999.999-99" <?php if(isset($_GET['id']) && strlen($cpf)==14){echo 'value="'.$cpf.'"';}?>><br /><!-- O Onkeypress serve pra criar uma máscara que automatiza os pontos e traços do CPF -->
          <label>ou  CNPJ </label>
          <input type="text" class="form-control" name="cnpjCliente" style="width:300px;" onkeypress="$(this).mask('00.000.000/0000-00')" placeholder="EX: 99.999.999/9999-99" <?php if(isset($_GET['id']) && strlen($cpf)>14){echo 'value="'.$cpf.'"';}?>><br />
          <label> Telefone </label>
          <input type="text" class="form-control" name="telCliente" style="width:300px;" onkeypress="$(this).mask('(00) 00000-0009')" placeholder="EX: (33) 98800-0000" <?php if(isset($_GET['id'])){echo 'value="'.$tel.'"';}?>><br />
          <label> Endereço </label>
          <input type="text" class="form-control" name="enderecoCliente" style="width:300px;" placeholder="Rua:, Nº:, Bairro:, Cidade:, CEP" <?php if(isset($_GET['id'])){echo 'value="'.$endereco.'"';}?>><br />
          <button type="submit" name="okCadastroCliente" class="btn btn-primary">Cadastrar</button>
        </form>
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
    echo '<script> alert("Ocorreu um erro ao tentar lançar os dados do cliente no banco de dados.") </script>';
  }
  elseif (isset($_GET['success'])) {
    echo '<script> alert("Dados do Cliente Lançados Com Sucesso!") </script>';
  }

  if (isset($_GET['error01'])) {
    echo '<script> alert("Ocorreu um erro ao tentar excluir o cliente do banco de dados.") </script>';
  }
  elseif (isset($_GET['success02'])) {
    echo '<script> alert("Cliente Excluido Com Sucesso!") </script>';
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
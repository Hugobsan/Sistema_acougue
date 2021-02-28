<?php 
include_once("seguranca.php");
session_start();
?>


<!doctype html>
<html lang="ptbr">
    <title>Configurações - Açougue do Povão</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="css/global_css.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->

</head>
  <body>
    <?php include 'menu.php'; ?>
    <h1> Redefinição de Dados de Login </h1>
    <hr>
    <div class="redefinidores">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <form action="atualizaLogin.php" method="POST">
                    <h3> Redefinir Login </h3>
                    <hr>
                    <label>Login Atual:</label>
                    <input type="text" class="border" name="rootAtual"><br />
                    <label>Novo Login:</label>
                    <input type="text" class="border" name="rootNovo"><br />
                    <button class="btn btn-primary" type="submit" name="redefRoot">Redefinir Login </button>
                </form>
            </div>
            <div class="col-sm-0 col-lg-4"></div>
            <div class="col-sm-12 col-lg-3">
              <br />
                <form action="processa.php" method="post">
                <h3> Fazer Backup do Banco de Dados </h3>
                  <?php
                  if(isset($_SESSION['msg']) && !isset($_SESSION['msg2'])){
                    echo "<p>".$_SESSION['msg']."</p>";
                    unset($_SESSION['msg']);
                  }
                  ?>
                <br />
                <button class="btn btn-success" type="submit">Fazer Backup</button>
              </form>
            </div>
        </div>
        <hr>
        <div class="row">    
            <div class="col-sm-12 col-lg-3 redefinidor">
                <form action="atualizaSenha.php" method="POST">
                    <h3> Redefinir Senha </h3>
                    <hr>
                    <label> Senha Atual:</label>
                    <div class="wrap-input100">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100 border" type="password" name="passAntiga">
					</div>
                    <label> Nova Senha: </label>
                    <div class="wrap-input100">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100 border" type="password" name="passNova">
					</div>
                    <button class="btn btn-primary" type="submit" name="redefPass">Redefinir Senha </button>
        </div>
        </form>
        <div class="col-sm-0 col-lg-4"></div>
            <div class="col-sm-12 col-lg-3">
              
              <br />
                <h3> Redefinir Base de Dados </h3>
                  <?php
                  if(isset($_SESSION['msg2'])){
                    echo "<p>".$_SESSION['msg2']."</p>";
                    unset($_SESSION['msg2']);
                  }
                  ?>
                <br />
                <a href="processa2.php"><button class="btn btn-success" type="submit">Redefinir Base de Dados</button></a>
  
            </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="js/jquery-3.3.1.slim.min.js"></script>
  <script src="js/jquery.mask.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
</body>
</html>
<?php
  if (isset($_GET['error07'])) {
    echo '<script> alert("Preencha todos os campos correspondentes antes de redefinir o Login!") </script>';
  }
  elseif (isset($_GET['error08'])) {
    echo '<script> alert("Erro! O Login Atual Inserido é Incorreto!") </script>';
  }
  elseif (isset($_GET['error09'])) {
    echo '<script> alert("Preencha os campos correspondentes antes de redefinir a Senha!") </script>';
  }
  elseif (isset($_GET['errorBD'])) {
    echo '<script> alert("Erro ao se conectar com o Banco de dados!") </script>';
  }
  elseif (isset($_GET['error10'])) {
    echo '<script> alert("Erro! A Senha Atual Inserida é Incorreta!") </script>';
  }
  elseif (isset($_GET['success06'])) {
    echo '<script> alert("Login Alterado Com Sucesso!") </script>';
  }
  elseif (isset($_GET['success07'])) {
    echo '<script> alert("Senha Alterada Com Sucesso!") </script>';
  }

?>

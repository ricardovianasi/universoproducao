<? 
session_start(); if ( !isset($_SESSION['paginaRestrita']) ) header( "location: index.php?msg=Acesso restrito"); 
?>

<? include_once("../head.php");?>
<? include_once("../includes/conexao.php");?>

<body>

<div id="container">

  <?
	
	
	$style[2] =' style="background-color:#37A1BB"';
	$style[3] ='class="selecionado"';
  include_once("../cabecalho_interna.php"); ?>
	<? include_once("mostras_sub_menu.php"); ?>
  <div id="mainContentInternoFull">

			
        <div class="titulo_geral" >
              Bem Vindo<br /> ao <br />Sistema de Gestão de Conte&uacute;do<br />
              <div style="font-size:14px;margin-top:20px"> 
 
                  

              
              </div>
        </div>
    
  
	<!-- fim #mainContent --></div>

<br class="clearfloat" />

<? include_once("../rodape.php"); ?>

<!-- fim #container --></div>



</body>
</html>

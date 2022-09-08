 <?php include 'settings.php';
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Справочник</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="icon" href="images/ico_pb.png">
<!--link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" /-->

<link href="css/main.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/script.js"></script>
<script src="js/js-search.js"></script>
<script src="js/ajaxupload.js"></script>
<script src="js/scroll.js"></script>
<!--script src="js/login-script.js"></script-->
</head>
<body>

<div class='main_wrapper'>
	<div class='head_of_page'>
		<!--div class='emblem_gegn'>
		</div-->
		<a class="emblem_gegn" href="http://web.nadzor.net/" title=""></a>
		<div class='naim_org'>
			<p>Министерство энергетики Республики Беларусь</p>
			<h1>Государственное учреждение <span>"Государственный энергетический и газовый надзор"</span></h1>	
		</div>
		<div class='link_admin'>
		<?php if(isset($_SESSION['user'])){
		  echo '<a class="" href="/phonebook/admin/administrator.php" title="">Администрирование</a>';
		}else{
			 echo '<a class="" href="/phonebook/admin/login.php?work_dir=phonebook" title="">Войти</a>';
		}
		?>
		<!--a href="printpdf.php" style='display: block'>Печать PDF</a-->
		
		</div>
	</div>
<div class="tooltip"><a href='javascript:scroll(0,0)'><div class='up_page'><span class="tooltiptext">В начало страницы</span></div></a></div>
<div class = "tooltip"><a href='../'><div class='to_site'><span class="tooltiptext">На главную</span></div></a></div>

<div class = "tooltip"><a href='index.php'><div class='to_main_page'><span class="tooltiptext">Справочник</span></div></a></div>
<div class = "tooltip"><a href='../ARM/basis/admin/'><div class='to_basis'><span class="tooltiptext">в ПО "Базис"</span></div></a></div>
<div class = "tooltip"><button class="print_word to_print"><span class="tooltiptext">Версия для печати</span></button></div>
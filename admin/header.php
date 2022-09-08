<?php include 'settings.php';
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="icon" href="images/ico_admin.png" />


<link href="/phonebook/css/main.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/jquery-3.0.0.min.js"></script>
<!--script src="js/script.js"></script>
<script src="js/js-search.js"></script>
<script src="js/ajaxupload.js"></script-->
<script src="js/login-script.js"></script>
<script src="js/admin.js"></script>
<script src="js/jquery.maskedinput.js"></script>

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
		  echo '<a class="" href="/phonebook/admin/proc/logout.php" title="">Выйти</a>';
		}else{
			 echo '<a class="" href="/phonebook/admin/login.php" title="">Войти</a>';
		}
		?>
		</div>
	</div>

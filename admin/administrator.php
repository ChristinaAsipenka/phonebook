<?php include '../admin/header.php';

//session_start();
?>



<div class='wrapper wrapper-admin'>
	
<?php	

if(isset($_SESSION['user'])){
if($_SESSION['user']['spr_admin']==1 or $_SESSION['user']['admin']==1){?>
	
	<div class='admin-menu'>
	<ul id="accordion" class="accordion">
		 <li>
			<div class="link"><i class="fa fa-database"></i>Структура<i class="fa fa-chevron-down"></i></div>
			
			<ul class="submenu">
			  <li><a href="proc/download-spr.php?name_spr=branch">Ур1 Филиалы</a></li>
			  <li><a href="proc/download-spr.php?name_spr=podrazd">Ур2 Отделения</a></li>
			  <li><a href="proc/download-spr.php?name_spr=district">Ур3 РЭГИ, отделы</a></li>
			</ul>
		 </li>
		 <li>
			<div class="link"><i class="fa fa-code"></i>Персонал<i class="fa fa-chevron-down"></i></div>
				<ul class="submenu">
				  <li><a href="proc/download-spr.php?name_spr=users">В штате</a></li>
				  <li><a href="proc/download-spr.php?name_spr=fired">Уволенные</a></li>
				</ul>
		 </li>
		 <li>
			<div class="link"><i class="fa fa-code"></i>Справочники<i class="fa fa-chevron-down"></i></div>
				<ul class="submenu">
				  <li><a href="ip_book/empty.php">Должности</a></li>
				  <li><a href="proc/download-spr.php?name_spr=ipgrp">Группы IP-телефонии</a></li>
				</ul>
		 </li>
		 <li>
			<div class="link"><i class="fa fa-code"></i>Настройки<i class="fa fa-chevron-down"></i></div>
				<ul class="submenu">
					<li><a href="proc/download-spr.php?name_spr=rules">Права</a></li>
					<li><a href="ip_book/empty.php">В разработке</a></li>
				</ul>
		 </li>
		 <li>
			<div class="link"><i class="fa fa-code"></i>Операции<i class="fa fa-chevron-down"></i></div>
				<ul class="submenu">
					<li><a href="ip_book/unload.php">Выгрузки</a></li>
					
					<li><a href="..\..\statistics\stat_index.php">Статистика</a></li>
				</ul>
		 </li>

		 
		 
	</ul>

	</div>
	
<?php }else{ ?>
	<div class = "info">
	<p class = "otmena">У Вас нет прав для администрирования справочника</p>
	</div>
<?php }?>
<?php } ?>
	<div class='admin-body'>
	<?php ?>
	</div>
</div>


<!-----------------Модальное окно------------------------------>
<div id="openModal" class="modalDialog">
	<div>
		<button title="закрыть" class="close">x</button>
			<div class="modalform">
			<div id='form_body'>
				
		
					
			</div>
		</div>
	</div>

</div>
<div id="overlay"></div>	

<!----------------- END Модальное окно------------------------------>


<?php include 'footer.php';?>
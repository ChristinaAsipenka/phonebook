
<?php include 'header.php';?>
<link rel="stylesheet" href="/phonebook/css/style_img.css">
<div class='main-body'>
	
<?php 



if(isset($_GET['id'])){
	
	$id = (int)$_GET['id'];
	$res_users = mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM `users` WHERE id = $id"));
	 
	$res_otd =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_otdel WHERE id=$res_users->spr_cod_otd"));
	$res_podrazdelenie =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id=$res_users->spr_cod_podrazd"));
	$res_branch =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_branch WHERE id=$res_users->spr_cod_branch"));

}
/*mysqli_close($connect);*/
?>

	<br><br>
	<div class = "block_branch_inner">
	<?php
	//---------------------- Наполнение бордовой полосы----------------------
		echo "<h2 class='name_branch'>$res_branch->name</h2>";
	?>
	</div>

	<div class = "block_podrazd_inner">
	<?php
		echo "<h3>$res_podrazdelenie->name_podrazd</h3>";
	?>
	</div>
	
	<div class = "block_otd_inner">
	<?php 
	//---------------------- Наполнение синей полосы----------------------
		echo "<h4>$res_otd->name_otdel</h4>"; 				
	?>
	</div>
		
		<div class='person_data'>
			<div class='block_link'><p class='name'><span class='fam'><?php echo $res_users->fam.' ' ?></span><span class='name'><?php echo $res_users->name.' '?></span><span class='otch'><?php echo $res_users->otch; ?></p></div>
			<div class='data_block'>
			<div class='person-photo '>
				<img src='images/users_photo/<?php echo $res_users->photo; ?>' alt='$res_users->fio'>
			</div>
				<div>
				<p><b><?php echo $res_users->dolgnost; ?></b></p>
								
				<p <?php  echo (strlen($res_users->phone)>0 ?  "":"class='none-info'");?>>Телефон (гор.): <a href='tel:<?php echo str_replace('(','',str_replace(')','',$res_users->phone)) ; ?>' class='link_phone link_phone_1'><?php echo (strlen($res_users->phone)>0 ? $res_users->phone : "" );?></a></p>
					
				<p <?php  echo (strlen($res_users->mobile_phone)>0 ?  "":"class='none-info'");?>>Мобильный телефон: <a href='tel:<?php echo str_replace('(','',str_replace(')','',$res_users->mobile_phone));?>' class='link_phone link_phone_2'><?php echo (strlen($res_users->mobile_phone)>0 ?  $res_users->mobile_phone:""); ?> </a></p>
				
				
				<p <?php  echo (strlen($res_users->ip_phone)>0 ?  "":"class='none-info'");?>>IP телефон: <a href='tel:<?php echo $res_users->ip_phone;?>' class='ip_phone link_phone link_phone_3'><?php echo $res_users->ip_phone ; ?></a></p>
				<p <?php  echo (strlen($res_users->rup_phone)>0 ?  "":"class='none-info'");?>>АТС ГПО "БелЭнерго": <a href='tel:<?php echo $res_users->rup_phone;?>' class='branch_phone link_phone link_phone_4'><?php echo $res_users->rup_phone; ?></a></p>
				<p <?php  echo (strlen($res_users->branch_phone)>0 ?  "":"class='none-info'");?>>Внутренняя АТС: <a href='tel:<?php echo $res_users->branch_phone;?>' class='branch_phone link_phone link_phone_5'><?php echo $res_users->branch_phone; ?></a></p>
				<p <?php  echo (strlen($res_users->email)>0 ?  "":"class='none-info'");?>>e-mail: <a href='mailto:<?php echo $res_users->email;?>' class = 'e-mail'><?php echo $res_users->email; ?></a></p>
				</div>
			</div>
			<div class='add_block_link'><a href='branch-list.php?id=<?php echo $res_branch->id;?>'><?php echo $res_branch->sokr_name; ?></a><a href='department.php?id=<?php echo $res_podrazdelenie->id;?>'><?php echo $res_podrazdelenie->sokr_name; ?></a><a href='district.php?id=<?php echo $res_otd->id;?>'><?php echo $res_otd->sokr_name; ?></a></div>
			</div>
			
		
			<div class='block_link'>
				<p class='name'><span class='fam'><?php echo "Брендинговая продукция:" ?></span></p>
				
			</div>
			
			<div class="container">
				<div class="element-1">
					<div class="container_img">
						<img src="/phonebook/admin/gd/ico_badge1.png" width=200 height=140 alt="" />
						<p class="title">БЕЙДЖ1</p>
						<div class="overlay"></div>
						<div class="button"><a href="/phonebook/admin/gd/gd_badge1.php?id=<?php echo $id ?>"> СКАЧАТЬ </a></div>					
					</div>
				</div>
				<div class="element-2"></div>
				<div class="element-3"><b>Бейдж сотрудника Госэнергогазнадзора (лицевая сторона).</b></br></br>Состоит из двух файлов формата PNG размером 1000 х 1400.</br>Рекомендуется использовать в двухсторонней печати размером 72 х 100мм (и более в соответствующих пропорциях) с последующей ламинацией и ношением с использованием ленты на шее.</div>
			</div>
			
			<div class="container">
				<div class="element-1">
					<div class="container_img">
						<img src="/phonebook/admin/gd/ico_badge2.png" width=200 height=140 alt="" />
						<p class="title">БЕЙДЖ2</p>
						<div class="overlay"></div>
						<div class="button"><a href="/phonebook/admin/gd/gd_badge2.php?id=<?php echo $id ?>"> СКАЧАТЬ </a></div>					
					</div>
				</div>
				<div class="element-2"></div>
				<div class="element-3"><b>Бейдж сотрудника Госэнергогазнадзора (обратная сторона).</b></br></br>На обратной стороне размещён QR-код визитной карточки сотрудника с необходимой контактной информацией. При сканировании QR-кода предлагается сохранить визитную карточку сотрудника в контакты смартфона.</div>
			</div>
			
			<div class="container">
				<div class="element-1">
					<div class="container_img">
						<img src="/phonebook/admin/gd/ico_pass.png" width=200 height=140 alt="" />
						<p class="title">Пропуск</p>
						<div class="overlay"></div>
						<div class="button"><a href="/phonebook/admin/gd/gd_pass.php?id=<?php echo $id ?>"> СКАЧАТЬ </a></div>					
					</div>
				</div>
				<div class="element-2"></div>
				<div class="element-3"><b>Наклейка электронного пропуска сотрудника.</b></br></br>Файл формата PNG размером 1000 х 700.</br>Рекомендуется к печати размером 70 х 49мм (и в других размерах в соответствующих пропорциях) с последующей ламинацией на клейкой основе и приклейкой на электронные пропуска.</div>
			</div>
			


</div>

<?php

	include 'footer.php';

?>


<?php include 'header.php';?>
<div class='main-body'>
	
<?php 

//echo 'here';

if(isset($_GET['id'])){
	
	$id = (int)$_GET['id'];
	// echo $id;
	
	$res_podrazdelenie =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id=$id"));
	$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE cod_podch = $res_podrazdelenie->id ORDER BY inner_order");
	
	$res_branch =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_branch WHERE id=$res_podrazdelenie->cod_branch"));
	
}
?>
<div class = "block_branch_inner">
<?php
//---------------------- Наполнение бордовой полосы----------------------
echo "<h2 class='name_branch'>$res_branch->name</h2>";
if ($res_branch->id==7) { echo "<h4 class='name_branch'>$res_branch->adress</h4>" ; } //вывод адреса только для АУ
$const_adress_branch=$res_branch->adress; //адрес-константа уровня branch
?>
</div>

		<div class = "block_podrazd_inner">
			<?php 				//---------------------- Наполнение золотой полосы----------------------
				if ($res_branch->id==7) { //для АУ
					echo "<h3>$res_podrazdelenie->name_podrazd</h3>";
					if ($const_adress_branch!=$res_podrazdelenie->adress) { echo "<p class='info_adress'>$res_podrazdelenie->adress<p>" ; } //вывод адреса только если он не равен адерсу-константы1
				} else { //для филиалов
					echo "<h3>$res_podrazdelenie->name_podrazd</h3>";
					echo "<p class='info_adress'>$res_podrazdelenie->adress<p>"; //вывод адреса всегда
				}
				$const_adress_podrazd=$res_podrazdelenie->adress; //адрес-константа уровня podrazd
			?>
		</div>
		<?php while($row_otd = mysqli_fetch_object($res_otd)){ ?>
		
		
			<div class = "block_otd_inner">
				<?php //---------------------- Наполнение синей полосы----------------------
					if ($res_branch->id==7) { //для АУ
						echo "<h4>$row_otd->name_otdel</h4>"; 
						if ($const_adress_branch!=$row_otd->adress) { echo "<p class='info_adress'>$row_otd->adress<p>" ; }; //вывод адреса только если он не равен адерсу-константы1
						
					} else { //для филиалов
						echo "<h4>$row_otd->name_otdel</h4>";
						if ($const_adress_podrazd!=$row_otd->adress) { echo "<p class='info_adress'>$row_otd->adress<p>" ; }; //вывод адреса только если он не равен адерсу-константы2

					}
					if (!empty($row_otd->email) and !empty($row_otd->fax)) 
							{ echo "<p class='info_adress'>e-mail: $row_otd->email, факс: +375($row_otd->phone_cod)$row_otd->fax<p>" ; } //вывод email и fax если они не пустые
						elseif (!empty($row_otd->email) and empty($row_otd->fax)) 
							{ echo "<p class='info_adress'>e-mail: $row_otd->email<p>" ; } //вывод только email если он не пустой, а факс пустой
						elseif (empty($row_otd->email) and !empty($row_otd->fax)) 
							{ echo "<p class='info_adress'>факс: +375($row_otd->phone_cod)$row_otd->fax<p>" ; } ;//вывод только fax если он не пустой, а мыло пустое	
				?>
			</div>

			<?php 
		$res_users = mysqli_query($connect, "SELECT * FROM `users` WHERE spr_cod_otd = $row_otd->id AND is_spr = 1 ORDER BY filter_spr");	
		
		while($row_user = mysqli_fetch_object($res_users)){	?>
			<div class='person_data'>
				<div class='block_link'>
					<a href='person.php?id=<?php echo $row_user->id; ?>' class='person_link'><p class='name'><span class='fam'><?php echo $row_user->fam.' ' ?></span><span class='name'><?php echo $row_user->name.' '?></span><span class='otch'><?php echo $row_user->otch; ?></p></a>
				</div>
				<div class='data_block'>
				<div class='person-photo '>
					<img src='images/users_photo/<?php echo $row_user->photo; ?>' alt='$row_user->fio'>
				</div>
					<div>
					<p><b><?php echo $row_user->dolgnost; ?></b></p>
					
					<p <?php  echo (strlen($row_user->phone)>0 ?  "":"class='none-info'");?>>Телефон (гор.): <a href='tel:<?php echo str_replace('(','',str_replace(')','',$row_user->phone)) ; ?>' class='link_phone link_phone_1'><?php echo (strlen($row_user->phone)>0 ? $row_user->phone : "" );?></a></p>
					
					<p <?php  echo (strlen($row_user->mobile_phone)>0 ?  "":"class='none-info'");?>>Мобильный телефон: <a href='tel:<?php echo str_replace('(','',str_replace(')','',$row_user->mobile_phone));?>' class='link_phone link_phone_2'><?php echo (strlen($row_user->mobile_phone)>0 ?  $row_user->mobile_phone:""); ?> </a></p>
					
					<p <?php  echo (strlen($row_user->ip_phone)>0 ?  "":"class='none-info'");?>>IP телефон: <a href='tel:<?php echo $row_user->ip_phone;?>' class='ip_phone link_phone link_phone_3'><?php echo $row_user->ip_phone ; ?></a></p>
					<p <?php  echo (strlen($row_user->rup_phone)>0 ?  "":"class='none-info'");?>>АТС ГПО "БелЭнерго": <a href='tel:<?php echo $row_user->rup_phone;?>' class='branch_phone link_phone link_phone_4'><?php echo $row_user->rup_phone; ?></a></p>
					<p <?php  echo (strlen($row_user->branch_phone)>0 ?  "":"class='none-info'");?>>Внутренняя АТС: <a href='tel:<?php echo $row_user->branch_phone;?>' class='branch_phone link_phone link_phone_5'><?php echo $row_user->branch_phone; ?></a></p>
					<p <?php  echo (strlen($row_user->email)>0 ?  "":"class='none-info'");?>>e-mail: <a href='mailto:<?php echo $row_user->email;?>' class = 'e-mail'><?php echo $row_user->email; ?></a></p>
					</div>
				</div>
				<div class='add_block_link'><a href='branch-list.php?id=<?php echo $res_branch->id;?>'><?php echo $res_branch->sokr_name; ?></a><a href='department.php?id=<?php echo $res_podrazdelenie->id;?>'><?php echo $res_podrazdelenie->sokr_name; ?></a><a href='district.php?id=<?php echo $row_otd->id; ?>'><?php echo $row_otd->sokr_name; ?></a></div>
			</div>
		
		
		<?php }?>
<?php } ?>
		
</div>




<?php include 'footer.php';?>

<?php include 'header.php';?>
<div class='main-body'>
	
	
	
<?php 

//echo 'here';

if(isset($_GET['id'])){
	
	$id = (int)$_GET['id'];
	// echo $id;
	$res_branch =  mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM spr_branch WHERE id=$id ORDER BY inner_order"));
	
	$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE cod_branch=$res_branch->id ORDER BY inner_order");
	
	
	
	
	
}

?>
<div class = "block_branch_inner">
<?php
//---------------------- Наполнение бордовой полосы----------------------
echo "<h2 class='name_branch'>$res_branch->name</h2>";
?>
</div>
<?php

		While($row_podrazd = mysqli_fetch_object($res_podrazdelenie)){
?>

		<div class = "block_podrazd_inner">
			<?php 
			//---------------------- Наполнение золотой полосы----------------------
				echo "<h3>$row_podrazd->name_podrazd</h3>";
				//echo "<p class='info_adress'>$row_podrazd->adress<p>"; 
			?>
		</div>
		<?php 
		$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE cod_podch = $row_podrazd->id ORDER BY inner_order");
		
		/*while($row_otd = mysqli_fetch_object($res_otd)){ */?>
		
		
			
			
			<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='department.php?id=<?php echo $row_podrazd->id;?>' class='person_link'><p class='name'><?php echo $row_podrazd->name_podrazd; ?> </p></a></div>
			<div class='data_block_otd'>
			
			<div class='person-photo'>
				<img src='images/users_photo/<?php echo  $row_podrazd->photo; ?>' alt='<?php echo $row_podrazd->name; ?>' >
			</div>
				<div  class='adr_otd'>
				<p><b><?php echo $row_podrazd->adress; ?></b></p>
				<p <?php echo (strlen($row_podrazd->phone)>0? "":"class='none-info'"); ?>>Телефон (гор.): <a href='tel:$row_podrazd->phone' class='link_phone link_phone_6'><?php echo (strlen($row_podrazd->phone)>0? "+375($row_podrazd->phone_cod) $row_podrazd->phone":""); ?></a></p>
				<p <?php echo (strlen($row_podrazd->fax)>0? "":"class='none-info'"); ?>>Факс: <a href='tel:$row_podrazd->fax' class='ip_phone link_phone link_phone_7'><?php echo (strlen($row_podrazd->fax)>0? "+375($row_podrazd->phone_cod) $row_podrazd->fax":""); ?></a></p>
				
				<p <?php echo (strlen($row_podrazd->email)>0? "":"class='none-info'"); ?>>e-mail: <a href='mailto:$row_podrazd->email' class = 'e-mail'><?php echo $row_podrazd->email; ?></a></p>
				</div>
			</div><ul class='add_block_link_otd'>
			
			
			<?php while($row_otd = mysqli_fetch_object($res_otd)){?>
				<li><a href='district.php?id=<?php echo $row_otd->id; ?>'><?php echo $row_otd->sokr_name; ?></a></li>
			<?php } ?>
			
			</ul><div class='add_block_link'>
			
			<?php /*while($row_branch = mysqli_fetch_object($res_branch)){ */?>
				<a href='branch-list.php?id=<?php echo $res_branch->id; ?>'><?php echo $res_branch->name; ?></a>
			<?php /*}*/ ?>
			
			</div></div></div>
			
			
			
			
			
			
			
			
			

			<?php 
		/*$res_users = mysqli_query($connect, "SELECT * FROM `users` WHERE spr_cod_otd = $row_otd->id ORDER BY filter_spr");	
		
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
					<p <?php  echo (strlen($row_user->phone)>0 ?  "":"class='none-info'");?>>Телефон (гор.): <a href='tel:<?php echo "+375".$row_otd->phone_cod.$row_user->phone ; ?>' class='link_phone link_phone_1'><?php echo (strlen($row_user->phone)>0 ?  "+375(".$row_otd->phone_cod.") ".$row_user->phone : "" );?></a></p>
					<p <?php  echo (strlen($row_user->mobile_phone)>0 ?  "":"class='none-info'");?>>Мобильный телефон: <a href='tel:<?php echo "+375".$row_user->mobile_phone;?>' class='link_phone link_phone_2'><?php echo (strlen($row_user->mobile_phone)>0 ?  "+375(".substr($row_user->mobile_phone,0,2).") ".substr($row_user->mobile_phone,2):""); ?> </a></p>
					<p <?php  echo (strlen($row_user->ip_phone)>0 ?  "":"class='none-info'");?>>IP телефон: <a href='tel:<?php echo $row_user->ip_phone;?>' class='ip_phone link_phone link_phone_3'> <?php echo $row_user->ip_phone ; ?> </a></p>
					<p <?php  echo (strlen($row_user->rup_phone)>0 ?  "":"class='none-info'");?>>АТС ГПО "БелЭнерго": <a href='tel:<?php echo $row_user->rup_phone;?>' class='branch_phone link_phone link_phone_4'><?php echo $row_user->rup_phone; ?></a></p>
					<p <?php  echo (strlen($row_user->branch_phone)>0 ?  "":"class='none-info'");?>>Внутренняя АТС: <a href='tel:<?php echo $row_user->branch_phone;?>' class='branch_phone link_phone link_phone_5'><?php echo $row_user->branch_phone; ?></a></p>
					<p <?php  echo (strlen($row_user->email)>0 ?  "":"class='none-info'");?>>e-mail: <a href='mailto:<?php echo $row_user->email;?>' class = 'e-mail'><?php echo $row_user->email; ?></a></p>
					</div>
				</div>
				<div class='add_block_link'><a href='branch.php?id=<?php echo $res_branch->id;?>'><?php echo $res_branch->sokr_name; ?></a><a href='department.php?id=<?php echo $row_podrazd->id;?>'><?php echo $row_podrazd->sokr_name; ?></a><a href='district.php?id=<?php echo $row_otd->id; ?>'><?php echo $row_otd->sokr_name; ?></a></div>
			</div>
		
		
		<?php }*/?>
	<?php/* }*/ ?>
<?php } ?>
		
</div>




<?php include 'footer.php';?>
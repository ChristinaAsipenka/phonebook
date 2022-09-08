<?php
include '../settings.php';

//print_r($_POST);

if(isset($_POST['name_spr'])){
	
	$name_spr = trim($_POST['name_spr']);
	
	switch($name_spr){
			case "branch":
				$inner_order = (int)$_POST['inner_order']; 
				$name = trim($_POST['name']);
				$adress = trim($_POST['adress']);
				$phone_cod = trim($_POST['phone_cod']);
				$phone = trim($_POST['phone']);
				$fax = trim($_POST['fax']);
				$email = trim($_POST['email']);
				$photo = trim($_POST['photo']);
				$sokr_name = trim($_POST['sokr_name']);
				$id_branch = (int)$_POST['id'];
				
					mysqli_query($connect, "UPDATE `spr_branch` SET `inner_order` = '$inner_order',`name` = '$name', `adress` = '$adress', `phone_cod` = '$phone_cod', `phone` = '$phone', `fax` = '$fax', `email` = '$email', `photo` = '$photo', `sokr_name` = '$sokr_name' WHERE `id` = '$id_branch'");
				break;
				
				
			case "podrazd":
				$id_podrazd = (int)$_POST['id']; 
				$inner_order = (int)$_POST['inner_order']; 
				$name_podrazd = trim($_POST['name_podrazd']);
				$cod_branch = trim($_POST['cod_branch']);
				$adress = trim($_POST['adress']);
				$phone_cod = trim($_POST['phone_cod']);
				$phone = trim($_POST['phone']);
				$fax = trim($_POST['fax']);
				$email = trim($_POST['email']);
				$photo = trim($_POST['photo']);
				$sokr_name = trim($_POST['sokr_name']);
				
					mysqli_query($connect, "UPDATE `spr_podrazdelenie` SET `inner_order` = '$inner_order',`name_podrazd` = '$name_podrazd', `cod_branch` = '$cod_branch',`adress` = '$adress', `phone_cod` = '$phone_cod', `phone` = '$phone', `fax` = '$fax', `email` = '$email', `photo` = '$photo', `sokr_name` = '$sokr_name' WHERE `id` = '$id_podrazd'");

				break;	
				
			
			
			case "district":
				$id_otdel = (int)$_POST['id']; 
				$inner_order = (int)$_POST['inner_order']; 
				$name_otdel = trim($_POST['name_otdel']);
				$cod_branch = trim($_POST['cod_branch']);
				$cod_podch = trim($_POST['cod_podch']);
				$adress = trim($_POST['adress']);
				$phone_cod = trim($_POST['phone_cod']);
				$phone = trim($_POST['phone']);
				$fax = trim($_POST['fax']);
				$email = trim($_POST['email']);
				$photo = trim($_POST['photo']);
				$sokr_name = trim($_POST['sokr_name']);
				
					mysqli_query($connect, "UPDATE `spr_otdel` SET `inner_order` = '$inner_order',`name_otdel` = '$name_otdel', `cod_branch` = '$cod_branch', `cod_podch` = '$cod_podch',`adress` = '$adress', `phone_cod` = '$phone_cod', `phone` = '$phone', `fax` = '$fax', `email` = '$email', `photo` = '$photo', `sokr_name` = '$sokr_name' WHERE `id` = '$id_otdel'");

				break;	
				
				
				
			case "users":
			
				$id_user = (int)$_POST['id'];
				$filter_spr = (int)$_POST['filter_spr'];
				$fio = trim($_POST['fio']);
				$name = trim($_POST['name']);
				$fam = trim($_POST['fam']);
				$otch = trim($_POST['otch']);
				$login = trim($_POST['login']);
				$password = trim($_POST['password']);
				$tab_num = trim($_POST['tab_num']);
				$dolgnost = trim($_POST['dolgnost']);
				$spr_cod_branch = (int)$_POST['spr_cod_branch'];
				$spr_cod_podrazd = (int)$_POST['spr_cod_podrazd'];
				$spr_cod_otd = (int)$_POST['spr_cod_otd'];
				$email = trim($_POST['email']);
				$photo = trim($_POST['photo']);
				$phone = trim($_POST['phone']);
				$mobile_phone = trim($_POST['mobile_phone']);
				$ip_phone = trim($_POST['ip_phone']);
				$rup_phone = trim($_POST['rup_phone']);
				$branch_phone = trim($_POST['branch_phone']);
				$ip_phone_otd = (int)$_POST['ip_phone_otd'];
				
				


				
					mysqli_query($connect, "UPDATE `users` SET `filter_spr` = '$filter_spr', `fio` = '$fio', `name` = '$name', `fam` = '$fam', `otch` = '$otch', `login` = '$login', `password` = '$password', `tab_num` = '$tab_num', `dolgnost` = '$dolgnost', `spr_cod_branch` = '$spr_cod_branch', `spr_cod_podrazd` = '$spr_cod_podrazd', `spr_cod_otd` = '$spr_cod_otd', `email` = '$email', `photo` = '$photo', `phone` = '$phone', `mobile_phone` = '$mobile_phone', `ip_phone` = '$ip_phone', `rup_phone` = '$rup_phone', `branch_phone` = '$branch_phone', `ip_phone_otd` = '$ip_phone_otd' WHERE `id` = '$id_user'");

				break;	
			
			
			
			case "rules":
			
				print_r($_POST);
				$id = (int)$_POST['id'];
				$id_user = (int)$_POST['id_user'];
				$admin = (int)$_POST['admin'];
				$spr_admin = (int)$_POST['spr_admin'];				
				$arm_prioritet = (int)$_POST['arm_prioritet'];			
				$arm_gruppa = (int)$_POST['arm_gruppa'];					
				$cod_branch = (int)$_POST['cod_branch'];	
				$inc_prioritet = (int)$_POST['inc_prioritet'];
				$access_level = (int)$_POST['access_level'];
				
					mysqli_query($connect, "UPDATE `rules` SET `id_user` = '$id_user', `admin` = '$admin', `spr_admin` = '$spr_admin', `arm_prioritet` = '$arm_prioritet', `arm_gruppa` = '$arm_gruppa', `cod_branch` = '$cod_branch', `inc_prioritet` = '$inc_prioritet', `access_level` = '$access_level' WHERE `id` = '$id'");

				break;

			case "ipgrp":
			
				$id = (int)$_POST['id'];
				$name = trim($_POST['name']);
				$sort = trim($_POST['sort']);
				
				mysqli_query($connect, "UPDATE `ip_group` SET `name` = '$name', `sort` = '$sort' WHERE `id` = '$id'");

				break;

			};
	
}
?>
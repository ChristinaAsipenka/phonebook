<?php
include '../settings.php';

print_r($_POST);

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
				
					mysqli_query($connect, "INSERT INTO `spr_branch` (`inner_order`, `name`, `adress`, `phone_cod`, `phone`, `fax`, `email`, `photo`, `sokr_name`) VALUES  ('$inner_order', '$name', '$adress', '$phone_cod', '$phone', '$fax', '$email', '$photo', '$sokr_name')");
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
				
					mysqli_query($connect, "INSERT INTO `spr_podrazdelenie` (`inner_order`,`name_podrazd`, `cod_branch`, `adress`, `phone_cod`, `phone`, `fax`, `email`, `photo`, `sokr_name`) VALUES  ('$inner_order', '$name_podrazd', '$cod_branch', '$adress', '$phone_cod', '$phone', '$fax', '$email', '$photo', '$sokr_name')");

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
				
					mysqli_query($connect, "INSERT INTO `spr_otdel` (`inner_order`, `name_otdel`, `cod_branch`, `cod_podch`, `adress`, `phone_cod`, `phone`, `fax`, `email`, `photo`, `sokr_name`) VALUES ('$inner_order', '$name_otdel', '$cod_branch', '$cod_podch', '$adress', '$phone_cod', '$phone', '$fax', '$email', '$photo', '$sokr_name')");

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
				/*$prioritet = (int)$_POST['prioritet'];*/
				$spr_cod_branch = (int)$_POST['spr_cod_branch'];
				$spr_cod_podrazd = (int)$_POST['spr_cod_podrazd'];
				$spr_cod_otd = (int)$_POST['spr_cod_otd'];
				/*$podrazdelenie = (int)$_POST['podrazdelenie'];*/
				$email = trim($_POST['email']);
				$photo = trim($_POST['photo']);
				$phone = trim($_POST['phone']);
				$mobile_phone = trim($_POST['mobile_phone']);
				$ip_phone = trim($_POST['ip_phone']);
				$rup_phone = trim($_POST['rup_phone']);
				$branch_phone = trim($_POST['branch_phone']);
				$ip_phone_otd = (int)$_POST['ip_phone_otd'];
				
				


				
					mysqli_query($connect, "INSERT INTO `users` (`filter_spr`, `fio`, `name`, `fam`, `otch`, `login`, `password`, `tab_num`, `dolgnost`, `spr_cod_branch`, `spr_cod_podrazd`, `spr_cod_otd`, `email`, `photo`, `phone`, `mobile_phone`, `ip_phone`, `rup_phone`, `branch_phone`, `is_spr`, `ip_phone_otd`) VALUES ('$filter_spr', '$fio', '$name', '$fam', '$otch', '$login', '$password', '$tab_num', '$dolgnost', '$spr_cod_branch', '$spr_cod_podrazd', '$spr_cod_otd', '$email', '$photo', '$phone', '$mobile_phone', '$ip_phone', '$rup_phone', '$branch_phone', '1', '$ip_phone_otd')");
					
					$id_user = mysqli_fetch_array(mysqli_query($connect, "SELECT MAX(`id`) FROM `users`"));
					
				mysqli_query($connect, "INSERT INTO `rules` (`id_user`, `cod_branch`, `access_level`) VALUES ('$id_user[0]','$spr_cod_branch', '6')");
				//print_r($id_user);
				break;	

			case "rules":

				$id_user = (int)$_POST['id_user'];
				$admin = (int)$_POST['admin'];
				$spr_admin = (int)$_POST['spr_admin'];				
				$arm_prioritet = (int)$_POST['arm_prioritet'];			
				$arm_gruppa = (int)$_POST['arm_gruppa'];					
				$cod_branch = (int)$_POST['cod_branch'];	
				$inc_prioritet = (int)$_POST['inc_prioritet'];
				$access_level = (int)$_POST['access_level'];
				
					mysqli_query($connect, "INSERT INTO `rules` (`id_user`, `admin`, `spr_admin`, `arm_prioritet`, `arm_gruppa`, `cod_branch`, `inc_prioritet`, `access_level`) VALUES ('$id_user', '$admin', '$spr_admin', '$arm_prioritet', '$arm_gruppa', '$cod_branch', '$inc_prioritet', '$access_level')");

				break;
			
			case "ipgrp":
			
			
				$id = (int)$_POST['id'];
				$name = trim($_POST['name']);
				$sort = trim($_POST['sort']);
				
				mysqli_query($connect, "INSERT INTO `ip_group` (`name`, `sort`) VALUES ('$name', '$sort')");

				break;	

			};
	
}
?>
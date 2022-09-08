<?php
include '../settings.php';
include 'fields_name.php';
session_start();
//$connect = mysqli_connect('localhost','root2','admin','main');

//print_r($_GET);

if(isset($_SESSION['user'])){

		if(isset($_GET['name_spr'])){
	
			$name_spr = trim($_GET['name_spr']);
			
			
			$user_cod_branch = $_SESSION['user']['spr_cod_branch'];
			
			$str_query = '';
			
			
			if($_SESSION['user']['spr_admin'] == 1 && $_SESSION['user']['admin'] == 0){
				
				//если справочник users то условие по полю spr_cod_branch
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0) ? (strcmp(trim($name_spr),"fired") == 0 ? " where users.is_spr = '0' and users.spr_cod_branch = $user_cod_branch" : " where is_spr = '1' and spr_cod_branch = $user_cod_branch")  : " where cod_branch = $user_cod_branch" );
			}else if(($_SESSION['user']['admin'] == 1 && $_SESSION['user']['spr_admin'] == 1) or ($_SESSION['user']['admin'] == 1 && $_SESSION['user']['spr_admin'] == 0)){
				
				//если справочник users то условие по полю spr_cod_branch
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0) ? (strcmp(trim($name_spr),"fired") == 0 ? " where users.is_spr = '0'" : " where is_spr = '1'")  : "" );
			}
	
			$work_table = "";
			$name_table_spr = "";
	switch($name_spr){
			case "branch":
				$res_query = mysqli_query($connect, "SELECT id, name, adress, email, inner_order FROM spr_branch");
				$name_table_spr = "Справочник филиалов";
				break;
			case "podrazd":
				$res_query = mysqli_query($connect, "SELECT id, name_podrazd, cod_branch, adress, email, inner_order  FROM spr_podrazdelenie ".$str_query." ORDER BY cod_branch");
				$name_table_spr = "Справочник подразделений";
				break;	
			case "district":
				$res_query = mysqli_query($connect, "SELECT id, name_otdel, cod_podch, cod_branch, adress, email, cod_branch, cod_podch, inner_order FROM spr_otdel ".$str_query." ORDER BY cod_branch");
				$name_table_spr = "Справочник отделов";
				break;
			case "users":
				$res_query = mysqli_query($connect, "SELECT id, fio, dolgnost, spr_cod_otd, spr_cod_podrazd, spr_cod_branch, filter_spr FROM users ".$str_query." ORDER BY spr_cod_podrazd, spr_cod_otd, filter_spr");
				$name_table_spr = "Справочник сотрудников";
				break;
			case "rules":
				$res_query = mysqli_query($connect, "SELECT * FROM rules".$str_query);
				$name_table_spr = "Права пользователей";
				break;
			case "fired":
		
				
				$res_query = mysqli_query($connect, "SELECT users.id, users.fio, users.dolgnost, users.spr_cod_otd, users.spr_cod_podrazd, users.spr_cod_branch, users.filter_spr FROM users ".$str_query." GROUP BY users.fam ORDER BY users.date_unreg DESC, users.spr_cod_podrazd, users.spr_cod_otd, users.filter_spr ");
	
				$name_table_spr = "Уволенные сотрудники";
				break;
			case "ipgrp":
		
				
				$res_query = mysqli_query($connect, "SELECT * FROM ip_group ".$str_query." ");
	
				$name_table_spr = "Группы IP-телефонии (не редактировать)";
				break;	
			};
		
	//	echo mysqli_num_fields($res_query);	
		//echo mysqli_list_fields($res_query);
		//$res_fields = array_keys($res_query);
			
			$fields_array = array();
			$num = 0;
			$work_table = "<script src='js/table.js'></script><div class='name_table_spr'>$name_table_spr</div><button class='new_element_spr'>Новая запись</button><div class='search-modal'><input type='text' class='form-control pull-right' id='search' placeholder='Поиск по таблице'>
		</div><table class='spr_table' name_spr='$name_spr'><thead><tr>";
			
			foreach(mysqli_fetch_object($res_query) as $key =>$element ){
				
				/*switch($key){
					case "potr_id":
					case "tepl_id":
						
					break;
					default:
						$work_table .= "<th>".fields_name($key, $name_spr, 'spr')."</th>";
					}*/
				
				$work_table .= "<th>".fields_name($key, $name_spr, 'spr')."</th>";
				
				$fields_array[$num++] = $key;
			}
			
			$work_table .= "</tr></thead>";
			
		mysqli_data_seek($res_query, 0);

			
			while($row_query = mysqli_fetch_object($res_query)){
			
				//$work_table .= "<tr id='$row_query->id' ".(strcmp($name_spr,'fired') ==0 ?(is_null($row_query->potr_id) && is_null($row_query->tepl_id)? "class=''" : "class='dont_delete'" ) : "class=''")." >";
				$work_table .= "<tr id='$row_query->id' class='' >";
				
					foreach($fields_array as $key =>$element ){
						
						
						switch($element){
							case "photo":
								$work_table .= "<td><div class='person-photo'><img src='../phonebook/images/users_photo/".$row_query->$element."'></div></td>";
							break;	
						case "cod_branch":
						case "spr_cod_branch":
							$row_branch =mysqli_fetch_object( mysqli_query($connect, "SELECT id, sokr_name FROM spr_branch WHERE id ='".$row_query->$element."'"));
							$work_table .= "<td>".$row_branch->sokr_name."</td>";
							break;
						case "cod_podch":
						case "spr_cod_podrazd":
							$row_podch =mysqli_fetch_object( mysqli_query($connect, "SELECT id, sokr_name FROM spr_podrazdelenie WHERE id ='".$row_query->$element."'"));					
							$work_table .= "<td>".$row_podch->sokr_name."</td>";
							break;
						case "spr_cod_otd":
							$row_otd =mysqli_fetch_object( mysqli_query($connect, "SELECT id, sokr_name FROM spr_otdel WHERE id ='".$row_query->$element."'"));
							$work_table .= "<td>".(isset($row_otd->sokr_name) ? $row_otd->sokr_name : '')."</td>";
							break;
						case "id_user":
							$row_user =mysqli_fetch_object( mysqli_query($connect, "SELECT id, fio FROM users WHERE id ='".$row_query->id_user."'"));
							$work_table .= "<td>".(isset($row_user->fio) ? $row_user->fio : "")."</td>";
							break;
						case "admin":
						case "spr_admin":
							$work_table .= "<td>".($row_query ->$element == 1 ? '&#10003' : '')."</td>";
							break;	
						case "arm_prioritet":
							$work_table .= "<td>".($row_query ->$element == 1 ? 'Инженер' : ($row_query ->$element == 2 ? 'Руководитель группы' : ($row_query ->$element == 3 ? 'Инспектор' : '')))."</td>";
							break;
						case "inc_prioritet":
							$work_table .= "<td>".($row_query ->$element == 1 ? 'Администратор' : ($row_query ->$element == 2 ? 'Руководство' : ($row_query ->$element == 3 ? 'Инженер ОблЭГИ' : ($row_query ->$element == 4 ? 'Инженер МрО' : ($row_query ->$element == 5 ? 'Инспектор' : '')))))."</td>";
							break;							
						case "arm_gruppa":
							$work_table .= "<td>".($row_query ->$element == 1 ? 'Тепло' : ($row_query ->$element == 2 ? 'Газ' : ($row_query ->$element == 3 ? 'Электро' : '')))."</td>";
							break;	
						case "potr_id":
						case "tepl_id":
							/*$row_user =mysqli_fetch_object( mysqli_query($connect, "SELECT id, fio FROM users WHERE id ='".$row_query->id_user."'"));*/
							/*$work_table .= "<td>".($row_query ->$element == 1 ? 'Тепло' : ($row_query ->$element == 2 ? 'Газ' : ($row_query ->$element == 3 ? 'Электро' : '')))."</td>";*/
							break;		
						default:
							$work_table .= "<td>".$row_query->$element."</td>";
						}
						
						
					}
			
				
				$work_table .= "</tr>";
				
			}
			
			
		
			$work_table .= "</table>";
			
			echo $work_table;	
		}
	}










?>
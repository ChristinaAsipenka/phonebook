<?php
include '../settings.php';
include 'fields_name.php';
session_start();
//$connect = mysqli_connect('localhost','root2','admin','main');


if(isset($_SESSION['user'])){
		
		if(isset($_POST['name_spr'])){
	
			$name_spr = trim($_POST['name_spr']);
			$user_cod_branch = $_SESSION['user']['spr_cod_branch'];
		
			$str_query = '';
			
			if(isset($_POST['id'])){
				$id = (int)$_POST['id'];
				$str_query .=" where id = $id";
				
			}
			
			if(isset($_POST['user_delete'])){
				$user_del = (int)$_POST['user_delete'];
			}
			
			if(isset($_POST['id']) AND ($_SESSION['user']['admin'] == 0 and $_SESSION['user']['spr_admin'] == 1)){
				// права доступа для администратора филиала - видит только свой филиал
				//если справочник users то условие по полю spr_cod_branch
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0) ? " AND spr_cod_branch = $user_cod_branch" : " AND cod_branch = $user_cod_branch" );
			}elseif(($_SESSION['user']['spr_admin'] == 1 and $_SESSION['user']['admin'] == 0) AND !isset($_POST['id'])){
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0 ) ? " where spr_cod_branch = $user_cod_branch" : " where cod_branch = $user_cod_branch" );
			}elseif(isset($_POST['id']) AND (($_SESSION['user']['admin'] == 1 and $_SESSION['user']['spr_admin'] == 1) or ($_SESSION['user']['admin'] == 1 and $_SESSION['user']['spr_admin'] == 0))){
				// права доступа для супер администратора - видит всё
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0) ? "" : "" );
			}elseif(!isset($_POST['id']) AND (($_SESSION['user']['admin'] == 1 and $_SESSION['user']['spr_admin'] == 1) or ($_SESSION['user']['admin'] == 1 and $_SESSION['user']['spr_admin'] == 0))){
				$str_query .=((strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0 ) ? " where spr_cod_branch = $user_cod_branch" : " where cod_branch = $user_cod_branch" );
			}
			
			$work_form = "";
			$btn_fire = "";
			$btn_del = "";
			
			switch($name_spr){
				case "branch":
						//echo "SELECT photo, id, name, sokr_name, adress, phone_cod, phone, fax, email, inner_order FROM spr_branch".$str_query;
						$res_query = mysqli_query($connect, "SELECT photo, id, name, sokr_name, adress, phone_cod, phone, fax, email, inner_order FROM spr_branch");
				//	print_r($res_query);
					break;
				case "podrazd":
						$res_query = mysqli_query($connect, "SELECT photo, id, cod_branch, name_podrazd, sokr_name, adress, phone_cod, phone, fax, email, inner_order  FROM spr_podrazdelenie".$str_query);
					break;	
				case "district":
						$res_query = mysqli_query($connect, "SELECT photo, id, cod_branch, cod_podch, name_otdel, sokr_name, adress, phone_cod, phone, fax, email, inner_order FROM spr_otdel".$str_query);
					break;
				case "users":
						$res_query = mysqli_query($connect, "SELECT  photo, id, spr_cod_branch, spr_cod_podrazd, spr_cod_otd,  fam, name, otch, fio, login, tab_num, password, dolgnost, email, phone, mobile_phone, ip_phone, ip_phone_otd, rup_phone, branch_phone, filter_spr FROM users ".$str_query." ORDER BY spr_cod_podrazd, spr_cod_otd, filter_spr ");
					$btn_fire ="<div class='btn_fire'><button>Уволить</button></div>";	
					break;
				case "fired":
						$res_query = mysqli_query($connect, "SELECT  photo, id, spr_cod_branch, spr_cod_podrazd, spr_cod_otd,  fam, name, otch, fio, login, tab_num, password, dolgnost, email, phone, mobile_phone, ip_phone, ip_phone_otd, rup_phone, branch_phone, filter_spr FROM users ".$str_query." ORDER BY spr_cod_podrazd, spr_cod_otd, filter_spr ");
					$btn_fire ="<div class='btn_restore'><button>Востановить</button></div>";
					// удаление закрыли по какой-то причине, которую не можем вспомнить (16.03.2022)
					$btn_del ="<div class='btn_del'><button ".($user_del == 1? "disabled = 'disabled'" : "")." onclick='delete_user(".(isset($id) ? $id : '' ).")' disabled>Удалить запись</button></div>";
					break;	
				case "rules":
					$res_query = mysqli_query($connect, "SELECT * FROM rules".$str_query);
					break;
				case "ipgrp":

					$res_query = mysqli_query($connect, "SELECT * FROM ip_group ");
					break;	
			};
			
			//	echo "SELECT * FROM rules".$str_query;
			$fields_array = array();
			$num = 0;
			$work_form = "<div class='' name_spr='$name_spr'><script src='js/ajaxupload.js'></script><script src='js/login-script.js'></script><script src='js/js-modal.js'></script>";
			$row_query	= mysqli_fetch_object($res_query);
			
			
			if(strcmp(trim($name_spr),"rules") != 0 && strcmp(trim($name_spr),"ipgrp") != 0){
			$work_form .= "<div class=''><div class='image-preview'><img id='preview' src='../../phonebook/images/users_photo/".(isset($id) ? $row_query ->photo : 'no-photo.png')."' alt=''></div>
							<form id='upload-image' enctype='multipart/form-data' name='uploadimage'>
								<div class='form-group'><input type='file' name='image' id='image'><label for='image'></label></div>
								<!--input type='submit' class='' value='Загрузить'-->
								<span class='warning_img'>(Размер файла не должен превышать 2 Мб)</span>
							</form>
							<div id='result'></div>
						</div>";
			}
			$work_form .= "<form class='form' id='main_modal_form' mode ='".(isset($id) ? 'edit' : 'new')."'>";
			$work_form .= "<input class='select_modal' name='name_spr' value='".$name_spr."' style='display:none'>";
			
		//	print_r($row_query);
			foreach($row_query as $key =>$element ){
				
				switch($key){
					case "spr_cod_branch":
						$res_query_edit = mysqli_query($connect, "SELECT * FROM spr_branch");
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
						$work_form .= "<option value = '0' selected>Выберите филиал</option>";
								while($row_query_edit = mysqli_fetch_object($res_query_edit)){
								if($row_query->spr_cod_branch == $row_query_edit->id && isset($id)){	
									$work_form .= "<option value = '".$row_query_edit ->id."' selected>".$row_query_edit ->name."</option>";
								}else{
									$work_form .= "<option value = '".$row_query_edit ->id."'>".$row_query_edit ->name."</option>";	
								}
							}
						$work_form .= "</select></div>";
						break;
					case "cod_branch":
						$res_query_edit = mysqli_query($connect, "SELECT * FROM spr_branch");
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
						$work_form .= "<option value = '0' selected>Выберите филиал</option>";
								while($row_query_edit = mysqli_fetch_object($res_query_edit)){
								if($row_query->cod_branch == $row_query_edit->id && isset($id)){	
									$work_form .= "<option value = '".$row_query_edit ->id."' selected>".$row_query_edit ->name."</option>";
								}else{
									$work_form .= "<option value = '".$row_query_edit ->id."'>".$row_query_edit ->name."</option>";	
								}
							}
						$work_form .= "</select></div>";
					break;
					case "arm_prioritet":
					case "prioritet":
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
									$work_form .= "<option value = '0' ".($row_query ->$key==0 && !isset($id)?'selected':'').">Нет прав</option>";	
									$work_form .= "<option value = '1' ".($row_query ->$key==1 && isset($id)?'selected':'').">Инженер</option>";
									$work_form .= "<option value = '2' ".($row_query ->$key==2 && isset($id)?'selected':'').">Руководитель группы</option>";
									$work_form .= "<option value = '3' ".($row_query ->$key==3 && isset($id)?'selected':'').">Инспектор энергогазинспекции</option>";

							
						$work_form .= "</select></div>";
					break;
					
					
					case "inc_prioritet":
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
									$work_form .= "<option value = '0' ".($row_query ->$key==0 && !isset($id)?'selected':'').">Нет прав</option>";	
									$work_form .= "<option value = '1' ".($row_query ->$key==1 && isset($id)?'selected':'').">Администратор</option>";
									$work_form .= "<option value = '2' ".($row_query ->$key==2 && isset($id)?'selected':'').">Руководство</option>";
									$work_form .= "<option value = '3' ".($row_query ->$key==3 && isset($id)?'selected':'').">Инженер ОблЭГИ</option>";
									$work_form .= "<option value = '4' ".($row_query ->$key==4 && isset($id)?'selected':'').">Инженер МрО</option>";
									$work_form .= "<option value = '5' ".($row_query ->$key==5 && isset($id)?'selected':'').">Инспектор энергогазинспекции</option>";

							
						$work_form .= "</select></div>";
					break;
					
					
					
					case "arm_gruppa":
					case "podrazdelenie":
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
									$work_form .= "<option value = '0' ".($row_query ->$key==0 && !isset($id)?'selected':'').">не относится</option>";	
									$work_form .= "<option value = '1' ".($row_query ->$key==1 && isset($id)?'selected':'').">тепло</option>";
									$work_form .= "<option value = '2' ".($row_query ->$key==2 && isset($id)?'selected':'').">газ</option>";
									$work_form .= "<option value = '3' ".($row_query ->$key==3 && isset($id)?'selected':'').">электро</option>";

							
						$work_form .= "</select></div>";
					break;
					
					
					case "spr_cod_podrazd":
					case "cod_podch":

						$res_query_edit = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie");

						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
						$work_form .= "<option value = '0' selected>Выберите подразделение</option>";
								while($row_query_edit = mysqli_fetch_object($res_query_edit)){
								if($row_query->$key == $row_query_edit->id && isset($id)){	
									$work_form .= "<option value = '".$row_query_edit ->id."' selected cod_branch='".$row_query_edit ->cod_branch."'>".$row_query_edit ->sokr_name."</option>";
								}else{
									$work_form .= "<option value = '".$row_query_edit ->id."' cod_branch='".$row_query_edit ->cod_branch."'>".$row_query_edit ->sokr_name."</option>";	
								}
							}
						$work_form .= "</select></div>";
					break;
					
					
					case "spr_cod_otd":
						$res_query_edit = mysqli_query($connect, "SELECT * FROM spr_otdel");
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
						$work_form .= "<option value = '0' selected>Выберите отдел</option>";
								while($row_query_edit = mysqli_fetch_object($res_query_edit)){
								if($row_query->$key == $row_query_edit->id && isset($id)){	
									$work_form .= "<option value = '".$row_query_edit ->id."'cod_podch='".$row_query_edit ->cod_podch."' selected>".$row_query_edit ->sokr_name."</option>";
								}else{
									$work_form .= "<option value = '".$row_query_edit ->id."' cod_podch='".$row_query_edit ->cod_podch."'>".$row_query_edit ->sokr_name."</option>";	
								}
							}
						$work_form .= "</select></div>";
					break;
					
					
					case "ip_phone_otd":
						$res_query_edit = mysqli_query($connect, "SELECT * FROM ip_group");
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " value='".$row_query ->$key."'>";
						$work_form .= "<option value = '0' selected>Выберите группу</option>";
								while($row_query_edit = mysqli_fetch_object($res_query_edit)){
								if($row_query->$key == $row_query_edit->id && isset($id)){	
									$work_form .= "<option value = '".$row_query_edit ->id."'cod_group='".$row_query_edit ->id."' selected>".$row_query_edit ->name."</option>";
								}else{
									$work_form .= "<option value = '".$row_query_edit ->id."' cod_group='".$row_query_edit ->id."'>".$row_query_edit ->name."</option>";	
								}
							}
						$work_form .= "</select></div>";
					break;
					
					
					
					
					case "photo":
							$work_form .= $btn_fire;
							$work_form .= $btn_del;
							$work_form .= "<div class='modal_line'><input class='select_modal' name=".$key. " value='".(isset($id) ? $row_query ->photo : 'no-photo.png')."' style='display:none'></div>";
						
					break;
					case "admin":
					// права супер админа может назначать только витебский филиал и аппарат управления
							if($user_cod_branch == 2 || $user_cod_branch == 7){ 
								$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='checkbox' class='check_modal' name=".$key. " value='' ".($row_query ->$key == 1 && isset($id) ? 'checked' : '')."></div>";
							}
					break;		
					case "spr_admin":
						
						
							$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='checkbox' class='check_modal' name=".$key. " value='' ".($row_query ->$key == 1 && isset($id) ? 'checked' : '')."></div>";
						
					break;
					
					
					case "mobile_phone":
					$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='text' name=".$key. " id='mobile_ph' class='select_modal' value='".(isset($id) ? $row_query ->$key : '')."'></div>";
				
					break;
					case "phone":
					$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='text' id='ph' name=".$key. " class='select_modal' value='".(isset($id) ? $row_query ->$key : '')."'></div>";
					
					break;
					case "fax":
					$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='text' id='fax' name=".$key. " class='select_modal' value='".(isset($id) ? $row_query ->$key : '')."'></div>";
				
					break;
					
					
					case "id":
						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input class='select_modal' name=".$key. " value='".(isset($id) ? $row_query ->$key : '')."' disabled></div>";
					break;
					case "id_user":
						if(isset($id )){
							$id_user_tmp = $row_query ->id_user;
							$row_user = mysqli_fetch_object(mysqli_query($connect, "SELECT id, fio FROM users where id = $id_user_tmp"));
						}

						$work_form .= "<div class='modal_line login-block'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input type='text' class='select_modal who'  value='".(isset($row_user) ? $row_user ->fio : '')."' autocomplete='off' ><input class='select_modal who_id' name=".$key. " value='".(isset($id_user_tmp ) ? $row_query ->id_user : '')."' style='display:none'><ul class='search_result'></ul></div>";
					break;
					case "access_level":
						$i = 1;

						$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><select class='select_modal' name=".$key. " >";
						$work_form .= "<option value = '0' selected>Выберите уровень</option>";
								while($i <= 6){
									
									$work_form .= "<option value = '".$i."' ".( isset($id) ? ($row_query ->$key == $i ? 'selected="selected"' : '') : ($i == 6 ? 'selected="selected"' : '') )." >".$i."</option>";
																
								$i++;
							}
						$work_form .= "</select>
						<p class='ps'>1 - инспектор; 2 - начальник группы; 3 - начальник/зам. начальника МРО; 4 - инспекция областная; 5 - аппарат управления; 6 - нет прав</p>
						</div>";
					break;
					default:
							$work_form .= "<div class='modal_line'><label class='modal_label'>".fields_name($key, $name_spr, 'modal')."</label><input class='select_modal' name=".$key." value='".(isset($id) ? $row_query ->$key : '')."'></div>";
						
				}
				

				$fields_array[$num++] = $key;
			}
		
			
			
			
		
			$work_form .= "<div><p id='messenger'></p><input type='submit' value='Сохранить'  class='submit admin_submit'></div></form></div>";
			
			echo $work_form;	
		}
	}



?>
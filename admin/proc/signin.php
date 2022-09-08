<?php include '../settings.php';?>
<?php
//$connect = mysqli_connect('localhost','root2','admin','main');

session_start();
if(isset($_SESSION['user'])){

	header("Location: ../login.php");
	$_SESSION['message'] =$_SESSION['user']['name'].' ' .$_SESSION['user']['otch'].', <br/>Вы уже авторизованы )))</br><a href="../../admin/administrator.php">Администрирование</a>';	
}else{
$login = $_POST['login'];
$password = $_POST['password'];
$work_dir = trim($_POST['work_dir']);

$check_user = mysqli_query($connect, "SELECT * FROM users WHERE login = '$login' AND password = '$password'");

if (mysqli_num_rows($check_user)>0){

	$user = mysqli_fetch_assoc($check_user);
	
	$user_id = $user['id'];
	$query_rules = mysqli_query($connect, "SELECT * FROM rules WHERE id_user = $user_id ");
	$rules = mysqli_fetch_assoc($query_rules);
	

	
	/*if($rules['spr_admin']==1){*/
		$_SESSION['user'] = [
			"id_user" => $user['id'],
			"login" => $user['login'],
			"password" => $user['password'],
			"name" => $user['name'],
			"fam" => $user['fam'],
			"otch" => $user['otch'],
			"cod_otd" => $user['spr_cod_podrazd'],
			"cod_uch" => $user['spr_cod_otd'],
			//"prioritet" => $user['prioritet'],
			"podrazdelenie" => $user['podrazdelenie'],
			"admin" => $rules['admin'],
			"spr_admin" => $rules['spr_admin'],
			"arm_prioritet" => $rules['arm_prioritet'],
			"inc_prioritet" => $rules['inc_prioritet'],
			"arm_gruppa" => $rules['arm_gruppa'],
			"spr_cod_branch" => $user['spr_cod_branch']
		];
		$_SESSION['is_filter'] = 0;
		
		// приоритет для фильтра
		if($rules['arm_prioritet'] == 2){
			$_SESSION['arm_prioritet'] = 3;	
		}else{
			$_SESSION['arm_prioritet'] = 0;	
		}
			
			
		if(strcmp($work_dir,'arm') ==0 ){
			header("Location: ../../arm/main.php");
		}else if(strcmp($work_dir,'phonebook') ==0 ){	
			header("Location: ../../admin/administrator.php");
		}else if(strcmp($work_dir,'inc') ==0 ){
			header("Location: ../../incidents/main.php");
		}else{	
			header("Location: ../../admin/administrator.php");
		}

}else{
	$_SESSION['message'] = 'Неверный логин или пароль';
	header("Location: ../login.php?work_dir=$work_dir");

}


}
?>
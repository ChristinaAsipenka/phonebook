<?php include '../settings.php';?>
<?php
session_start();

if(isset($_SESSION['user'])){
	
$id_restore = (int)$_POST['user_id'];


mysqli_query($connect, "UPDATE `users` SET  `is_spr` = 1 WHERE `id` = '$id_restore'");

}	
mysqli_close($connect);


?>
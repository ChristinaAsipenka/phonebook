<?php include '../settings.php';?>
<?php
session_start();

if(isset($_SESSION['user'])){
	
$id_fire = (int)$_POST['user_id'];


mysqli_query($connect, "UPDATE `users` SET  `is_spr` = 0, `date_unreg` = CURRENT_TIMESTAMP(3) WHERE `id` = '$id_fire'");

}	
mysqli_close($connect);


?>
<?php include '../settings.php';?>
<?php

session_start();


if(isset($_SESSION['user'])){

$id_delete = (int)$_GET['user_id'];
mysqli_query($connect, "DELETE FROM users WHERE id = $id_delete");
mysqli_query($connect, "DELETE FROM rules WHERE id_user = $id_delete");
}

mysqli_close($connect);


?>
<?php include '../settings.php';


$data_str='';

if(isset($_POST['referal'])){
 $referal = trim($_POST['referal']);
 //$podr = trim($_POST['podr']);
 
  //  $db_referal = mysqli_query($connect, "SELECT * from `users` WHERE fio LIKE '%$referal%' AND podrazdelenie = '$podr'");
    $db_referal = mysqli_query($connect, "SELECT * from `users` WHERE fio LIKE '%$referal%' AND is_spr = 1");

    while ($row = mysqli_fetch_object($db_referal)) {
    //   $data_str= trim($data_str) + "<li>".$row->fio."</li>";
	   echo "<li id_user = ".$row->id." user_cod_branch = ".$row->spr_cod_branch.">".$row->fio."</li>";
    }
	//echo $data_str;
}else{
	echo $referal;
}

mysqli_close($connect);
?>
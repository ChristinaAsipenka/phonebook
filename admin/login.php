
<?php include 'header.php';
//session_start();
?>
<div class='main-body'>

	<div class="container-log">
		<div class="container-header">
			<h3>Авторизация</h3>
		</div>
		
		<form action="proc/signin.php" method="post">
		
			<div class='login-block'>
				<input type="text" name="login" placeholder="Логин" class="who" autocomplete="off">
				<ul class='search_result'></ul>
			</div>
			<input type="password" name="password" placeholder="Пароль">
			<input type="hidden" name="work_dir" value=<?php echo (isset($_GET['work_dir']) ? $_GET['work_dir'] : '' );?>>
			<input type="submit" name="submit" class="container-submit" value="Войти">
		
			
		</form>
		
	</div>
	<?php
				if(isset($_SESSION['message'])){
					echo '<div class="msg"><p>' . $_SESSION['message'] . '</p></div>';
					unset($_SESSION['message']);
				}
			?>

</div>


<?php include 'footer.php';?>
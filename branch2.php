<?php include 'header.php';?>
	<div class='main-body'>
	<!--------------------------------------------------------------------------------------------------------->
<?php

$res_users = mysqli_query($connect, "SELECT users.id, users.filter_spr, users.password, users.name, users.fam, users.otch, users.dolgnost, users.spr_cod_branch, users.spr_cod_otd, users.spr_cod_podrazd, users.photo, users.email, users.phone, users.mobile_phone, users.ip_phone, users.rup_phone, users.branch_phone, users.is_spr, spr_otdel.name_otdel, spr_otdel.cod_podch, spr_podrazdelenie.name_podrazd FROM `users` LEFT JOIN `spr_otdel` AS spr_otdel on users.spr_cod_otd=spr_otdel.id LEFT JOIN `spr_podrazdelenie` as spr_podrazdelenie on users.spr_cod_podrazd=spr_podrazdelenie.id where is_spr=1 ORDER BY spr_cod_branch, spr_cod_podrazd, spr_cod_otd, filter_spr");


	//print_r($array_otd);
?>	
<div class='block1'>

<?php
$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie");
$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel");
	while($row_podrazd = mysqli_fetch_object($res_podrazdelenie)){
		
	echo "<div id='podrazd$row_podrazd->id' class='block_podrazd'><div class='block_podrazd_inner'><h2>$row_podrazd->name_podrazd</h2>";
		
		if(isset($row_podrazd->adress)){
			echo "<p>$row_podrazd->adress</p>";
			echo "<p>Код: $row_podrazd->phone_cod  Тел/факс: $row_podrazd->fax</p>";
		
		}
		echo "</div>";

	
	while($row_otd = mysqli_fetch_object($res_otd)){
		if($row_otd->cod_podch == $row_podrazd->id){
		echo "<div id='otd$row_otd->id' class='block_otd'><div class='block_otd_inner'><h3>$row_otd->name_otdel</h3>";
		if(isset($row_otd->adress)){
			echo "<p>$row_otd->adress  Код: $row_otd->phone_cod  Тел/факс: $row_otd->fax</p>";
		
		
		}
			echo "</div>";
?>


<?php 
	while($row_user = mysqli_fetch_object($res_users))
{		

?>
	<?php if($row_user->spr_cod_podrazd == $row_podrazd->id && $row_user->spr_cod_otd == $row_otd->id ){?>
	<div class='person'  cod_user='<?php echo $row_user->id; ?>' person_cod_branch='<?php echo $row_user->spr_cod_branch;?>' person_cod_podrazd='<?php echo $row_user->spr_cod_podrazd;?>' person_cod_otd='<?php echo $row_user->spr_cod_otd;?>'>
			<div class='person-photo'>
				<img src="images/users_photo/<?php echo $row_user->photo;?>" alt="<?php echo $row_user->fio; ?>">
			</div>
			<div class='person-data'>
				<div class='person-data-part1'>
					<p class='pass' style='display:none'><?php echo $row_user->password; ?></p>
					<p class='cod_branch' style='display:none'><?php echo $row_user->spr_cod_branch; ?></p>
					<p class='name'><span class='fam'><?php echo $row_user->fam; ?></span><br/><span class='name'><?php echo $row_user->name.' '?></span><span class='otch'><?php echo $row_user->otch; ?></p>
					<p class='position'><?php echo $row_user->dolgnost; ?></p>
				
						<?php echo "<p class='distric' cod_otd = $row_user->spr_cod_otd> $row_user->name_otdel</p>";
					
						echo "<p class='otd' cod_podr=$row_user->spr_cod_podrazd> $row_user->name_podrazd</p>"
							?>
					
					<p  class='email'>e-mail:<a href='mailto:<?php echo $row_user->email; ?>' class = "e-mail"><?php echo $row_user->email; ?></a></p>
				</div>
				<div class='person-data-part2'>
					<p class='phone1'>Городской телефон:<a href='tel:<?php echo $row_user->phone ; ?>' class='phone'><?php echo $row_user->phone ; ?></a></p>
					<p class='phone1'>Мобильный телефон:<a href='tel:<?php echo $row_user->mobile_phone; ?>' class='mobile_phone'><?php echo $row_user->mobile_phone; ?></a></p>
					<p class='phone1'>IP телефон:<a href='tel:<?php echo $row_user->ip_phone ; ?>' class='ip_phone'><?php echo $row_user->ip_phone ; ?></a></p>
					<p class='phone1'>АТС ГПО "БелЭнерго":<a href='tel:<?php echo $row_user->rup_phone; ?>' class='rup_phone'><?php echo $row_user->rup_phone; ?></a></p>
					<p class='phone1'>Внутренняя АТС:<a href='tel:<?php echo $row_user->branch_phone; ?>' class='branch_phone'><?php echo $row_user->branch_phone; ?></a></p>
				</div>
			</div>
			<button class='show_modal'>Редактировать</button>
		</div>
<?php }; // end if
		}; // end users
	mysqli_data_seek($res_users,0);	
echo "</div>";		
	}; // end if otdel
	
	
	}; // end otdel
	mysqli_data_seek($res_otd,0);
	mysqli_data_seek($res_users,0);
	echo "</div>";
}; // end podrezd
mysqli_data_seek($res_otd,0);
mysqli_data_seek($res_podrazdelenie,0);
 ?>
	<!--------------------------------------------------------------------------------------------------------->
	
		
	</div>
	<div class='block2'>
		<button class='show_modal show_modal_new'>Новая запись</button>
		<div class='search'>
			<input type="text" class="form-control pull-right" id="search" placeholder="Поиск по таблице">
		</div>
	</div>
</div>
</div>

<!-----------------Модальное окно------------------------------>
<div id="openModal" class="modalDialog" modal_mode='edit_person'>
	<div>
		<button title="закрыть" class="close">x</button>
		<?php
	//	$res_ozp = mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM ozp_potr_2020 where cod_potr = $row->course"));
		?>
			<div class="modalform ">
			
			<form class="form" id="">
				<input type="text" name='cod_user' id='cod_user' style='display:none'></input>
				<div class="select1" style='display:none'><label for="photo">Фото сотрудника</label><input type="text" name="photo" id="photo"></input></div>
				<div class="select1"><label for="fam">Фамилия</label><input type="text" name="fam" id="fam"></input></div>
				<div class="select1"><label for="name">Имя</label><input type="text" name="name" id="name"></input></div>
				<div class="select1"><label for="otch">Отчество</label><input type="text" name="otch" id="otch"></input></div>
				<div class="select1"><label for="pass">Табельный номер</label><input type="text" name="pass" id="pass"></input></div>
				
				<div class="select1"><label for="cod_branch">Филиал</label>

					<select name="cod_branch" id="cod_branch">
									<option value="0">Выберите филиал</option>
								<?php
									while($object = mysqli_fetch_object($res_branch)){
									echo "<option value = '$object->id'>$object->name</option>";
								}?>
					</select>
				</div>
				
				
				<div class="select1"><label for="cod_podr">Подразделение</label>
					
					<select name="cod_podr" id="cod_podr">
									<option value="0">Выберите подразделение</option>
								<?php
									while($object = mysqli_fetch_object($res_podrazdelenie)){
									echo "<option value = '$object->id'  cod_branch='$object->cod_branch'>$object->name_podrazd</option>";
								}?>
					</select>
				</div>
				
				<div class="select1"><label for="cod_otd">Отдел</label>
			
					<select name="cod_otd" id="cod_otd">
									<option value="0">Выберите отдел</option>
								<?php
									while($object = mysqli_fetch_object($res_otd)){
									echo "<option value = '$object->id' cod_podch='$object->cod_podch'>$object->name_otdel</option>";
								}?>
					</select>
				</div>
				
				
				
				<div class="select1"><label for="dolgnost">Должность</label><input type="text" name="dolgnost" id="dolgnost"></input></div>
				<div class="select1"><label for="email">e-mail</label><input type="text" name="email" id="email"></input></div>
				<div class="select1"><label for="phone">Городской телефон</label><input type="text" name="phone" id="phone"></input></div>
				<div class="select1"><label for="mobile_phone">Мобильный телефон</label><input type="text" name="mobile_phone" id="mobile_phone"></input></div>
				<div class="select1"><label for="ip_phone">IP телефон</label><input type="text" name="ip_phone" id="ip_phone"></input></div>
				<div class="select1"><label for="rup_phone">Телефон РУП "ОблЭнерго"</label><input type="text" name="rup_phone" id="rup_phone"></input></div>
				<div class="select1"><label for="branch_phone">Внутренняя АТС</label><input type="text" name="branch_phone" id="branch_phone"></input></div>
				<div><input type="submit" value="Сохранить"  class="submit"></div>

			</form>
			<div class="">
				  <div class="image-preview">
					<img id="preview" src="" alt="">
				  </div>
					<form id="upload-image" enctype="multipart/form-data">
						<div class="form-group">
						  <label for="image"></label>
						  <input type="file" name="image" id="image">
						</div>
						<input type="submit" class="" value="Загрузить">
					</form>
					<div id="result">
					</div>
			</div>
		
		</div>
	</div>

</div>
<div id="overlay"></div>	

<!----------------- END Модальное окно------------------------------>

</body>
<footer>
</footer>
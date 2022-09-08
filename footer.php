</div>
<footer>
<p>Информационный ресурс "Справочник Госэнергогазнадзора". По всем вопросам и предложениям обращаться в сектор РИТ.</p>
</footer>
<!-----------------Модальное окно------------------------------>
<div id="openModal" class="modalDialogPrint">
	<div>
		<button title="закрыть" class="close">x</button>
		
		<div class="ModalWindowReport">

			<form class='PrintForm'>
				
				<fieldset class='fieldset_info' >
				<legend>Скачать справочник</legend>
				
				<div class = "branch_list" >
				
					<div>
					<input type="checkbox" name="id_all"  id="id_all"></input>
					<label for ="id_all" class="m_name_org_t">Полный справочник</label>
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 7 "));
						$is_info =$is_info_arr[0] ;
					?>
					<input type="checkbox" name="id_upr"  id="id_upr" <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_upr" class="m_name_org_t">Управление Госэнергогазнадзора</label>
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 1 "));
						$is_info =$is_info_arr[0] ;
					?>
					<input type="checkbox" name="id_brest"  id="id_brest" <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_brest" class="m_name_tepl_t">Брестский филиал</label>					
					</div>
					<div>	
						<?php $is_info_arr = mysqli_fetch_row( mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 2 "));
						$is_info =$is_info_arr[0] ;

						?>					
					<input type="checkbox" name="id_vitebsk"  id="id_vitebsk" <?php echo ($is_info > 0 ? "" : "disabled");?>></input>
					<label for ="id_vitebsk" class="m_name_adr_t">Витебский филиал</label>					
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 3 "));
						$is_info =$is_info_arr[0] ;
					?>
					<input type="checkbox" name="id_gomel"  id="id_gomel" <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_gomel" class="m_name_otd_t">Гомельский филиал</label>					
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 4 "));
						$is_info =$is_info_arr[0] ;
					?>	
					
					<input type="checkbox" name="id_grodno"  id="id_grodno" <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_grodno" class="m_name_uch_t">Гродненский филиал</label>					
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 6 "));
						$is_info =$is_info_arr[0] ;
					?>
					<input type="checkbox" name="id_minsk"  id="id_minsk"  <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_minsk" class="m_name_obl_t">Минский филиал</label>					
					</div>
					<div>
					<?php $is_info_arr = mysqli_fetch_row(mysqli_query($connect, "SELECT count(*) FROM `users` WHERE spr_cod_branch = 5 "));
						$is_info =$is_info_arr[0] ;
					?>
					<input type="checkbox" name="id_mogilev"  id="id_mogilev" <?php echo ($is_info > 0 ? "" :  "disabled");?>></input>
					<label for ="id_mogilev" class="m_name_rayon_t">Могилевский филиал</label>					
					</div>
					<div>
					<input type="checkbox" name="id_personal"  id="id_personal" disabled ></input>
					<label for ="id_personal" class="m_name_ved_t">С личными телефонами</label>
					</div>
					
				</div>
				</fieldset>
			
					<div class="submit_print"><input type="submit" value="Сформировать"></div>
					<a href="" class="WordDownload"><div class=" ">Скачать</div></a>
					<div class="spinner">
						<span class="spinner__animation"></span>
						<!--span class="spinner__info">Загрузка...</span-->
					</div>

			</form>


		
		</div>
	</div>

</div>
<div id="overlay"></div>
<!-------------------------------------------------------------------------------------------------------------->
</body>
</html>
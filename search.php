<?php
   include 'settings.php';
//получаем данные через $_POST
if(isset($_POST['pre-search'])){
	$word = $_POST['pre-search'];
    // Строим запрос
    $sql = "SELECT * FROM users WHERE fam LIKE '%" . $word . "%' AND is_spr = 1 GROUP BY fam";
    // Получаем результаты
	$res_users = mysqli_query($connect, $sql);
	$res_users_name = mysqli_query($connect, "SELECT * FROM users WHERE name LIKE '%" . $word . "%' AND is_spr = 1 GROUP BY name");
	$res_users_ip_phone = mysqli_query($connect, "SELECT * FROM users WHERE ip_phone LIKE '%" . $word . "%'  AND is_spr = 1 GROUP BY ip_phone");
	
	$res_dolgnost = mysqli_query($connect, "SELECT * FROM users WHERE dolgnost LIKE '%" . $word . "%' AND is_spr = 1 GROUP BY dolgnost");
	$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE name LIKE '%" . $word . "%' or sokr_name LIKE '%" . $word . "%' GROUP BY name");
	$res_podrazd = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE name_podrazd LIKE '%" . $word . "%' or sokr_name LIKE '%" . $word . "%' GROUP BY name_podrazd");
	$res_otd_search = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE name_otdel LIKE '%" . $word . "%' GROUP BY name_otdel");
	$res_otd_search_sokr = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE sokr_name LIKE '%" . $word . "%' GROUP BY sokr_name");
	$end_result = '';
	
	
	 if(isset($res_dolgnost)  or isset($res_users)  or isset($res_branch) or isset($res_podrazd) or isset($res_otd_search)  or isset($res_otd_search_sokr) or isset($res_users_name) or isset($res_users_ip_phone)){ 
	 
	
	if(mysqli_num_rows($res_users_ip_phone) > 0) {
		
		while($row_users_ip_phone = mysqli_fetch_object($res_users_ip_phone)){ 
			$end_result     .= "<li>$row_users_ip_phone->ip_phone </li>";
		}
	};
	 if(mysqli_num_rows($res_branch) > 0) {
		
		while($row_branch = mysqli_fetch_object($res_branch)){ 
			$end_result     .= "<li>$row_branch->name </li>";
		}
	};	
	if(mysqli_num_rows($res_podrazd) > 0) {
		
		while($row_podr = mysqli_fetch_object($res_podrazd)){ 
			$end_result     .= "<li>$row_podr->name_podrazd</li>";
		}
	};
///******* ОТДЕЛЫ ******************	
	if(mysqli_num_rows($res_otd_search) > 0) {
		while($row_otd_s = mysqli_fetch_object($res_otd_search)){ 
			$end_result     .= "<li>$row_otd_s->name_otdel</li>";	
		}	
	};
	if(mysqli_num_rows($res_otd_search_sokr) > 0) {
		while($row_otd_s = mysqli_fetch_object($res_otd_search_sokr)){ 
			$end_result     .= "<li>$row_otd_s->sokr_name</li>";	
		}	
	};
	///******* ФИЗ ЛИЦА ******************
    if(mysqli_num_rows($res_users) > 0) {

		while($row_user = mysqli_fetch_object($res_users)){	
            $end_result     .= "<li>$row_user->fam</li>";
        }
    };
	 if(mysqli_num_rows($res_users_name) > 0) {
		while($row_user = mysqli_fetch_object($res_users_name)){	
            $end_result     .= "<li>$row_user->name</li>";
        }
    };
	if(mysqli_num_rows($res_dolgnost) > 0) {
		while($row_user = mysqli_fetch_object($res_dolgnost)){
            $end_result     .= "<li>$row_user->dolgnost</li>";
        }
	};
	if(mysqli_num_rows($res_dolgnost) == 0 AND mysqli_num_rows($res_users) == 0 AND mysqli_num_rows($res_branch) == 0 AND mysqli_num_rows($res_podrazd) == 0){
         $end_result     .= '';
    } 
	 }
	    echo $end_result;
};
if (isset($_POST['search'])) {
    // подключаемся к базе
    // никогда не доверяйте входящим данным! Фильтруйте всё!
   // $word = mysql_real_escape_string($_POST['search']);
    $word = $_POST['search'];
    // Строим запрос
    $sql = "SELECT * FROM users WHERE (fam LIKE '%" . $word . "%' or name LIKE '%" . $word . "%')  AND is_spr = 1";
    // Получаем результаты
	$res_users = mysqli_query($connect, $sql);
	$res_dolgnost = mysqli_query($connect, "SELECT * FROM users WHERE dolgnost LIKE '%" . $word . "%' AND is_spr = 1");
	$res_users_ip_phone = mysqli_query($connect, "SELECT * FROM users WHERE ip_phone LIKE '%" . $word . "%'  AND is_spr = 1 GROUP BY ip_phone");
	$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE name LIKE '%" . $word . "%' or sokr_name LIKE '%" . $word . "%'");
	$res_podrazd = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE name_podrazd LIKE '%" . $word . "%' or sokr_name LIKE '%" . $word . "%'");
	$res_otd_search = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE name_otdel LIKE '%" . $word . "%'  or sokr_name LIKE '%" . $word . "%'");
	
	$end_result = '';
	
	if(mysqli_num_rows($res_branch) > 0) {
		
		while($row_branch = mysqli_fetch_object($res_branch)){ 
		
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE cod_branch = $row_branch->id ORDER BY id");
			$end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='branch-list.php?id=$row_branch->id' class='person_link'><p class='name'>$row_branch->name </p></a></div>
			<div class='data_block'>
			
			<div class='person-photo'>
				<img src='images/users_photo/$row_branch->photo' alt='$row_branch->name'>
			</div>
				<div>
				<p><b>$row_branch->adress</b></p>
				<p ".(strlen($row_branch->phone)>0? "":"class='none-info'").">Телефон (гор.): <a href='tel:$row_branch->phone' class='link_phone link_phone_6'>".(strlen($row_branch->phone)>0? "+375($row_branch->phone_cod) $row_branch->phone":"")."</a></p>
				<p ".(strlen($row_branch->fax)>0? "":"class='none-info'").">Факс: <a href='tel:$row_branch->fax' class='ip_phone link_phone link_phone_7'>".(strlen($row_branch->fax)>0? "+375($row_branch->phone_cod) $row_branch->fax":"")."</a></p>				
				<p ".(strlen($row_branch->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_branch->email' class = 'e-mail'>$row_branch->email</a></p>
				</div>
			</div><div class='add_block_link'>";
			
			
			while($row_podrazd = mysqli_fetch_object($res_podrazdelenie)){
				$end_result     .= "<a href='department.php?id=$row_podrazd->id'>$row_podrazd->sokr_name</a>&nbsp;";
			}
			
			$end_result     .="</div></div></div>";
		}
		
	};
	
	if(mysqli_num_rows($res_podrazd) > 0) {
		
		while($row_podr = mysqli_fetch_object($res_podrazd)){ 
		
			$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE cod_podch = $row_podr->id ORDER BY inner_order");
			$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE id = $row_podr->cod_branch");
			
			$end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='department.php?id=$row_podr->id' class='person_link'><p class='name'>$row_podr->name_podrazd </p></a></div>
			<div class='data_block_otd'>
			
			<div class='person-photo'>
				<img src='images/users_photo/$row_podr->photo' alt='$row_podr->name_podrazd'' alt=''>
			</div>
				<div  class='adr_otd'>
				<p><b>$row_podr->adress</b></p>
				<p ".(strlen($row_podr->phone)>0? "":"class='none-info'").">Телефон (гор.): <a href='tel:$row_podr->phone' class='link_phone link_phone_6'>".(strlen($row_podr->phone)>0? "+375($row_podr->phone_cod) $row_podr->phone":"")."</a></p>
				<p ".(strlen($row_podr->fax)>0? "":"class='none-info'").">Факс: <a href='tel:$row_podr->fax' class='ip_phone link_phone link_phone_7'>".(strlen($row_podr->fax)>0? "+375($row_podr->phone_cod) $row_podr->fax":"")."</a></p>
				
				<p ".(strlen($row_podr->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_podr->email' class = 'e-mail'>$row_podr->email</a></p>
				</div>
			</div><ul class='add_block_link_otd'>";
			
			
			while($row_otd = mysqli_fetch_object($res_otd)){
				$end_result     .= "<li><a href='district.php?id=$row_otd->id'>$row_otd->name_otdel</a></li>";
			}
			
			$end_result     .="</ul><div class='add_block_link'>";
			
			while($row_branch = mysqli_fetch_object($res_branch)){
				$end_result     .= "<a href='branch-list.php?id=$row_branch->id'>$row_branch->name</a>&nbsp;";
			}
			
			$end_result     .="</div></div></div>";
		}
		
	};
///******* ОТДЕЛЫ ******************	
	if(mysqli_num_rows($res_otd_search) > 0) {

		while($row_otd_s = mysqli_fetch_object($res_otd_search)){ 
		
			$res_person = mysqli_query($connect, "SELECT * FROM users WHERE spr_cod_otd = $row_otd_s->id AND is_spr = 1 ORDER BY filter_spr");
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id = $row_otd_s->cod_podch");
			$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE id = $row_otd_s->cod_branch");
			
			$end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='district.php?id=$row_otd_s->id' class='person_link'><p class='name'>$row_otd_s->name_otdel </p></a></div>
			<div class='data_block_otd'>
			
			<div class='person-photo'>
				<img src='images/users_photo/$row_otd_s->photo' alt='$row_otd_s->name_otdel' alt=''>
			</div>
				<div class='adr_otd'>
				<p><b>$row_otd_s->adress</b></p>
				<p ".(strlen($row_otd_s->phone)>0? "":"class='none-info'").">Телефон: <a href='tel:$row_otd_s->phone' class='link_phone  link_phone_8'>".(strlen($row_otd_s->phone)>0? "+375($row_otd_s->phone_cod) $row_otd_s->phone":"")."</a></p>
				<p ".(strlen($row_otd_s->fax)>0? "":"class='none-info'").">Факс: <a href='tel:$row_otd_s->fax' class='ip_phone link_phone link_phone_7'>".(strlen($row_otd_s->fax)>0? "+375($row_otd_s->phone_cod) $row_otd_s->fax":"")."</a></p>			
				<p ".(strlen($row_otd_s->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_otd_s->email' class = 'e-mail'>$row_otd_s->email</a></p>
				</div>
			</div><ul class='add_block_link_otd'>";
			
			
			while($row_pers = mysqli_fetch_object($res_person)){
				$end_result     .= "<li><a href='person.php?id=$row_pers->id'>$row_pers->fio</a></li>";
			}
			
			$end_result     .="</ul><div class='add_block_link'>";
			
			while($row_branch = mysqli_fetch_object($res_branch)){
				$end_result     .= "<a href='branch-list.php?id=$row_branch->id'>$row_branch->sokr_name</a>&nbsp;";
			}
			while($row_podrazdelenie = mysqli_fetch_object($res_podrazdelenie)){
				$end_result     .= "<a href='department.php?id=$row_podrazdelenie->id'>$row_podrazdelenie->sokr_name</a>&nbsp;";
			}
			
			$end_result     .="</div></div></div>";
		}
		
	};
	///******* ФИЗ ЛИЦА ******************
    if(mysqli_num_rows($res_users) > 0) {
        
     
	 
		while($row_user = mysqli_fetch_object($res_users)){
            $s_cod_otd       = $row_user->spr_cod_otd;
            $s_cod_branch       = $row_user->spr_cod_branch;
            $s_cod_podrazd       = $row_user->spr_cod_podrazd;
			
			$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE id = $s_cod_otd");
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id = $s_cod_podrazd");
			$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE id = $s_cod_branch");
			
			$row_branch = mysqli_fetch_object($res_branch);
			$row_podrazd = mysqli_fetch_object($res_podrazdelenie);
			$row_otd = mysqli_fetch_object($res_otd);
			
            $end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='person.php?id=$row_user->id' class='person_link'><p class='name'><span class='fam'>$row_user->fam </span><span class='name'>$row_user->name </span><span class='otch'>$row_user->otch </p></a></div>
			<div class='data_block'>
			<div class='person-photo'>
				<img src='images/users_photo/$row_user->photo' alt='$row_user->fio;'>
			</div>
				<div>
				<p><b>$row_user->dolgnost</b></p>
				<p ".(strlen($row_user->phone)>0? "":"class='none-info'").">Телефон (гор.): <a href='tel:".str_replace('(','',str_replace(')','',$row_user->phone))."' class='link_phone link_phone_1'>".(strlen($row_user->phone)>0 ? "$row_user->phone" :"")."</a></p>
				<p ".(strlen($row_user->mobile_phone)>0? "":"class='none-info'").">Мобильный телефон: <a href='tel:".str_replace('(','',str_replace(')','',$row_user->mobile_phone))."' ' class='link_phone link_phone_2'>".(strlen($row_user->mobile_phone)>0 ? $row_user->mobile_phone :"")."</a></p>
				<p ".(strlen($row_user->ip_phone)>0? "":"class='none-info'").">IP телефон: <a href='tel:$row_user->ip_phone' class='ip_phone link_phone link_phone_3'>$row_user->ip_phone</a></p>
				<p ".(strlen($row_user->rup_phone)>0 ?  "":"class='none-info'").">Доп АТС: <a href='tel:".$row_user->rup_phone."' class='rup_phone link_phone link_phone_4'>".$row_user->rup_phone." </a></p>
				<p ".(strlen($row_user->branch_phone)>0? "":"class='none-info'").">Внутренняя АТС: <a href='tel:$row_user->branch_phone' class='branch_phone link_phone link_phone_5'>$row_user->branch_phone</a></p>
				
				<p ".(strlen($row_user->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_user->email' class = 'e-mail'>$row_user->email</a></p>
				</div>
			</div>
			<div class='add_block_link'><a href='branch-list.php?id=$row_branch->id'>$row_branch->sokr_name</a><a href='department.php?id=$row_podrazd->id'>$row_podrazd->sokr_name</a><a href='district.php?id=$row_otd->id'>$row_otd->sokr_name</a></div>
			</div></div>";
        }
      
    };
	
	///******* ФИЗ ЛИЦА по IP телефону ******************
    if(mysqli_num_rows($res_users_ip_phone) > 0) {
        
     
	 
		while($row_user = mysqli_fetch_object($res_users_ip_phone)){
            $s_cod_otd       = $row_user->spr_cod_otd;
            $s_cod_branch       = $row_user->spr_cod_branch;
            $s_cod_podrazd       = $row_user->spr_cod_podrazd;
			
			$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE id = $s_cod_otd");
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id = $s_cod_podrazd");
			$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE id = $s_cod_branch");
			
			$row_branch = mysqli_fetch_object($res_branch);
			$row_podrazd = mysqli_fetch_object($res_podrazdelenie);
			$row_otd = mysqli_fetch_object($res_otd);
			
            $end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='person.php?id=$row_user->id' class='person_link'><p class='name'><span class='fam'>$row_user->fam </span><span class='name'>$row_user->name </span><span class='otch'>$row_user->otch </p></a></div>
			<div class='data_block'>
			<div class='person-photo'>
				<img src='images/users_photo/$row_user->photo' alt='$row_user->fio;'>
			</div>
				<div>
				<p><b>$row_user->dolgnost</b></p>
				<p ".(strlen($row_user->phone)>0? "":"class='none-info'").">Телефон (гор.): <a href='tel:".str_replace('(','',str_replace(')','',$row_user->phone))."' class='link_phone link_phone_1'>".(strlen($row_user->phone)>0 ? "$row_user->phone" :"")."</a></p>
				<p ".(strlen($row_user->mobile_phone)>0? "":"class='none-info'").">Мобильный телефон: <a href='tel:".str_replace('(','',str_replace(')','',$row_user->mobile_phone))."' ' class='link_phone link_phone_2'>".(strlen($row_user->mobile_phone)>0 ? $row_user->mobile_phone :"")."</a></p>
				<p ".(strlen($row_user->ip_phone)>0? "":"class='none-info'").">IP телефон: <a href='tel:$row_user->ip_phone' class='ip_phone link_phone link_phone_3'>$row_user->ip_phone</a></p>
				<p ".(strlen($row_user->rup_phone)>0 ?  "":"class='none-info'").">Доп АТС: <a href='tel:".$row_user->rup_phone."' class='rup_phone link_phone link_phone_4'>".$row_user->rup_phone." </a></p>
				<p ".(strlen($row_user->branch_phone)>0? "":"class='none-info'").">Внутренняя АТС: <a href='tel:$row_user->branch_phone' class='branch_phone link_phone link_phone_5'>$row_user->branch_phone</a></p>
				
				<p ".(strlen($row_user->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_user->email' class = 'e-mail'>$row_user->email</a></p>
				</div>
			</div>
			<div class='add_block_link'><a href='branch-list.php?id=$row_branch->id'>$row_branch->sokr_name</a><a href='department.php?id=$row_podrazd->id'>$row_podrazd->sokr_name</a><a href='district.php?id=$row_otd->id'>$row_otd->sokr_name</a></div>
			</div></div>";
        }
      
    };
	
	if(mysqli_num_rows($res_dolgnost) > 0) {
		
		while($row_user = mysqli_fetch_object($res_dolgnost)){
			
            $s_cod_otd       = $row_user->spr_cod_otd;
            $s_cod_branch       = $row_user->spr_cod_branch;
            $s_cod_podrazd       = $row_user->spr_cod_podrazd;
			
			$res_otd = mysqli_query($connect, "SELECT * FROM spr_otdel WHERE id = $s_cod_otd");
			$res_podrazdelenie = mysqli_query($connect, "SELECT * FROM spr_podrazdelenie WHERE id = $s_cod_podrazd");
			$res_branch = mysqli_query($connect, "SELECT * FROM spr_branch WHERE id = $s_cod_branch");
			
			$row_branch = mysqli_fetch_object($res_branch);
			$row_podrazd = mysqli_fetch_object($res_podrazdelenie);
			$row_otd = mysqli_fetch_object($res_otd);
			
            $end_result     .= "<div class='result_html'>
			<div class='person_data'>
			<div class='block_link'><a href='person.php?id=$row_user->id' class='person_link'><p class='name'><span class='fam'>$row_user->fam </span><span class='name'>$row_user->name </span><span class='otch'>$row_user->otch </p></a></div>
			<div class='data_block'>
			<div class='person-photo'>
				<img src='images/users_photo/$row_user->photo' alt='$row_user->fio'>
			</div>
				<div>
				<p><b>$row_user->dolgnost</b></p>
				<p ".(strlen($row_user->phone)>0? "":"class='none-info'").">Телефон (гор.): <a href='tel: +375$row_otd->phone_cod.$row_user->phone' class='link_phone link_phone_1'>".(strlen($row_user->phone)>0 ? "+375($row_otd->phone_cod) $row_user->phone" :"")."</a></p>
				<p ".(strlen($row_user->ip_phone)>0? "":"class='none-info'").">IP телефон: <a href='tel:$row_user->ip_phone' class='ip_phone link_phone link_phone_3'>$row_user->ip_phone</a></p>
				<p ".(strlen($row_user->branch_phone)>0? "":"class='none-info'").">Внутренняя АТС: <a href='tel:$row_user->branch_phone' class='branch_phone link_phone link_phone_5'>$row_user->branch_phone</a></p>
				<p ".(strlen($row_user->email)>0? "":"class='none-info'").">e-mail: <a href='mailto:$row_user->email' class = 'e-mail'>$row_user->email</a></p>
				</div>
			</div>
			<div class='add_block_link'><a href='branch-list.php?id=$row_branch->id'>$row_branch->sokr_name</a><a href='department.php?id=$row_podrazd->id'>$row_podrazd->sokr_name</a><a href='district.php?id=$row_otd->id'>$row_otd->sokr_name</a></div>
			</div></div>";
        }
		
	};
	if(mysqli_num_rows($res_dolgnost) == 0 AND mysqli_num_rows($res_users) == 0 AND mysqli_num_rows($res_branch) == 0 AND mysqli_num_rows($res_podrazd) == 0){
         $end_result     .= '<p class="result_html">По вашему запросу ничего не найдено</p>';
    }
	  echo $end_result;
}
?>

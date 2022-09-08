<?php 
function fields_name($fld_name, $name_spr, $place){
	$str_name = trim($fld_name);
	$field_name = $str_name;
	switch($str_name){
				
			case "adress":
				$field_name = 'Адрес'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "phone_cod":
				$field_name = 'Телефонный код';
				break;	
			case "phone":
				$field_name = 'Тел.(гор.)';
				break;	
			case "fax":
				$field_name = 'Факс';
				break;	
			case "email":
				$field_name = 'E-mail';
				break;	
			case "photo":
				$field_name = 'Фото';
				break;
			case "sokr_name":
				$field_name = 'Сокращенное наименование'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "name_otdel":
				$field_name = 'Уровень3 (РЭГИ, отделы, сектора)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			
			case "name_podrazd":
				$field_name = 'Уровень2 (Отделения, управления)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "name":
				if(strcmp(trim($name_spr),"users") == 0 or strcmp(trim($name_spr),"fired") == 0){
					$field_name = 'Имя'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
					
				}else{
					$field_name = 'Уровень1 (Филиал)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");	
				}
				break;
			case "fio":
				$field_name = 'Ф.И.О.'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "dolgnost":
				$field_name = 'Должность'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;		
			case "mobile_phone":
				$field_name = 'Мобильный телефон';
				break;	
			case "ip_phone":
				$field_name = 'IP телефон';
				break;
			case "rup_phone":
				$field_name = 'Доп. АТС';
				break;	
			case "branch_phone":
				$field_name = 'Внутренняя АТС';
				break;	
			case "spr_cod_podrazd":
				$field_name = 'Уровень2 (Отделения, управления)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "spr_cod_otd":
				$field_name = 'Уровень3 (РЭГИ, отделы, сектора)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "spr_cod_branch":
				$field_name = 'Уровень1 (Филиал)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "cod_branch":
				$field_name = 'Уровень1 (Филиал)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "cod_podch":
				$field_name = 'Уровень2 (Отделения, управления)'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "fam":
				$field_name = 'Фамилия'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
				
			case "otch":
				$field_name = 'Отчество'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "id":
				$field_name = 'ID';
				break;
			case "tab_num":
				$field_name = 'Табельный номер'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "password":
				$field_name = 'Пароль'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "login":
				$field_name = 'Логин'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;
			case "filter_spr":
			case "inner_order":
				$field_name = 'Порядок сортировки'.( strcmp(trim($place),"modal") == 0 ? "<span class = 'formTextRed'>*</span>": "");
				break;	
			case "prioritet":
				$field_name = 'Приоритет для АРМ';
				break;
			case "podrazdelenie":
				$field_name = 'Группа для АРМ';
				break;
			case "id_user":
				$field_name = 'Пользователь';
				break;	
			case "admin":
				$field_name = 'Администратор';
				break;
			case "spr_admin":
				$field_name = 'Администратор филиала (для справочника)';
				break;	
			case "arm_prioritet":
				$field_name = 'Приоритет АРМ ОЗП (Витебск)';
				break;
			case "inc_prioritet":
				$field_name = 'Приоритет (пожары)';
				break;
			case "ip_phone_otd":
				$field_name = 'Группа для IP-телефонии';
				break;	
			case "arm_gruppa":
				$field_name = 'Инспекция <span class="ps">(газ/тепло/электро)</span>';
				break;
			case "access_level":
				$field_name = 'Уровень доступа АРМ (БАЗИС)';
				break;
			case "sort":
				$field_name = 'Порядок сортировки';
				break;	
	}
	return  $field_name;
}
?>
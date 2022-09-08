<?php
function fun_pb_contacts(){
//Создание удаленной КНИГИ pb содержащей весь надзор ------группы как в ip_group------------------------------------------------------------------------------------------------------------------------------------------
	//Устанавливаем доступы к базе данных:
		$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = 'admin'; //пароль, по умолчанию пустой
		$db_name = 'gegn'; //имя базы данных

	//Переменные
		//$branch=2; //код филиала
		$urll=$_SERVER['DOCUMENT_ROOT']."/phonebook/admin/ip_book/pbBook/"; //Путь к файлу на сервере
		$file = "pb.xml"; // название файла
		$filed = $urll.$file; //Путь к файлу и название файла
		$ftp_host = "10.170.0.2"; 
		$ftp_url="Gosenergogaznadzor/Phonebook";
		$otvet="";
		$total=0;
		
	//Соединяемся с базой данных используя наши доступы:
		$link=mysqli_connect($host, $user, $password, $db_name); //or die("Не могу соединиться с MySQL.");
	
	//Создание файла книги по шаблону 
		$rez = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<YealinkIPPhoneBook>\r\n  <Title>Yealink</Title>\r\n";
	
	//Необходимые записи в файле
		$mnn="  <Menu Name=\"";
		$unt="    <Unit Name=\"[";
		$phn="\" Phone1=\"";
		$eend="\" Phone2=\"\" Phone3=\"\" default_photo=\"Resource:\"/>";
		
	//Запрос в таблицу ip_group с правильной сортировкой  
	//	$arr_grp=mysqli_query($link,"SELECT * FROM `ip_group` ORDER BY sort");

	
	//Запрос в таблицу users с объединением с группами и филиалами + правильной сортировкой 
		$arr_users=mysqli_query($link,"SELECT users.filter_spr, users.fam, users.name, users.otch, users.ip_phone, users.ip_phone_otd, ip_group.name AS nameGR, ip_group.sort, users.spr_cod_branch AS sprB,
		spr_otdel.sokr_name AS sokr_nameP, spr_podrazdelenie.sokr_name AS sokr_name
									FROM users 
									INNER JOIN ip_group ON users.ip_phone_otd =ip_group.id
									INNER JOIN spr_branch ON users.spr_cod_branch=spr_branch.id 
                                    INNER JOIN spr_podrazdelenie ON users.spr_cod_podrazd=spr_podrazdelenie.id
                                    INNER JOIN spr_otdel ON users.spr_cod_otd=spr_otdel.id
                                    WHERE ip_phone<>\"\" AND is_spr=1 
									ORDER BY ip_group.sort, ip_phone_otd DESC, spr_branch.id, users.filter_spr");
		
		$zotd=1000; //для прехода к следующему отделу		
		$glav="*";	//помечаем первого по списку главным в отделе
		$sprCB="";
		$nomer=1;
		
		while ($row = mysqli_fetch_array($arr_users)){ //крутим весь массив
			
			if ($nomer<10) {
				$str_nomer="0".$nomer;
			}else{
				$str_nomer=$nomer;
			}
			
			
			$pref=""; //добавляем в три буквы от соращенного названия отдела МРО или РЭГИ[ВТ_Пол]
			
			if ($zotd<>$row['ip_phone_otd']){   // если не меняется код отдела открываем группу
				$menuname=$mnn.$str_nomer." ".$row['nameGR']."\">\r\n"; //формируем название группы
				$rez=$rez.$menuname;
				$zbr=1000; //для прехода к следующему филиалу
				$mnn="  </Menu>\r\n"."  <Menu Name=\""; //закрыть и открыть группу
				$nomer=$nomer+1;
				$zotd=$row['ip_phone_otd'];
			}else{	
				
			}
			
			//Выбор области
			$xx=$row['sprB'];
			switch ($xx) {
				case 1: $sprCB="БР";
				break;
				case 2: $sprCB="ВТ";
				break;
				case 3:	$sprCB="ГМ";
				break;
				case 4:	$sprCB="ГР";			
				break;
				case 5:	$sprCB="МГ";
				break;
				case 6:	$sprCB="МН";
				break;
				case 7: $sprCB="АУ";
				break;
			}
			
			if ($zbr==$row['sprB']){   // если измениться код филиала помечаем первого по списку главным
				$glav=""; //убираем пометку
			}else{
				$glav="*";	//помечаем первого по списку главным в отделе
				$zbr=$row['sprB'];
			}

			if ($row['ip_phone_otd']==14 or $row['ip_phone_otd']==15 or $row['ip_phone_otd']==21){	//группы: 14(МРО),15(РЭГИ),21(прочие) 
				$glav=""; //убираем пометку там нет главных

				$perv=mb_substr($row['sokr_name'],0,1); 										//запоминаем первую букву
				$row['sokr_name']=mb_substr($row['sokr_name'],1,3); 							//следующие буквы
				$row['sokr_name']=preg_replace('#[аеёиоуыэюя\s]+#iu', '', $row['sokr_name']); 	//удаляем все гласные буквы (i заглавные, u кодировка UTF-8)
				$row['sokr_name']=$perv.mb_substr($row['sokr_name'],0,1); 						//формируем название
				
				$pref="/".$row['sokr_name']." МрО";  //итог: добавляем 2 буквы от соращенного названия МРО ВТ/Вт
			}
			
			if ($row['ip_phone_otd']==15){	//группы: 15(РЭГИ) дописываем соращенное название РЭГИ

				$perv=mb_substr($row['sokr_nameP'],0,1); 											//запоминаем первую букву
				$row['sokr_nameP']=mb_substr($row['sokr_nameP'],1,3); 								//следующие буквы
				$row['sokr_nameP']=preg_replace('#[аеёиоуыэюя\s]+#iu', '', $row['sokr_nameP']); 	//удаляем все гласные буквы (i заглавные, u кодировка UTF-8)
				$row['sokr_nameP']=$perv.mb_substr($row['sokr_nameP'],0,1); 						//формируем название
				
				$pref=$pref."/".$row['sokr_nameP'];//добавляем 2 буквы от соращенного названия РЭГИ ВТ/Бш
			}
			
			$rez=$rez.$unt.$glav.$sprCB.$pref."] ".$row['fam']." ".$row['name']." ".$row['otch'].$phn.$row['ip_phone'].$eend."\r\n"; // наполняем группу сотрудниками
			$total=$total+1;
				 
					
		} 	
		$rez=$rez."  </Menu>\r\n"."</YealinkIPPhoneBook>"; //конец формирования файла
	

	//Записываем результат в файл 
		file_put_contents($filed, $rez);
	
    //Загрузка на FTP. 
		$ftp = ftp_connect($ftp_host, "21", "30"); // Создаём идентификатор соединения (адрес хоста, порт, таймаут)
		$login = ftp_login($ftp, "Vitebsk", "Vitebsk"); // Авторизуемся на FTP-сервере
		if (!$login) exit("Ошибка подключения");
		ftp_chdir($ftp, $ftp_url); // Заходим в директорию
		
		// проверяем есть ли в текущей директории наш файл (если его нет фунция возращает -1) проверка для его удаления, а затем загрузки или сразу загрузки 
		$file_size = ftp_size($ftp, $file);
		if ($file_size != -1) {
			$otvet=$otvet.'Файл существует на сервере.<br>';
			if (ftp_delete($ftp, $file)) { // попытка удалить файл так как он существует, и вывод результата
				$otvet=$otvet."Файл удалён с сервера.<br>";
				} else {
				$otvet=$otvet."На сервере не удалось удалить существующий файл.<br>";
			}
			} else {
			$otvet=$otvet.'Такого файла не было найдено на сервере.<br>';
		}	
		if (ftp_put($ftp, $file, $filed, FTP_BINARY)) { // попытка загрузки на FTP в бинарном режиме и вывод результата. Можно переименовав написав новое имя в первых кавычках
			$otvet=$otvet."<br>"."Новый файл удаленной книги <b>".$file."</b> (".$total." contacts) загружен по адресу: FTP://".$ftp_host."/".$ftp_url."/."."<br><br>"."<b>Операция выполнена успешно.</b>"."<br>";
			} else {
			$otvet=$otvet."Не удалось загрузить файл."."<br>"."Ошибка!"."<br>";
		}
	ftp_close($ftp);	
echo $otvet;	

}
?>
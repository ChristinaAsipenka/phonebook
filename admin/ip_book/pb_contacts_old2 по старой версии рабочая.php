<?php

/*function pb_cont()
{*/
//Создание локальной КНИГИ контактов pbcontacts(для Yealink) и PBfanvil------------------------------------------------------------------------------------------------------------------------------------------
	//Устанавливаем доступы к базе данных:
		$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = ''; //пароль, по умолчанию пустой
		$db_name = 'main'; //имя базы данных
		
	//Переменные
		$urll=$_SERVER['DOCUMENT_ROOT']."/admin/ip_book/pbbook/"; //Путь к файлу на сервере
		$file1 = "pbcontacts.xml"; // название файла для Yealink
		$file2 = "pbfanvil.xml"; // название файла для Fanvil
		$urlfile1 = $urll.$file1; //Путь к файлу и название файла для Yealink
		$urlfile2 = $urll.$file2; //Путь к файлу и название файла для Fanvil
		$ftp_host = "10.170.0.2"; 
		$ftp_url="Gosenergogaznadzor/Phonebook";
		$otvet="";
		$total1=0;
		$total2=0;		

	//Соединяемся с базой данных используя наши доступы:
		$link=mysqli_connect($host, $user, $password, $db_name); //or die("Не могу соединиться с MySQL.");
		
//для Yealink-------------------------------------------------------------------------------------------------------	
	//Запрос в первую таблицу ip_group
		$arr_ip_group=mysqli_query($link,"SELECT * FROM `ip_group` ORDER BY id"); 
	
	//Создание файла локальной книги по шаблону 
		$rez1 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<vp_contact>\r\n  <root_group>\r\n";
	
	while ($row_grp = mysqli_fetch_array($arr_ip_group)){
			//вывод перечня групп в ip контактах локальной книги
			
			// с номерами групп (номера нужны для правильной сортировки)
			$rez1 = $rez1."    <group display_name=\"".$row_grp['sort']." ".$row_grp['name']."\"/>\r\n";
			$arr_grp[]=$row_grp['sort']." ".$row_grp['name']; 	
			/* можно без номеров групп
			$rez1 = $rez1."    <group display_name=\"".$row_grp['name']."\"/>\r\n";
			$arr_grp[]=$row_grp['name'];	
			*/			
	} 	
	$rez1 = $rez1."  </root_group>\r\n";
	$rez1 = $rez1."  <root_contact>\r\n";
	//echo "---------------------------------<br>";
		
	//Необходимые записи в файле
		$cnt="    <contact group_id_name=\"";
		$dsn="\" display_name=\"";
		$ofn="\" office_number=\"";
		$eend="\" mobile_number=\"\" other_number=\"\" default_photo=\"Resource:\"/>";
	
	//Запрос во вторую таблицу users с правильной сортировкой
		$arr_users=mysqli_query($link,"SELECT * FROM `users` WHERE ip_phone<>\"\" AND is_spr=1 ORDER BY spr_cod_branch, ip_phone_otd, filter_spr"); //$arr_users=mysqli_query($link,"SELECT * FROM `users` WHERE id like '%5'");
		//для повторного использования надо меняет указатель в массиве на 0 mysqli_data_seek($arr_users,0);

		$zz=0; //для присвоения *-главный в группе
		while ($row = mysqli_fetch_array($arr_users)){
			
			//Выбор области
			$xx=$row['spr_cod_branch'];
			switch ($xx) {
				case 1: $spr_cod_branch="БР";
				break;
				case 2: $spr_cod_branch="ВТ";
				break;
				case 3:	$spr_cod_branch="ГМ";
				break;
				case 4:	$spr_cod_branch="ГР";			
				break;
				case 5:	$spr_cod_branch="МГ";
				break;
				case 6:	$spr_cod_branch="МН";
				break;
				case 7: $spr_cod_branch="АУ";
				break;
			}
			
			if ($row['ip_phone_otd']==0 or $row['ip_phone_otd']==null){	//всем у кого не выбрана группа назначаем группу 21.Прочие 
				$row['ip_phone_otd']=21;
			}

			//присвоение *-главный в группе, первому user в IP-группе
			if ($row['ip_phone_otd']==14 or $row['ip_phone_otd']==15 or $row['ip_phone_otd']==21){	//игнорируем 14,15,21 группы там нет главных
				}else {
					if ($zz<>$row['ip_phone_otd']){
						$spr_cod_branch="*".$spr_cod_branch;
						$zz=$row['ip_phone_otd'];
					}
			}
				
			//Вывод результата
				$rez1=$rez1.$cnt.$arr_grp[$row['ip_phone_otd']-1].$dsn."[".$spr_cod_branch."] ".$row['fam']." ".$row['name']." ".$row['otch'].$ofn.$row['ip_phone'].$eend."\r\n";
				$total1=$total1+1;
		} 	
	$eend="  </root_contact>"."\r\n";
	$eend=$eend."</vp_contact>";
    $rez1=$rez1.$eend;
//----------------------------------------------------------------------------------------------------------------------------------
	
//для Fanvil------------------------------------------------------------------------------------------------------------------------

	//Создание файла локальной книги по шаблону 
		$rez2 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<PhoneBook>\r\n";
	
	//Запрос в таблицу users с правильной сортировкой
		$arr_users2=mysqli_query($link,"SELECT users.name, users.fam, users.otch, users.ip_phone, users.spr_cod_branch, users.ip_phone_otd, ip_group.name AS grName, ip_group.sort AS grSort, users.filter_spr
										FROM `users` 
										LEFT JOIN ip_group ON users.ip_phone_otd=ip_group.id 
										WHERE ip_phone<>\"\" AND is_spr=1 
										ORDER BY spr_cod_branch, grSort, filter_spr");

		$zz=0; //для присвоения *-главный в группе
		while ($row = mysqli_fetch_array($arr_users2)){
			
			//Выбор области
			$xx=$row['spr_cod_branch'];
			switch ($xx) {
				case 1: $spr_cod_branch="БР";
				break;
				case 2: $spr_cod_branch="ВТ";
				break;
				case 3:	$spr_cod_branch="ГМ";
				break;
				case 4:	$spr_cod_branch="ГР";			
				break;
				case 5:	$spr_cod_branch="МГ";
				break;
				case 6:	$spr_cod_branch="МН";
				break;
				case 7: $spr_cod_branch="АУ";
				break;
			}
			
			if ($row['ip_phone_otd']==0 or $row['ip_phone_otd']==null){	//всем у кого не выбрана группа назначаем группу 21.Прочие 
				$row['ip_phone_otd']=21;
			}

			//присвоение *-главный в группе, первому user в IP-группе
			if ($row['ip_phone_otd']==14 or $row['ip_phone_otd']==15 or $row['ip_phone_otd']==21){	//игнорируем 14,15,21 группы там нет главных
				}else {
					if ($zz<>$row['ip_phone_otd']){
						$spr_cod_branch="*".$spr_cod_branch;
						$zz=$row['ip_phone_otd'];
					}
			}
			
			//Необходимые записи в файле
				$DE1="  <DirectoryEntry>"."\r\n";
				$DE2="  </DirectoryEntry>"."\r\n";
				$N1="    <Name>";
				$N2="</Name>"."\r\n";
				$T1="    <Telephone>";
				$T2="</Telephone>"."\r\n";
				$M12="    <Mobile/>"."\r\n";
				$O12="    <Other/>"."\r\n";
				$R1="    <Ring>";
				$R2="</Ring>"."\r\n";
				$G1="    <Group>";
				$G2="</Group>"."\r\n";
				$eend2="</PhoneBook>"."\r\n";
				
			//Вывод результата
				$rez2=$rez2.$DE1.$N1."[".$spr_cod_branch."] ".$row['fam']." ".$row['name']." ".$row['otch'].$N2.$T1.$row['ip_phone'].$T2.$M12.$O12.$R1."Default".$R2.$G1.$row['grSort']." ".$row['grName'].$G2.$DE2;
				$total2=$total2+1;
		} 	

    $rez2=$rez2.$eend2;
//----------------------------------------------------------------------------------------------------------------------------------

	
	//Записываем результат в файл 
		file_put_contents($urlfile1, $rez1);
		file_put_contents($urlfile2, $rez2);
	
    //Загрузка на FTP. Надо влючить extension=php_ftp.dll в php.ini и рестарт апач c:\Server\bin\Apache24\bin\httpd.exe -k restart
		//перенес в переменные $ftp_host = "10.170.0.2"; 
		//перенес в переменные $ftp_url="Gosenergogaznadzor/Phonebook";
		$ftp = ftp_connect($ftp_host, "21", "30"); // Создаём идентификатор соединения (адрес хоста, порт, таймаут)
		$login = ftp_login($ftp, "Vitebsk", "Vitebsk"); // Авторизуемся на FTP-сервере
		if (!$login) exit("Ошибка подключения");
		ftp_chdir($ftp, $ftp_url); // Заходим в директорию
		
		// для Yealink проверяем есть ли в текущей директории наш файл (если его нет фунция возращает -1) проверка для его удаления, а затем загрузки или сразу загрузки 
		$file_size = ftp_size($ftp, $file1);
		if ($file_size != -1) {
			$otvet=$otvet.'Файл:'.$file1.' существует на сервере.<br>';
			if (ftp_delete($ftp, $file1)) { // попытка удалить файл так как он существует, и вывод результата
				$otvet=$otvet."Файл:".$file1." удалён с сервера.<br>";
				} else {
				$otvet=$otvet."На сервере не удалось удалить существующий файл:".$file1.".<br>";
			}
			} else {
			$otvet=$otvet.'Такого файла:'.$file1.' не было найдено на сервере.<br>';
		}	
		if (ftp_put($ftp, $file1, $urlfile1, FTP_BINARY)) { // попытка загрузки на FTP в бинарном режиме и вывод результата. Можно переименовав написав новое имя в первых кавычках
			$otvet=$otvet."<br>"."Новый файл книги <b>".$file1."</b> (".$total1." contacts) загружен по адресу: FTP://".$ftp_host."/".$ftp_url."/."."<br><br>"."<b>Операция выполнена успешно.</b>"."<br>";
			} else {
			$otvet=$otvet."Не удалось загрузить файл:".$file1."."."<br>"."Ошибка!"."<br>";
		}
		
		$otvet=$otvet."------------------------------------<br>";
		
		// для Fanvil проверяем есть ли в текущей директории наш файл (если его нет фунция возращает -1) проверка для его удаления, а затем загрузки или сразу загрузки 
		$file_size = ftp_size($ftp, $file2);
		if ($file_size != -1) {
			$otvet=$otvet.'Файл:'.$file2.' существует на сервере.<br>';
			if (ftp_delete($ftp, $file2)) { // попытка удалить файл так как он существует, и вывод результата
				$otvet=$otvet."Файл:".$file2." удалён с сервера.<br>";
				} else {
				$otvet=$otvet."На сервере не удалось удалить существующий Файл:".$file2.".<br>";
			}
			} else {
			$otvet=$otvet.'Такого файла:'.$file2.' не было найдено на сервере.<br>';
		}	
		if (ftp_put($ftp, $file2, $urlfile2, FTP_BINARY)) { // попытка загрузки на FTP в бинарном режиме и вывод результата. Можно переименовав написав новое имя в первых кавычках
			$otvet=$otvet."<br>"."Новый файл книги <b>".$file2."</b> (".$total2." contacts) загружен по адресу: FTP://".$ftp_host."/".$ftp_url."/."."<br><br>"."<b>Операция выполнена успешно.</b>"."<br>";
			} else {
			$otvet=$otvet."Не удалось загрузить файл:".$file2."."."<br>"."Ошибка!"."<br>";
		}
	ftp_close($ftp);

echo $otvet;	
//}


?>
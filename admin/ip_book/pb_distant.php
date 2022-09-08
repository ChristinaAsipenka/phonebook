<?php

/*function pb_dist($branch)
{*/

include $_SERVER['DOCUMENT_ROOT']."/phonebook/admin/ip_book/fun_pb_contacts.php"; // $_SERVER['DOCUMENT_ROOT'] - указывает корневую директорию сайта
$branch=$_POST['fil'];
if ($branch==0){ 
	$otvet="Не выбран филиал.";
	echo $otvet;
} elseif ($branch==8){
				//Создание удаленной КНИГИ pb содержащей весь надзор ---------------группы из ip_group---------
				$otvet=fun_pb_contacts();
				echo $otvet;
}else{
//Создание удаленной КНИГИ pbN содержащей только свой филиал ------группы как в справочнике------------------------------------------------------------------------------------------------------------------------------------------
	//Устанавливаем доступы к базе данных:
		$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = 'admin'; //пароль, по умолчанию пустой
		$db_name = 'gegn'; //имя базы данных

	//Переменные
		//$branch=2; //код филиала
		$urll=$_SERVER['DOCUMENT_ROOT']."/phonebook/admin/ip_book/pbBook/"; //Путь к файлу на сервере
		$file = "pb".$branch.".xml"; // название файла
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
		$unt="    <Unit Name=\"";
		$phn="\" Phone1=\"";
		$eend="\" Phone2=\"\" Phone3=\"\" default_photo=\"Resource:\"/>";
	
	//Запрос в таблицу users с правильной сортировкой 
		$arr_users=mysqli_query($link,"
			SELECT users.filter_spr, users.name, users.fam, users.otch, users.ip_phone, users.spr_cod_otd, spr_otdel.sokr_name AS sokrNO, spr_podrazdelenie.sokr_name AS sokrNP 
			FROM users 
			INNER JOIN spr_otdel ON users.spr_cod_otd = spr_otdel.id 
			INNER JOIN spr_podrazdelenie ON users.spr_cod_podrazd = spr_podrazdelenie.id 
			WHERE ip_phone<>\"\" AND is_spr=1 AND spr_cod_branch=$branch 
			ORDER BY spr_podrazdelenie.inner_order, spr_otdel.inner_order, users.spr_cod_otd, users.filter_spr");
		//для повторного использования надо меняет указатель в массиве на 0 mysqli_data_seek($arr_users,0);

		
		$zotd=1000; //для прехода к следующему отделу
		$glav="*";	//помечаем первого по списку главным в отделе
		$nomer=1; //счетчик групп
		
		while ($row = mysqli_fetch_array($arr_users)){ //крутим весь массив
			
			if ($nomer<10) {
				$str_nomer="0".$nomer;
			}else{
				$str_nomer=$nomer;
			}
			if ($row['sokrNP']=="Управление"){   
					$row['sokrNP']="АУ";			// сокращаем название Управление до АУ
					$menuname=$mnn.$str_nomer." ".$row['sokrNO']." [".$row['sokrNP']."]"."\">\r\n"; //формируем название группы для управлений
				}else {
					$perv=mb_substr($row['sokrNP'],0,1); 										//запоминаем первую букву
					$row['sokrNP']=mb_substr($row['sokrNP'],1,3); 								//следующие буквы
					$row['sokrNP']=preg_replace('#[аеёиоуыэюя\s]+#iu', '', $row['sokrNP']); 	//удаляем все гласные буквы (i заглавные, u кодировка UTF-8)
					$row['sokrNP']=$perv.mb_substr($row['sokrNP'],0,1); 						//формируем название
					
					$perv=mb_substr($row['sokrNO'],0,1); 										//запоминаем первую букву
					$row['sokrNO']=mb_substr($row['sokrNO'],1,3); 								//следующие буквы
					$row['sokrNO']=preg_replace('#[аеёиоуыэюя\s]+#iu', '', $row['sokrNO']); 	//удаляем все гласные буквы (i заглавные, u кодировка UTF-8)
					$row['sokrNO']=$perv.mb_substr($row['sokrNO'],0,1); 						//формируем название					
					
					$menuname=$mnn.$str_nomer." ".$row['sokrNP']." МрО [".$row['sokrNO']."]"."\">\r\n"; //формируем название группы для всех МРО кроме управлений
			}
			
			
			if ($zotd<>$row['spr_cod_otd']){   // если измениться код отдела завкрываем группу
				$rez=$rez.$menuname;
				$mnn="  </Menu>\r\n"."  <Menu Name=\"";		
				$glav="*"; //помечаем первого по списку главным в отделе
				$nomer=$nomer+1; //учеличиваем счетчик групп
			}

			
			$rez=$rez.$unt.$glav.$row['fam']." ".$row['name']." ".$row['otch'].$phn.$row['ip_phone'].$eend."\r\n"; // наполняем группу сотрудниками
			$total=$total+1;
			
			$glav=""; //пока не измениться код отдела главного в отделе нет
			$zotd=$row['spr_cod_otd']; //берем код отдела 
				 
					
		} 	
		$rez=$rez."  </Menu>\r\n"."</YealinkIPPhoneBook>"; //конец формирования файла
		
	

	//Записываем результат в файл 
		file_put_contents($filed, $rez);
		

    //Загрузка на FTP. Надо влючить extension=php_ftp.dll в php.ini и рестарт апач c:\Server\bin\Apache24\bin\httpd.exe -k restart
		//перенес в переменные $ftp_host = "10.170.0.2"; 
		//перенес в переменные $ftp_url="Gosenergogaznadzor/Phonebook";
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
/*}*/			
?>
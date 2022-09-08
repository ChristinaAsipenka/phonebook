<?php
$start = microtime(true);

$branch=$_POST['fil2'];
$fio_str=$_POST['fio_str'];
if ($branch==0){ 
	$otvet="Не выбран филиал.";
	echo $otvet;
}else{
//Создание vcard------------------------------------------------------------------------------------------------------------------
	//Устанавливаем доступы к базе данных:
		$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = 'admin'; //пароль, по умолчанию пустой
		$db_name = 'gegn'; //имя базы данных

	//Переменные
		$urll=$_SERVER['DOCUMENT_ROOT']."/phonebook/admin/ip_book/vcard/"; //Путь к файлу на сервере
		$file = "vcard".$branch.".vcf"; // название файла
		if ($branch==8) $file = "vcard.vcf"; //название файла всех филиалов вместе
		$filed = $urll.$file; //Путь к файлу и название файла
		
		$ftp_host = "10.170.0.2"; 
		$ftp_url="Gosenergogaznadzor/Phonebook/vCard";
		
		$otvet="";
		$rez="";
		$total=0;
		$total_fio=0;
		$total_str1=0;
		$total_str2=0;
		$total_str3=0;
		
	//Соединяемся с базой данных используя наши доступы:
		$link=mysqli_connect($host, $user, $password, $db_name); //or die("Не могу соединиться с MySQL.");
	
	if ($fio_str==0 or $fio_str==1) { //Запрос в таблицу users (создание контактов сотрудников)
		if ($branch==8){
			$arr_users=mysqli_query($link,"SELECT fam, name, otch, dolgnost, mobile_phone, phone, email, spr_cod_branch 
									FROM users 
									WHERE (fam<>\"\" AND (mobile_phone<>\"\" or phone<>\"\") AND is_spr=1)");
		}else{
			$arr_users=mysqli_query($link,"SELECT fam, name, otch, dolgnost, mobile_phone, phone, email, spr_cod_branch 
									FROM users 
									WHERE (fam<>\"\" AND spr_cod_branch=".$branch." AND (mobile_phone<>\"\" or phone<>\"\") AND is_spr=1)");
		}
	
		while ($row = mysqli_fetch_array($arr_users)){ //крутим весь массив
		
			$rez=$rez."BEGIN:VCARD\r\nVERSION:3.0\r\n";
			$rez=$rez."FN:".$row['fam']." ".$row['name']." ".$row['otch']."\r\n";
			$rez=$rez."N:".$row['fam'].";".$row['name'].";".$row['otch']."\r\n";
			$rez=$rez."TEL;TYPE=cell, pref:".trim($row['mobile_phone'])."\r\n";
			$rez=$rez."TEL;TYPE=work:".trim($row['phone'])."\r\n";
			$rez=$rez."EMAIL;TYPE=INTERNET:".$row['email']."\r\n";
			$rez=$rez."TITLE:".$row['dolgnost']."\r\n";					
			
			//вставка фото
			//$image_data=file_get_contents('test.jpg');
			//$encoded_image=base64_encode($image_data);
			//$rez=$rez."PHOTO;ENCODING=b;TYPE=JPEG:".$encoded_image."\r\n";
			
			$rez=$rez."END:VCARD\r\n";
			$total_fio=$total_fio+1;
				
		} 	
	}
	if ($fio_str==0 or $fio_str==2){	//Запрос в таблицы структурных подразделений (создание контактов подразделений)
		//уровень 1 таблица spr_branch
		if ($branch==8){ 
			$arr_1=mysqli_query($link,"SELECT name, phone_cod, phone, fax, email, adress, id 
									FROM spr_branch 
									WHERE (name<>\"\" AND (phone<>\"\" or fax<>\"\" or email<>\"\" or adress<>\"\"))");
		}else{
			$arr_1=mysqli_query($link,"SELECT name, phone_cod, phone, fax, email, adress, id 
									FROM spr_branch 
									WHERE (name<>\"\" AND id=".$branch." AND (phone<>\"\" or fax<>\"\" or email<>\"\" or adress<>\"\"))");
		}
	
		while ($row = mysqli_fetch_array($arr_1)){ //крутим весь массив
		
			$rez=$rez."BEGIN:VCARD\r\nVERSION:3.0\r\n";
			$rez=$rez."FN:".$row['name']."\r\n";
			$rez=$rez."N:".$row['name']."\r\n";
			if ($row['phone']!=""){
			$rez=$rez."TEL;TYPE=work, pref:+375".trim($row['phone_cod']).trim($row['phone'])."\r\n";}
			if ($row['fax']!=""){
			$rez=$rez."TEL;TYPE=work, fax:+375".trim($row['phone_cod']).trim($row['fax'])."\r\n";}
			$rez=$rez."EMAIL;TYPE=INTERNET:".$row['email']."\r\n";	
			$rez=$rez."ADR;TYPE=work:;;".trim($row['adress'])."\r\n";			
			$rez=$rez."LABEL;TYPE=work:".trim($row['adress'])."\r\n";			
			$rez=$rez."END:VCARD\r\n";
			$total_str1=$total_str1+1;	
		} 
		//уровень 2 таблица spr_podrazdelenie 
		if ($branch==8){ 
			$arr_2=mysqli_query($link,"SELECT spr_podrazdelenie.sokr_name, spr_podrazdelenie.phone_cod, spr_podrazdelenie.phone, 			spr_podrazdelenie.fax, spr_podrazdelenie.email, spr_podrazdelenie.adress, spr_branch.sokr_name AS sokrNB
									FROM spr_podrazdelenie
									INNER JOIN spr_branch ON spr_podrazdelenie.cod_branch=spr_branch.id  
									WHERE (spr_podrazdelenie.name_podrazd<>\"\" AND (spr_podrazdelenie.phone<>\"\" or spr_podrazdelenie.fax<>\"\" or spr_podrazdelenie.email<>\"\" or spr_podrazdelenie.adress<>\"\"))");
		}else{
			$arr_2=mysqli_query($link,"SELECT spr_podrazdelenie.sokr_name, spr_podrazdelenie.phone_cod, spr_podrazdelenie.phone, 			spr_podrazdelenie.fax, spr_podrazdelenie.email, spr_podrazdelenie.adress, spr_branch.sokr_name AS sokrNB
									FROM spr_podrazdelenie
									INNER JOIN spr_branch ON spr_podrazdelenie.cod_branch=spr_branch.id    
									WHERE (spr_podrazdelenie.name_podrazd<>\"\" AND spr_podrazdelenie.cod_branch=".$branch." AND (spr_podrazdelenie.phone<>\"\" or spr_podrazdelenie.fax<>\"\" or spr_podrazdelenie.email<>\"\" or spr_podrazdelenie.adress<>\"\"))");
		}
	
		while ($row = mysqli_fetch_array($arr_2)){ //крутим весь массив
		
			$rez=$rez."BEGIN:VCARD\r\nVERSION:3.0\r\n";
			$rez=$rez."FN:".$row['sokrNB']." /".$row['sokr_name']."\r\n";
			$rez=$rez."N:".$row['sokrNB'].";".$row['sokr_name']."\r\n";
			if ($row['phone']!=""){
			$rez=$rez."TEL;TYPE=work, pref:+375".trim($row['phone_cod']).trim($row['phone'])."\r\n";}
			if ($row['fax']!=""){
			$rez=$rez."TEL;TYPE=work, fax:+375".trim($row['phone_cod']).trim($row['fax'])."\r\n";}
			$rez=$rez."EMAIL;TYPE=INTERNET:".$row['email']."\r\n";	
			$rez=$rez."ADR;TYPE=work:;;".trim($row['adress'])."\r\n";			
			$rez=$rez."LABEL;TYPE=work:".trim($row['adress'])."\r\n";			
			$rez=$rez."END:VCARD\r\n";
			$total_str2=$total_str2+1;	
		} 
		//уровень 3 таблица spr_otdel 
		if ($branch==8){ 
			$arr_3=mysqli_query($link,"SELECT spr_otdel.sokr_name, spr_otdel.phone_cod, spr_otdel.phone, spr_otdel.fax, spr_otdel.email, spr_otdel.adress, spr_branch.sokr_name AS sokrNB, spr_podrazdelenie.sokr_name AS sokrNP
									FROM spr_otdel
									INNER JOIN spr_branch ON spr_otdel.cod_branch=spr_branch.id
									INNER JOIN spr_podrazdelenie ON spr_otdel.cod_podch=spr_podrazdelenie.id  
									WHERE (spr_otdel.name_otdel<>\"\" AND (spr_otdel.phone<>\"\" or spr_otdel.fax<>\"\" or spr_otdel.email<>\"\" or spr_otdel.adress<>\"\"))");
		}else{
			$arr_3=mysqli_query($link,"SELECT spr_otdel.sokr_name, spr_otdel.phone_cod, spr_otdel.phone, spr_otdel.fax, spr_otdel.email, spr_otdel.adress, spr_branch.sokr_name AS sokrNB, spr_podrazdelenie.sokr_name AS sokrNP
									FROM spr_otdel
									INNER JOIN spr_branch ON spr_otdel.cod_branch=spr_branch.id
									INNER JOIN spr_podrazdelenie ON spr_otdel.cod_podch=spr_podrazdelenie.id    
									WHERE (spr_otdel.name_otdel<>\"\" AND spr_otdel.cod_branch=".$branch." AND (spr_otdel.phone<>\"\" or spr_otdel.fax<>\"\" or spr_otdel.email<>\"\" or spr_otdel.adress<>\"\"))");
		}
	
		while ($row = mysqli_fetch_array($arr_3)){ //крутим весь массив
		
			$rez=$rez."BEGIN:VCARD\r\nVERSION:3.0\r\n";
			$rez=$rez."FN:".$row['sokrNB']." /".$row['sokrNP']." /".$row['sokr_name']."\r\n";
			$rez=$rez."N:".$row['sokrNB'].";".$row['sokrNP']." ".$row['sokr_name']."\r\n";
			if ($row['phone']!=""){
			$rez=$rez."TEL;TYPE=work, pref:+375".trim($row['phone_cod']).trim($row['phone'])."\r\n";}
			if ($row['fax']!=""){
			$rez=$rez."TEL;TYPE=work, fax:+375".trim($row['phone_cod']).trim($row['fax'])."\r\n";}
			$rez=$rez."EMAIL;TYPE=INTERNET:".$row['email']."\r\n";	
			$rez=$rez."ADR;TYPE=work:;;".trim($row['adress'])."\r\n";			
			$rez=$rez."LABEL;TYPE=work:".trim($row['adress'])."\r\n";			
			$rez=$rez."END:VCARD\r\n";
			$total_str3=$total_str3+1;	
		} 
	}	
	$total=$total_fio+$total_str1+$total_str2+$total_str3;

$finish = microtime(true);	
$start2 = microtime(true);
		
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
			$otvet=$otvet."<br>"."Новый файл удаленной книги <b>".$file."</b> загружен по адресу: FTP://".$ftp_host."/".$ftp_url."/.<br>(Total contacts:".$total."=".$total_fio."+".$total_str1."+".$total_str2."+".$total_str3.", это:Персонал +Филиалы +Подразделения +Отделы).<br><br>"."<b>Операция выполнена успешно.</b>"."<br>";
			} else {
			$otvet=$otvet."Не удалось загрузить файл."."<br>"."Ошибка!"."<br>";
		}
	ftp_close($ftp);

$finish2 = microtime(true);	
$delta = mb_substr($finish - $start,0,5);
$delta2 = mb_substr($finish2 - $start2,0,5);
echo $otvet."<br>*Работа скрипта:".$delta." сек.<br>Загрузка на сервер:".$delta2." сек.";		
	
}
		
?>
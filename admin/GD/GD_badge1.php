<?php 
//скрипт формирует картинку, а именно по данным пользователя его Бэйдж страницу1 (с использованием библиотеки GD)
	session_start();
	$user_id=$_GET['id'];
	
//Устанавливаем сообщения об ошибках
	ini_set("display_errors", "1");
	error_reporting(E_ALL); 

//Устанавливаем доступы к базе данных:
	$host = 'localhost'; 	//имя хоста, на локальном компьютере это localhost
	$user = 'root'; 		//имя пользователя, по умолчанию это root
	$password = 'admin'; 		//пароль, по умолчанию пустой
	$db_name = 'gegn'; 		//имя базы данных

// создает Полотно - новое, пустое изображение заданного размера
	$width = 1000; 										// необходимая ширина фотографии для вставки
	$height = 1400; 									// необходимая высота фотографии для вставки
	$polotno = ImageCreateTrueColor($width,$height);	// создает ХОЛСТ - новое, пустое изображение заданного размера
	//imageAlphaBlending($polotno, false);				// устанавливает режим смешивания --- false - накладываемый пиксель заменяет исходный
	ImageSaveAlpha($polotno, true); 					// задаем прозрачность


	
//Данные, переменные	
	$img_bg = "bg_badge1.png";   															// исходная картинка размер 1000*1400, формат png, изображение фона
	$image = ImageCreateFromPNG($img_bg); 											// Загружаем изображения фона
	imageAlphaBlending($image, false);												// устанавливает режим смешивания --- false - накладываемый пиксель заменяет исходный
	ImageSaveAlpha($image, true); 													// задаем прозрачность
	
	$url_photo = $_SERVER['DOCUMENT_ROOT']."/phonebook/images/users_photo/";		// путь к фотографиям
	
	$fontName = $_SERVER['DOCUMENT_ROOT']."/phonebook/admin/gd/Sylfaen.ttf";					// путь к шрифту
	$white    = ImageColorallocate($polotno, 255, 255, 255); 						//задаем белый цвет для конкретного изображения
	$black    = ImageColorallocate($polotno, 0, 0, 0);								//задаем черный цвет для конкретного изображения
	$red      = ImageColorallocate($polotno, 255, 0, 0);							//задаем красный цвет для конкретного изображения
	$blue     = ImageColorallocate($polotno, 57, 78, 137);							//задаем синий цвет для конкретного изображения
	$burgundy2= ImageColorallocate($polotno, 89,30, 38);							//задаем бордовый цвет для конкретного изображения
	
//Соединяемся с базой данных используя наши доступы:
	$link=mysqli_connect($host, $user, $password, $db_name); //or die("Не могу соединиться с MySQL.");

//Запрос1 проверка на уволеного
	$arr_user_spr=mysqli_query($link,"
	SELECT users.is_spr
	FROM `users`
	WHERE users.id=".$user_id); 
	$row1 = mysqli_fetch_array($arr_user_spr);	
	
if ($row1['is_spr']==1) {	//проверка на уволеного
//Запрос2 все необходимые данные о сотруднике
	$arr_user=mysqli_query($link,"
	SELECT users.is_spr, users.fam, users.name, users.otch, users.dolgnost, users.tab_num, users.photo, users.spr_cod_branch,
		spr_branch.name AS lev1, 
		spr_podrazdelenie.name_podrazd AS lev2, 
		spr_otdel.name_otdel AS lev3
	FROM `users`
	JOIN spr_branch ON users.spr_cod_branch=spr_branch.id
	JOIN spr_podrazdelenie ON users.spr_cod_podrazd=spr_podrazdelenie.id
	JOIN spr_otdel ON users.spr_cod_otd=spr_otdel.id
	WHERE users.id=".$user_id); 
	$row = mysqli_fetch_array($arr_user);											//данные по пользователю в массиве
	


// ---------- Работа с Фотографией--------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	// Изменяем размер картинки. Пропорционально увеличивать или уменьшать. 
	// Если новый размер картинки будет задан не пропорционально, то холст создастся по новым размерам, а картинка изменится до максимально допустимого, без потери пропорций.
	
	if ($row['photo']==null) {$row['photo']="no-photo.png";} //проверка на наличие фото
		
	$source = $url_photo.$row['photo'];		// путь к фотографии
	$size = getimagesize($source); 			// узнаем размеры исходной картинки: $size[0]-ширина, $size[1]-высота, $size[2]-тип формат.
											// по типу можно определить формат картинки (1 - это тип gif, 2 - jpeg, 3 - PNG)
	
	// в зависимости от формата загружаем исходную картинку
	$extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));
	switch ($extension) {
    case 'jpg':
    case 'jpeg':
		$photo = ImageCreateFromJPEG($source);		// загружаем исходную картинку – создает изображение из файла, работает только с одним форматом
		break;
    case 'gif':
		$photo = ImageCreateFromGIF($source);
		break;
    case 'png':
       $photo = ImageCreateFromPNG($source);
    break;
	}
	
	// создает ХОЛСТ - новое, пустое изображение заданного размера
	$width = 354; 									// необходимая ширина фотографии для вставки
	$height = 472; 									// необходимая высота фотографии для вставки
	$img = ImageCreateTrueColor($width,$height);	// создает ХОЛСТ - новое, пустое изображение заданного размера
	imageAlphaBlending($img, false);				// устанавливает режим смешивания --- false - накладываемый пиксель заменяет исходный
	ImageSaveAlpha($img, true); 					// задаем прозрачность
	$rgb = 0xffffff; 								// цвет заливки фона белый	
	ImageFill($img, 0, 0, $rgb); 					// заливаем холст цветом $rgb – осуществляет заливку заданным цветом. В данном примере заливается все изображение.

	//------------Пропорции для преобразования фото------------------
	$x_ratio = $width / $size[0]; 					//пропорция ширины
	$y_ratio = $height / $size[1]; 					//пропорция высоты

	$ratio = min($x_ratio, $y_ratio);				// определяем соотношения ширины к высоте - находит минимальное значение, из сравниваемых
	$use_x_ratio = ($x_ratio == $ratio); 
	$new_width   = $use_x_ratio  ? $width : floor($size[0] * $ratio); 		// высчитываем новую ширину картинки
	$new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio); 		// высчитываем новую высоту картинки
	$new_left    = $use_x_ratio  ? 0 : 	floor(($width - $new_width) / 2); 	// расхождение с заданными параметрами по ширине
	$new_top     = !$use_x_ratio ? 0 : 	floor(($height - $new_height) / 2); // расхождение с заданными параметрами по высоте
	//------------конец пропорции------------------

	//$rgb = imagecolorat($photo, 15, 15); 				// цвет заливки фона белый	 ---- берем индекс цвета пиксела на заданных координатах на изображении.
	//ImageFill($polotno, 0, 0, $rgb); 					// заливаем холст цветом $rgb – осуществляет заливку заданным цветом. В данном примере заливается все изображение.
	
	// копируем на холст сжатую картинку с учетом расхождений – копирует одно изображение на другое, с возможностью смещения и изменения размера копируемого. 
	// В данном случае, копируемое изображение меняет размер и центруется, относительно пустого изображения.
	ImageCopyResampled($img, $photo, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
	
// Копируем на Полотно поочереди изображения чтобы залить бока круга(x = 735;y = 645 это координаты  центра круга на изображении)
for($i=1;$i<=10;$i++) {
	$k=(0.4/10)*(11-$i)+1;
	
	// вичисляем размеры и координаты и учеличиваем до максимального по ширине и т.д. все меньше и меньше
	$w_o=$width;										
	$h_o=$height;
	$w_o=$width*$k;
	$h_o=$height*$k;
	$x = 735-($w_o/2);	$y = 645-($h_o/2);
	$img_o = imagecreatetruecolor($w_o, $h_o); 									// Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $width, $height); 	// Переносим изображение из исходного в выходное, масштабируя его
	if ($i<10) {imagefilter($img_o, IMG_FILTER_SMOOTH, -5);}		// кроме последнего, границы более плавными, а ИЗО менее четким. Диапазон значений не ограничен, но наиболее заметные изменения происходят от 0 до -8.
	ImageCopy($polotno, $img_o, $x, $y, 0, 0, $w_o, $h_o);	
}	
						
	$x = 0;	$y = 0;													// координаты верхнего левого угла накладываемой картинки
	ImageCopy($polotno, $image, $x, $y, 0, 0, 1000, 1400);			// изо фона с прозрачным вырезом круга	
	
	// очищаем память после выполнения скрипта
	ImageDestroy($photo);	
// ---------- конец Работа с фотографией--------------------------------------------------------------------------------------------------------------------------------------------------------------------	


// ---------- Работа с текстом --------------------------------------------------------------------------------------------------------------------------------------------------------------------	
		
//ФИО	
	$fontSise = 50; 			// размер шрифта ---- самый большой шрифт
	$x = 50; $y = 1050;			// координаты
	$text_F = $row['fam']; 		// Фамилия ----------- "Текст кир\nиллица";  \n обозначает переход на новую строку	
	$text_I = $row['name'];
	$text_O = $row['otch'];
	$text = $text_F." ".$text_I." ".$text_O;
	//$text="Wwwwtssss0Wwwwtssss0Wwwwtssss0Wwwwtssss0";
	if (mb_strlen($text)>=27) { $fontSise = 45;}   //если длинна текста больше чем 28 уменьшаем размер шрифта
	if (mb_strlen($text)>31) { $fontSise = 40;}
	if (mb_strlen($text)>34) { $fontSise = 36;}
	if (mb_strlen($text)>39) { $fontSise = 32;}
	if (mb_strlen($text)>42) { $fontSise = 30;}
	// само нанесение текста
	Imagettftext($polotno, $fontSise, 0, $x, $y, $burgundy2, $fontName, $text);

//должность	
	$fontSise = 31; 					
	$x = 50; $y = 875;											
	$text = trim($row['dolgnost']);  //если больше 33
	if (mb_strlen($text)>=15) { 
		$pos=mb_strpos($text," ",15);						// поиск пробела не с начала строки, используем третий параметр 
		if ($pos==0 And mb_strlen($text)<33){				// если не нашел пробелов и строка не длинная (помещается), то нужно взять всю строку
			$pos=mb_strlen($text);
		} 
		if ($pos==0 And mb_strlen($text)>=33){				// если не нашел пробелов но строка длинная (не помещается), то нужно разбивать раньше (~после первого пробела)
			$pos=mb_strpos($text," ",12);					// поиск пробела не с начала строки, используем третий параметр 
		}
		$text=mb_substr($text, 0, $pos,'UTF-8')."\n".mb_substr($text, $pos+1, mb_strlen($text),'UTF-8');
	}
	Imagettftext($polotno, $fontSise, 0, $x, $y, $burgundy2, $fontName, $text);
	
//табельный 
	$fontSise = 26; 			
	$x = 50; $y = 700;										
	$text = trim("№".$row['spr_cod_branch']."-".$row['tab_num']); 
	Imagettftext($polotno, $fontSise, 0, $x, $y, $white, $fontName, $text);	

$branch=$row['spr_cod_branch'];	
//филиал пордразделение отдел на белой полосе
	$fontSise = 30; 
	$x = 50;
	$y = 1160;

	$text1 = trim($row['lev1']);
	$text2 = (trim($row['lev2'])); 
	$text3 = (trim($row['lev3'])); 
	
	$len1=mb_strlen($text1);  
	$len2=mb_strlen($text2);
	$len3=mb_strlen($text3);
	
	//расчет размера шрифта строки1
	$fontSise1 = 24; 									// размер шрифта ---- самый маленький шрифт
	if (mb_strlen($text1)<=49) { $fontSise1 = 29; }   	//если длинна текста больше чем 28 уменьшаем размер шрифта
	if (mb_strlen($text1)<=47) { $fontSise1 = 28; }

	//расчет размера шрифта строки2
	$fontSise2 = 22; 									// размер шрифта ---- самый маленький шрифт
	if (mb_strlen($text2)<45) { $fontSise2 = 24;  }   	//если длинна текста больше чем 28 уменьшаем размер шрифта
	if (mb_strlen($text2)<42) { $fontSise2 = 26;  }
	if (mb_strlen($text2)<39) { $fontSise2 = 28;  }
	if (mb_strlen($text2)<36) { $fontSise2 = 30;  }
	if (mb_strlen($text2)<33) { $fontSise2 = 32;  }
	
	//расчет размера шрифта строки3
	$fontSise3 = 24; 									// размер шрифта ---- самый маленький шрифт
	if (mb_strlen($text3)<45) { $fontSise3 = 27;  }  	//если длинна текста больше чем 28 уменьшаем размер шрифта
	if (mb_strlen($text3)<42) { $fontSise3 = 30;  }
	if (mb_strlen($text3)<39) { $fontSise3 = 33;  }
	if (mb_strlen($text3)<36) { $fontSise3 = 36;  }
	if (mb_strlen($text3)<33) { $fontSise3 = 39;  }
	
	if ($branch==7) {$y = 1130; $text1 = trim("");}  											//исключение для управления
	$fontSiseMIN=min($fontSise2,$fontSise3); $fontSise2=$fontSiseMIN; $fontSise3=$fontSiseMIN;	//выбор минимального шрифта из 2 и 3
	
	Imagettftext($polotno, $fontSise1, 0, $x, $y, $burgundy2, $fontName, $text1);
	$y = $y +50;
	Imagettftext($polotno, $fontSise2, 0, $x, $y, $burgundy2, $fontName, $text2);
	$y = $y +50;
	Imagettftext($polotno, $fontSise3, 0, $x, $y, $burgundy2, $fontName, $text3);
	
}else{ //уволенный сотрудник
	$fontSise = 36; 			
	$x = 400;	$y = 350; 					
	$text = mb_strtoupper(trim("Сотрудник \nуволен")); 	
	Imagettftext($polotno, $fontSise, 0, $x, $y, $red, $fontName, $text);
}	
//вывод получившегося изображения
	//header('Content-Type: image/png');			// задаем заголовок, чтоб вывести результат в браузере
	header('Content-Disposition: Attachment;filename=' . basename("badge1_id".$user_id.".png")); //Чтобы браузер отдал фото на скачивание
	// выводим картинку
	ImagePNG($polotno);
	
	// очищаем память после выполнения скрипта
	ImageDestroy($image);			
	ImageDestroy($polotno);
	ImageDestroy($img);
  	ImageDestroy($img_o);

  ?>
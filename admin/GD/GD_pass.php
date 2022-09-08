<?php 
//скрипт формирует картинку, а именно по данным пользователя его ПРОПУСК (с использованием библиотеки GD)
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
	
//Данные, переменные	

	$img_bg = "bg_pass.png";   														// исходная картинка размер 1000*700, формат png, изображение фона
	$image = ImageCreateFromPNG($img_bg); 											// Загружаем изображения фона
	ImageSaveAlpha($image, true); 													// задаем прозрачность
	
	$url_photo = $_SERVER['DOCUMENT_ROOT']."/phonebook/images/users_photo/";		// путь к фотографиям
	
	$fontName = $_SERVER['DOCUMENT_ROOT']."/phonebook/admin/gd/sitka-small.ttf";				// путь к шрифту
	$white    = ImageColorallocate($image, 255, 255, 255); 							//задаем белый цвет для конкретного изображения
	$black    = ImageColorallocate($image, 0, 0, 0);								//задаем черный цвет для конкретного изображения
	$red      = ImageColorallocate($image, 255, 0, 0);								//задаем красный цвет для конкретного изображения
	$blue     = ImageColorallocate($image, 57, 78, 137);							//задаем синий цвет для конкретного изображения
	$burgundy = ImageColorallocate($image, 120, 32, 6);								//задаем бордовый цвет для конкретного изображения
	
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
	$width = 285; 									// необходимая ширина фотографии для вставки
	$height = 380; 									// необходимая высота фотографии для вставки
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


	// копируем на холст сжатую картинку с учетом расхождений – копирует одно изображение на другое, с возможностью смещения и изменения размера копируемого. 
	// В данном случае, копируемое изображение меняет размер и центруется, относительно пустого изображения.
	ImageCopyResampled($img, $photo, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
	
	$x = 17;	$y = 231;		// координаты верхнего левого угла накладываемой картинки
	
	// Копируем на фон полученый ХОЛСТ (преобразованое фото)
	ImageCopy($image, $img, $x, $y, 0, 0, $width, $height);

	// очищаем память после выполнения скрипта
	ImageDestroy($img);
	ImageDestroy($photo);	
// ---------- конец Работа с фотографией--------------------------------------------------------------------------------------------------------------------------------------------------------------------	

//ФИО	
	$fontSise = 40; 			// размер шрифта
	$x = 320; $y = 265;			// координаты
	$text_F = $row['fam']; 		// Фамилия ----------- "Текст кир\nиллица";  \n обозначает переход на новую строку	
	$text_I = $row['name'];
	$text_O = $row['otch'];
	$text = $text_F."\n".$text_I."\n".$text_O;
	// само нанесение текста
	Imagettftext($image, $fontSise, 0, $x, $y, $burgundy, $fontName, $text);

//должность	
	$fontSise = 21; 			
	$x = 320; $y = 500;											
	$text = trim($row['dolgnost']);  //если больше 33
	if (mb_strlen($text)>=25) { 
		$y = 480;
		$pos=mb_strpos($text," ",25);						// поиск пробела не с начала строки, используем третий параметр 
		if ($pos==0 And mb_strlen($text)<33){				// если не нашел пробелов и строка не длинная (помещается), то нужно взять всю строку
			$pos=mb_strlen($text);
			$y = 500;
		} 
		if ($pos==0 And mb_strlen($text)>=33){				// если не нашел пробелов но строка длинная (не помещается), то нужно разбивать раньше (~после первого пробела)
			$pos=mb_strpos($text," ",15);					// поиск пробела не с начала строки, используем третий параметр 
		}
		$text=mb_substr($text, 0, $pos,'UTF-8')."\n".mb_substr($text, $pos+1, mb_strlen($text),'UTF-8');
	}
	Imagettftext($image, $fontSise, 0, $x, $y, $blue, $fontName, $text);
	
//табельный и дата
	$fontSise = 24; 			
	$x = 320; $y = 600;										
	$text = trim("№".$row['spr_cod_branch']."-".$row['tab_num']); 
	Imagettftext($image, $fontSise, 0, $x, $y, $burgundy, $fontName, $text);	
	$x = 700; $y = 600; 					
	$text = date("d.m.Y");
	Imagettftext($image, $fontSise, 0, $x, $y, $burgundy, $fontName, $text);

	$branch=$row['spr_cod_branch'];
//управление или филиал Заглавие 
	if ($branch==7) {
		//управление ---------- прописываем "Госэнергогазнадзор"
		$fontSise = 36; 			
		$x = 240;	$y = 135; 					
		$text = mb_strtoupper(trim("Госэнергогазнадзор")); 	
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, $text);
	}elseif ($branch==6){
		//минск ---------- прописываем название с разбиением на две строки и изменением координат
		$fontSise = 21; 			
		$x = 350;	$y = 110; 					
		$text = mb_strtoupper(trim($row['lev1'])); 	
		$pos=mb_strpos($text," ",10);				// поиск пробела не с начала строки, используем третий параметр
		if ($pos==0){$pos=mb_strlen($text);}		//если не нашел пробелов, то нужно взять всю строку
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, mb_substr($text, 0, $pos,'UTF-8'));
		$x = 300;	$y = 150; 
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, mb_substr($text, $pos+1, mb_strlen($text),'UTF-8'));
	} else{
		//остальные ---------- прописываем название с разбиением на две строки и изменением координат
		$fontSise = 21; 			
		$x = 350;	$y = 110; 					
		$text = mb_strtoupper(trim($row['lev1'])); 	
		$pos=mb_strpos($text," ",10);				// поиск пробела не с начала строки, используем третий параметр
		if ($pos==0){$pos=mb_strlen($text);}		//если не нашел пробелов, то нужно взять всю строку
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, mb_substr($text, 0, $pos,'UTF-8'));
		$x = 420;	$y = 150; 
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, mb_substr($text, $pos+1, mb_strlen($text),'UTF-8'));
	}	
//нижняя строка
	if ($branch==7) {
		//управление
		$fontSise = 18; 			
		$x = 13;	$y = 670;
		$text = trim($row['lev2']); 
		//if (mb_strlen($text)<35) { $x=320;} 					//двигаем текст правее если не длинная строка
		if (mb_strlen($text)>=55) { 
			$y = 655; 
			$pos=mb_strpos($text," ",40);						// поиск пробела не с начала строки, используем третий параметр 
			if ($pos==0){$pos=mb_strlen($text);$y = 670;}		//если не нашел пробелов, то нужно взять всю строку
			$text=mb_substr($text, 0, $pos,'UTF-8')."\n".mb_substr($text, $pos+1, mb_strlen($text),'UTF-8');
		}
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, $text);
	}else{
		//минск
		$fontSise = 16; 			
		$x = 13;	$y = 655; 						
		$text1=trim($row['lev2']);
		if (mb_strlen($text1)>=55){$fontSise = 17;}
		$text2=trim($row['lev3']);
		//if (mb_strlen($text1)<35 and mb_strlen($text2)<35) { $x=320;} 	//двигаем текст правее если не длинная строка
		$text = $text1."\n".$text2; 	
		Imagettftext($image, $fontSise, 0, $x, $y, $white, $fontName, $text);
	}

}else{ //уволенный сотрудник
	$fontSise = 36; 			
	$x = 400;	$y = 350; 					
	$text = mb_strtoupper(trim("Сотрудник \nуволен")); 	
	Imagettftext($image, $fontSise, 0, $x, $y, $red, $fontName, $text);
}	
//вывод получившегося изображения
	//header('Content-Type: image/png');			// задаем заголовок, чтоб вывести результат в браузере
	header('Content-Disposition: Attachment;filename=' . basename("pass_id".$user_id.".png"));
	ImagePNG($image);							// выводим картинку
	ImageDestroy($image);						// очищаем память после выполнения скрипта
	
  
  ?>
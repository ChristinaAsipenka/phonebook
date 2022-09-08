<?php 
//скрипт формирует картинку, а именно по данным пользователя его Бэйдж страницу2 (с использованием библиотеки GD и QR)
	session_start();
	$user_id=$_GET['id'];

	include $_SERVER['DOCUMENT_ROOT']."/phonebook/admin/gd/phpqrcode/qrlib.php";		// подключаем библиотеку QR
	
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
	$img_bg = "bg_badge2.png";   														// исходная картинка размер 1000*1400, формат png, изображение фона
	$polotno = ImageCreateFromPNG($img_bg); 											// Загружаем изображения фона
	imageAlphaBlending($polotno, false);												// устанавливает режим смешивания --- false - накладываемый пиксель заменяет исходный
	ImageSaveAlpha($polotno, true); 													// задаем прозрачность
	
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
	SELECT users.is_spr, users.fam, users.name, users.otch, users.dolgnost, users.tab_num, users.email, users.phone, users.mobile_phone,
		spr_branch.name AS lev1, users.spr_cod_branch,
		spr_podrazdelenie.name_podrazd AS lev2, 
		spr_otdel.name_otdel AS lev3
	FROM `users`
	JOIN spr_branch ON users.spr_cod_branch=spr_branch.id
	JOIN spr_podrazdelenie ON users.spr_cod_podrazd=spr_podrazdelenie.id
	JOIN spr_otdel ON users.spr_cod_otd=spr_otdel.id
	WHERE users.id=".$user_id); 
	$row = mysqli_fetch_array($arr_user);											//данные по пользователю в массиве
	
// ---------- Работа с текстом --------------------------------------------------------------------------------------------------------------------------------------------------------------------			
//ФИО	
	$fontSise = 56; 			// размер шрифта ---- самый большой шрифт
	$x = 50; $y = 1175;			// координаты
	$text_F = trim($row['fam']); 		// Фамилия ----------- "Текст кир\nиллица";  \n обозначает переход на новую строку	
	$text_I = trim($row['name']);
	$text_O = trim($row['otch']);
	$name = $text_F. " ".$text_I." ".$text_O;
	$name2 = $text_F. ";".$text_I.";".$text_O;
	$text = $text_F."\n".$text_I." ".$text_O;
	// само нанесение текста
	Imagettftext($polotno, $fontSise, 0, $x, $y, $burgundy2, $fontName, $text);

//должность	
	$fontSise = 31; 					
	$x = 50; $y = 875;											
	$text = trim($row['dolgnost']);  
	$dolg =$text;
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
	
//прочие данные
	$phone1 = trim($row['mobile_phone']);
	$phone2 = trim($row['phone']);
	$email = trim($row['email']);
	$org = trim($row['lev1']);

	
// ---------- Работа с QR кодом --------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	// Добавление нового контакта из QR
	$text  = 'BEGIN:VCARD' . "\n" . "VERSION:3.0" . "\n";
	$text .= 'FN:' . $name . "\n";
	$text .= 'N:' . $name2 . "\n";
	$text .= 'TEL;TYPE=Cell:' . $phone1 . "\n";
	$text .= 'TEL;TYPE=WORK:' . $phone2 . "\n";
	$text .= 'EMAIL;TYPE=INTERNET:' . $email . "\n";
	$text .= 'TITLE:' . $dolg . "\n";
	$text .= 'ORG:' . $org . "\n";
	$text .= 'URL:' . "http://gosenergogaznadzor.by" . "\n";
	$text .= 'END:VCARD'; 
	
	QRcode::png($text, "tmpQR.png", "Q", 20, 5);  // Создаем и сохраняем QR код с текстом
	//сохранить в файл QRcode::png("http://www.ruseller.com", false, "L", 4, 4,false);	
	
	$qr_im = imagecreatefrompng('tmpQR.png');
	$width = imagesx($qr_im);
	$height = imagesy($qr_im);
	
	// меняем цвет пикселей QRкода
	$fg_color = imageColorAllocate($qr_im, 89,30, 38);
	for ($x = 0; $x < $width; $x++) {
		for ($y = 0; $y < $height; $y++) {
			$color = imagecolorat($qr_im, $x, $y);
			if ($color == 1) {
				imageSetPixel($qr_im, $x, $y, $fg_color);
			}
		}
	}
// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	
	// создает ХОЛСТ - новое, пустое изображение заданного размера
	$size = getimagesize('tmpQR.png'); 			// узнаем размеры исходной картинки: $size[0]-ширина, $size[1]-высота, $size[2]-тип формат.
	$width = 420; 									// необходимая ширина фотографии для вставки
	$height = 420; 									// необходимая высота фотографии для вставки
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
	ImageCopyResampled($img, $qr_im, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
	
	ImageCopy($polotno, $img, 550, 415, 0, 0, 420, 420);			// на полотно накладываем QRкод
	
}else{ //уволенный сотрудник
	$fontSise = 36; 			
	$x = 400;	$y = 350; 					
	$text = mb_strtoupper(trim("Сотрудник \nуволен")); 	
	Imagettftext($polotno, $fontSise, 0, $x, $y, $red, $fontName, $text);
}	
//вывод получившегося изображения
	//header('Content-Type: image/png');			// задаем заголовок, чтоб вывести результат в браузере
	header('Content-Disposition: Attachment;filename=' . basename("badge2_id".$user_id.".png")); //Чтобы браузер отдал фото на скачивание
	// выводим картинку
	ImagePNG($polotno);
	
	// очищаем память после выполнения скрипта			
	ImageDestroy($polotno);
	ImageDestroy($qr_im);
	ImageDestroy($img);

  ?>
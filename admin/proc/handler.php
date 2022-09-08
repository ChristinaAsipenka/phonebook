<?php 
// Проверяем установлен ли массив файлов и массив с переданными данными
if(isset($_FILES) && isset($_FILES['image'])) {
 
  //Переданный массив сохраняем в переменной
  $image = $_FILES['image'];
 
  // Проверяем размер файла и если он превышает заданный размер
  // завершаем выполнение скрипта и выводим ошибку
  if ($image['size'] > 2000000) {
    die('error');
  }
 
  // Достаем формат изображения
  $imageFormat = explode('.', $image['name']);
  /*$imageFormat = $imageFormat[1];*/
  $imageFormat = end($imageFormat);
 
  // Генерируем новое имя для изображения. Можно сохранить и со старым
  // но это не рекомендуется делать
  $newFileName = hash('crc32',time());
  $imageFullName = $_SERVER['DOCUMENT_ROOT'].'/phonebook/images/users_photo/' . $newFileName . '.' . $imageFormat;
 
  // Сохраняем тип изображения в переменную
  $imageType = $image['type'];
 
  // Сверяем доступные форматы изображений, если изображение соответствует,
  // копируем изображение в папку images
  if ($imageType == 'image/jpeg' || $imageType == 'image/png') {
    if (move_uploaded_file($image['tmp_name'],$imageFullName)) {
     // echo 'success';

     echo $newFileName.'.'.$imageFormat;
    } else {
      echo 'error';
    }
  }
}
?>
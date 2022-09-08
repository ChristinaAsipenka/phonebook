$(document).ready(function () {
 
  function readImage ( input ) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
 
      reader.onload = function (e) {
        $('#preview').attr('src', e.target.result);
      }
 
      reader.readAsDataURL(input.files[0]);
    }
  }
 
  function printMessage(destination, msg) {
 
    $(destination).removeClass();
 
    if (msg == 'success') {
      $(destination).addClass('alert alert-success').text('Файл успешно загружен.');
    }
 
    if (msg == 'error') {
      $(destination).addClass('alert alert-danger').text('Произошла ошибка при загрузке файла.');
    }
 
  }
 
  $('#image').change(function(){
    readImage(this);
  });
 
  $('#upload-image').on('submit',(function(e) {
    e.preventDefault();
 
    var formData = new FormData(this);

    $.ajax({
      type:'POST', // Тип запроса
      url: 'proc/handler.php', // Скрипт обработчика
      data: formData, // Данные которые мы передаем
      cache:false, // В запросах POST отключено по умолчанию, но перестрахуемся
      contentType: false, // Тип кодирования данных мы задали в форме, это отключим
      processData: false, // Отключаем, так как передаем файл
      success:function(data){
       // printMessage('#result', data);
		
	//	$("#main_modal_form input[name='photo']").val(data);
		console.log(data);
      },
    //  error:function(data){
       console.log(data);
      }
    });
  }));
});
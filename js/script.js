$(document).ready(function(){
/*********** Меню ********************/	

	$('.branch li').hover(function(){
		$(this).children('.podrazdelenie').fadeIn();
	},function(){
		$(this).children('.podrazdelenie').fadeOut();
	});
	
	$('.podrazdelenie li').hover(function(){
		$(this).children('.menu_otd').fadeIn();
	},function(){
		$(this).children('.menu_otd').fadeOut();
	});
	
	

/*********** END Меню ********************/
 $('.print_word').click(function(){
	$("#openModal").fadeIn(300);
	$("#overlay").fadeIn(300);
	$(".modalDialogPrint #id_all").prop('checked',1);
	$("div.submit_print").show();
	$("div.spinner").hide();
	$(".WordDownload").css({'display':'none'});
	return false;
 });	

/*****************************************/
	$('.show_modal_new').click(function(){
		$("#openModal").attr('modal_mode','new_person');
	});
	
	 $('.show_modal').click(function(){
		$("#openModal").fadeIn(300);
		$("#overlay").fadeIn(300);
		
		if($("#openModal").attr('modal_mode') == 'edit_person'){
			var parent_block = $(this).parent();
			var cod_user = $(parent_block).attr('cod_user');
			var person_photo_src = $(parent_block).find('.person-photo img').attr('src').trim();
			var person_photo = person_photo_src.substr(19,person_photo_src.length);
			
			var fam = $(parent_block).find('.fam').text().trim();
			var name = $(parent_block).find('span.name').text().trim();
			var otch =  $(parent_block).find('.otch').text().trim();
			var pass = $(parent_block).find('.pass').text().trim();
			
			var cod_branch = $(parent_block).find('.cod_branch').text().trim();
			var cod_podr = $(parent_block).find('.otd').attr('cod_podr');
			var cod_otd = $(parent_block).find('.distric').attr('cod_otd');
			
			var dolgnost = $(parent_block).find('.position').text().trim();
			var email = $(parent_block).find('.e-mail').text().trim();
			var phone = $(parent_block).find('.phone').text().trim();
			var mobile_phone = $(parent_block).find('.mobile_phone').text().trim();
			var ip_phone = $(parent_block).find('.ip_phone').text().trim();
			var rup_phone = $(parent_block).find('.rup_phone').text().trim();
			var branch_phone = $(parent_block).find('.branch_phone').text().trim();
			
		//	console.log(person_photo);
			
			$('#cod_user').val(cod_user);
			$('#preview').attr('src', person_photo_src);
			$('#photo').val(person_photo);
			
			$('#fam').val(fam);
			$('#name').val(name);
			$('#otch').val(otch);
			$('#otch').val(otch);
			$('#pass').val(pass);
			
			$('#cod_podr option[value="'+cod_podr+'"]').prop('selected',true);
			$('#cod_otd option[value="'+cod_otd+'"]').prop('selected',true);
			$('#cod_branch option[value="'+cod_branch+'"]').prop('selected',true);
			
			$('#dolgnost').val(dolgnost);
			$('#email').val(email);
			$('#phone').val(phone);
			$('#mobile_phone').val(mobile_phone);
			$('#ip_phone').val(ip_phone);
			$('#rup_phone').val(rup_phone);
			$('#branch_phone').val(branch_phone);
			
		};
		
		return false;
	 });
	 
	  $(".form").submit(function(event){
		  event.preventDefault();
		// console.log($(".form").serialize());
		if($("#openModal").attr('modal_mode') == 'edit_person'){
			var my_url = "edit.php";
		}else{
			 var my_url = "new.php";
		};
		  str_url = window.location.href.indexOf('#');
		  position_url = window.location.href.substr(str_url,window.location.href.length )
		// console.log(position_url);
		  href_url = 'index.php';
		  console.log(href_url);
		  $.ajax({
					type: "POST",
					cache: false,
					url: my_url,
					data: $(".form").serialize(),
					success: function(data) {
						//window.location.href = href_url;
						window.location.reload() // переделать !!!!!
						//console.log(data);
							}
				});
				//closeModalWindow();
	  });
	  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////	  
	
	/*кнопка сформировать*/ 
	$(".submit_print").click(function(){
		$("div.submit_print").hide();
		$("div.spinner").show();
		/*$(".modalDialogPrint input[type='checkbox']").prop('disabled', true);*/
		/*$(".ModalWindowReport").css({'cursor': 'none'});*/
		/*$(".fieldset_info").prop('disabled', false);*/
	});  
	   
	/////   Печать справочника
	$(".PrintForm").submit(function(event){
		  event.preventDefault();
		  var my_url = "../phonebook/printword.php";
		  $.ajax({
				type: "POST",
				cache: false,
				url: my_url,
				data: $(".PrintForm").serialize(),
				success: function(data) {	
					console.log(data);
					/*$("div.submit_print").hide();*/
					$("div.spinner").hide();
					$(".WordDownload").show();
					$(".WordDownload").css({'display': 'block'});
					$(".WordDownload").attr("href", data);
				}
			});
			$(".fieldset_info").prop('disabled', true);
	});
	
	
	/*все чекбоксы кроме id_all*/
	$(".modalDialogPrint input[type='checkbox']").not(".modalDialogPrint #id_all").click(function(){
		$(".modalDialogPrint #id_all").prop('checked',0);
		$(".WordDownload").css({'display':'none'});
		$("div.submit_print").show();
	});
	
	/*чекбокс id_all*/
	$(".modalDialogPrint #id_all").click(function(){
		$(".modalDialogPrint input[type='checkbox']").val(0);	
		$(".modalDialogPrint input").prop('checked',0);
		$(".modalDialogPrint #id_all").prop('checked',1);
		$(".WordDownload").css({'display':'none'});
		$("div.submit_print").show();
	});
 
	/*кнопка скачать*/ 
	$(".WordDownload").click(function(){
		$(".modalDialogPrint input[type='checkbox']").val(0);
		$(".modalDialogPrint input").prop('checked',0);
		$(".WordDownload").css({'display':'none'});
		$("div.submit_print").show();
		$(".fieldset_info").prop('disabled', false);
		closeModalWindow();
	});
	 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////	  
	 $(".close").click(function(){
		$(".modalDialogPrint input[type='checkbox']").val(0);
		$(".modalDialogPrint input").prop('checked',0);
		$(".fieldset_info").prop('disabled', false);
		/*$(".WordDownload").css({'display':'none'});
		$("div.submit_print").show();*/
		closeModalWindow();
	 });
	 
	 function closeModalWindow(){
		 $('#preview').attr('src','');
		 $(".modalDialog input[type='text']").val('');
		$('#cod_podr option[value="0"]').prop('selected',true);
		$('#cod_otd option[value="0"]').prop('selected',true);
		$('#cod_branch option[value="0"]').prop('selected',true);
		$("#openModal").attr('modal_mode','edit_person');
		$("#openModal").fadeOut(400);
		$("#overlay").fadeOut(400);
		
		
		
		return false; 
	 };
	 $("#cod_branch").change(function(){
		$('#cod_podr option').show();
		$('#cod_podr').val(0);
		$('#cod_otd').val(0);
		$('#cod_otd option').hide();
		$('#cod_podr option').not("[cod_branch = "+$(this).val()+"]").hide();
	 });
	 
	 
	 $("#cod_podr").change(function(){
		$('#cod_otd option').show();
		$('#cod_otd').val(0);
		$('#cod_otd option').not("[cod_podch = "+$(this).val()+"]").hide();
	 });
	 
/********************************************** живой поиск ****************************************************************************/		
		$("#search").keyup(function(){
		_this = this;
		
		$.each($(".block1 .person"), function() {
		//console.log($(this).parent());	
		
        if($(this).find('.name, .position, .fam, .otch, .phone1, .email').text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {

            $(this).hide();

        } else {
		
            $(this).show(); 
			
		};
		
		
		
        });
		
	
	/*$.each($(".block_otd"), function() {
	
        if($(this).find("h3").text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
		//	 $(this).hide();
		//	$(this).parent().hide();
          
        } else {
            $(this).find(".person").show();
				
		}			
        });*/
		
		/*$.each($(".block1 .person, .block_otd"), function() {
			
			console.log('here');
			var my_block = '';
			
			if($(this).find('.name, .position, .fam, .otch, .phone1, .email').text().toLowerCase().indexOf($(_this).val().toLowerCase()) != -1){
				my_block = 'block_person';
			}else if($(this).find("h3").text().toLowerCase().indexOf($(_this).val().toLowerCase()) != -1){
				my_block = 'block_otd';
			}else{
				my_block = '';
			}
			
		switch($(this)){
		case 'block_person':
		console.log($(this).find('.name').text());
			 if($(this).find('.name, .position, .fam, .otch, .phone1, .email').text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
				$(this).hide();
				} else {
				$(this).show(); 
				}			
    
			break;
		case $(this).find("h3"):
		console.log($(this).find("h3").text());
			if($(this).find("h3").text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
			// $(this).hide();
			} else {
            $(this).find(".person").show();
				
			}			
        
			break;
		
		default:
			break;
	}
			
			
			
		});*/
		
		
    });
	 

	 
	 
 })
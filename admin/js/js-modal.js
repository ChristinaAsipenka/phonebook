$(document).ready(function(){

	$("input[name='fam']").change(function(){
		$("input[name='login']").val($(this).val());
		$("input[name='fio']").val($(this).val());
	})


	$("input[name='name'], input[name='otch']").change(function(){
		var fio = $("input[name='fam']").val()+" "+$("input[name='name']").val().substr(0,1)+"."+$("input[name='otch']").val().substr(0,1)+".";
		$("input[name='login']").val(fio);
		$("input[name='fio']").val(fio);
	})


	$("input[name='tab_num']").change(function(){
		$("input[name='password']").val($(this).val());

	})
	$("input[name='admin'], input[name='spr_admin'] ").click(function(){
		$(this).val(($(this).prop('checked') != false)? 1 : 0);
	})

	if($("input[name='admin']").is(':checked')){
		
		$("input[name='admin']").prop('value',1);
	}

	if($(" input[name='spr_admin'] ").is(':checked')){
		
		$("input[name='spr_admin'] ").prop('value',1);
	}
	
	
	$("select option").show();
	$("select[name='spr_cod_podrazd'] option").not("[cod_branch = "+$("select[name='spr_cod_branch']").val()+"]").hide();		
	$("select[name='spr_cod_otd'] option").not("[cod_podch = "+$("select[name='spr_cod_podrazd']").val()+"]").hide();	
	
	$("select[name='cod_branch']").change(function(){
		$("select[name='cod_podch']").val('0');
		$("select[name='cod_podch'] option").show();
		$("select[name='cod_podch'] option").not("[cod_branch = "+$(this).val()+"]").hide();
	})
	$("select[name='spr_cod_branch']").change(function(){
			//console.log(branch);
			$("select[name='spr_cod_podrazd']").val('0');
			$("select[name='spr_cod_otd']").val('0');
			$("select[name='spr_cod_podrazd'] option").show();
			$("select[name='spr_cod_podrazd'] option").not("[cod_branch = "+$(this).val()+"]").hide();	
			
		
	})
	
	
		$("select[name='spr_cod_podrazd']").change(function(){
			//console.log(branch);
			$("select[name='spr_cod_otd']").val('0');
			$("select[name='spr_cod_otd'] option").show();
			$("select[name='spr_cod_otd'] option").not("[cod_podch = "+$(this).val()+"]").hide();	
		
	})
	
	$("select[name='spr_cod_otd']").change(function(){
			var a = $("select[name='spr_cod_otd']").val();
			console.log(a);
			
			if(a==18 || a==19 || a==20 || a==21 || a==23 || a==24 || a==25 || a==26 || a==27 || a==33 || a==34 || a==35 || a==36 || a==37 || a==38 || a==39 || a==40 || a==47 || a==48 || a==49 || a==50 || a==51){			///////////// Бешенковичи, Городок, Лиозно, Шумилино, все Глубокое, Дубровно, сенно, толочин
				$("#ph").mask("+375(9999) 99999");
				//$("#fax").mask("+375(9999) 99999");
				//console.log("Here");
			}else if(a==11 || a==14 || a==15 || a==16 || a==17 || a==1 || a==2 || a==3 || a==4 || a==5 || a==6 || a==13  || a==52 || a==53 || a==28 || a==29 || a==30 || a==31 || a==32 || a==41 || a==42 || a==43 || a==44 || a==45 || a==46){ /// Управление ВитМрО, ВитЭГ1, ВитЭГ2, Виттеплогруппа, Витгазгруппа, Управление по Витебску, Управление Орша
				$("#ph").mask("+375(999) 999999");
				//$("#fax").mask("+375(999) 999999");
			}else if(a==54 || a==55 || a==56 || a==57 || a==58 || a==59 || a==60 || a==61 || a==62 || a==63 || a==64 || a==65 || a==66 || a==67){			///////////// Управление Минск
				$("#ph").mask("+375(99) 9999999");
				//$("#fax").mask("+375(99) 9999999");
			}else{
				$("#ph").mask("+375999999999");
			} 	
		
	})
	
	$("#mobile_ph").mask("+375(99) 9999999");
	
	$(".btn_fire button").click(function(){
		event.preventDefault();
		var id_fire = $("input[name='id']").val();
			
			my_url = '../admin/proc/user_fire.php'	
				$.ajax({
				type: 'POST',
				cache: false,
				url: my_url,
				data: {user_id: id_fire},
				success: function(data){
					console.log(data);
					$(".btn_fire button").html("Сотрудник уволен");
					$(".btn_fire button").css({"background-color": "#6ef082"});
				}
	
			})
					$('#openModal').fadeOut(300);
					$('#overlay').fadeOut(300);
			Pause(1000);			
			ReloadTable($("div[name_spr]").attr('name_spr'));	

	})
		$(".btn_restore button").click(function(){
		event.preventDefault();
		var id_restore = $("input[name='id']").val();
			
			my_url = '../admin/proc/user_restore.php'	
				$.ajax({
				type: 'POST',
				cache: false,
				url: my_url,
				data: {user_id: id_restore},
				success: function(data){
					console.log(data);
					$(".btn_restore button").html("Сотрудник восстановлен");
					$(".btn_restore button").css({"background-color": "#f06e6e"});
				}
	
			})
					$('#openModal').fadeOut(300);
					$('#overlay').fadeOut(300);	
					
					Pause(1000);			
					ReloadTable($("div[name_spr]").attr('name_spr'));	
	})
	
	$(".btn_del button").click(function(){
		event.preventDefault();
	})
	
	
	$("#main_modal_form").submit(function(){
			event.preventDefault();
			//if($(this).parent().attr("name_spr") == "rules" && ){

				var name_submit = $("input[type=submit][clicked=true]").attr('name');
				
				//console.log($(this).parent().attr("name_spr"));

				if($(this).parent().attr("name_spr") == "branch"){
					var fields = ["name", "adress", "sokr_name", "inner_order"];
				}else if($(this).parent().attr("name_spr") == "podrazd"){
					var fields = ["name_podrazd", "cod_branch", "adress", "sokr_name", "inner_order"];
				}else if($(this).parent().attr("name_spr") == "district") {
					var fields = ["name_otdel", "cod_podch", "cod_branch", "adress", "sokr_name", "inner_order"];
				}else if($(this).parent().attr("name_spr") == "users" || $(this).parent().attr("name_spr") == "fired") {
					var fields = ["filter_spr", "fio", "login", "password", "name", "fam", "otch", "dolgnost", "cod_otd", "cod_uch", "spr_cod_branch", "spr_cod_otd", "spr_cod_podrazd", "tab_num"];
				}else if($(this).parent().attr("name_spr") == "rules") {
					var fields = ["fio", "id_user"];
				}else if($(this).parent().attr("name_spr") == "ipgrp") {
					var fields = ["name", "sort"];
				}	
				
				var error = 0; // флаг заполнения обязательных полей
				
					 $(".form").find(":input").each(function(){ // проверяем каждое поле формы
						 for(var i = 0; i < fields.length; i++){  // проходимся в цикле по массиву обязательных полей
							if($(this).attr("name") == fields[i]){	// если проверяемое поле есть в списке обязательных
							
								if(!$.trim( $(this).val() ) ){      // если поле не заполнено
									 $(this).addClass("formError");
									 error = 1;
								}else{
									 $(this).removeClass("formError");
								}	
							}
						 }
						 
					});
					$(".form").find("select").each(function(){ // проверяем каждое поле формы
				
						 for(var i = 0; i < fields.length; i++){  // проходимся в цикле по массиву обязательных полей
							if($(this).attr("name") == fields[i]){	// если проверяемое поле есть в списке обязательных
								if( $(this).val() == 0 ){      // если поле не заполнено
									 $(this).addClass("formError");
									 error = 1;
								}else{
									 $(this).removeClass("formError");
								}	
							}
						 }
						 
					});

			if(error == 0){

			$("input").prop('disabled', false);
			
			console.log($("#main_modal_form").serialize());
			
			if($(this).attr('mode') == 'edit'){
				my_url = '../admin/proc/modal_edit.php'
			}else{
				my_url = '../admin/proc/modal_new.php'
			}
			
			$.ajax({
				type: 'POST',
				cache: false,
				url: my_url,
				data:
					$("#main_modal_form").serialize(),
				
				success: function(data){
					console.log(data);
	
				}
	
			})	
					$('#openModal').fadeOut(300);
					$('#overlay').fadeOut(300);
		
						
						
			//console.log("proc/download-spr.php?name_spr="+$("div[name_spr]").attr('name_spr'));	
			Pause(1000);			
			//ReloadTable($("div[name_spr]").attr('name_spr'));
			}else{
				var err_text = "";
				if(error) err_text += "<p>Заполните пожалуйста все обязательные поля помеченные звездочкой!!!</p>"
				$('#messenger').hide().fadeIn(500).html(err_text);
				return false;
			 };
			
		//return false;	
	
		//	}	
	})
	
	
	
	
	

})
function Pause(ms){
	var date = new Date();
	var curDate = null;
	do{curDate = new Date();}
	while(curDate-date < ms);
}

function ReloadTable(NameTable){
	 $.ajax({
            type: 'GET',
			cache: false,
            url: "proc/download-spr.php?name_spr="+NameTable,
			//datatype: 'JSON',
			
            success: function(data){
				console.log(data);
                $(".admin-body").html(data);
           }
       })
}

function delete_user(user_id){
	var id_user = user_id;
		if(confirm('Вы действительно хотите удалить данного сотрудника из базы?!')){
	
			
				my_url = '../admin/proc/user_delete.php'	
				
				$.ajax({
				type: 'GET',
				cache: false,
				url: my_url,
				data: {user_id: id_user},
				success: function(data){
					console.log(data);
				}
	
				})
			
					$('#openModal').fadeOut(300);
					$('#overlay').fadeOut(300);
			}		

}
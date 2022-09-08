$(document).ready(function(){


//////////////////////////// Живой поиск в строке для ввода логина при авторизации /////////////////////////////////////////////////////////////////////
$('.who').bind("change keyup input click", function(event) {
event.preventDefault();
if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13){	

    if($(this).val()){
		my_str = $(this).val();
		my_url='../admin/proc/login-search.php';

        $.ajax({
            type: 'POST',
			cache: false,
            url: my_url,
			datatype: 'JSON',
			data:{referal: my_str,
				
			},
            success: function(data){
			//	console.log(data);
               $(".search_result").html(data).fadeIn();
				if(event.keyCode != 13  && event.keyCode != undefined){
					
					curElem = -1;	
				}
           }
       })
    }

}

if(event.keyCode == 40 || event.keyCode == 38 || event.keyCode == 13 ){	
	
			
			if($(".search_result").is(":visible")){
				var pre_result_list = Array.from($(".search_result li"));
				$(pre_result_list[curElem]).removeClass('active');
				
			//	console.log($(pre_result_list));
				//console.log(curElem);
				if(event.keyCode == 40 || event.keyCode == 38){
					if(event.keyCode == 40){
						curElem = curElem + 1;
					}else{
						
						curElem = curElem - 1;
					}
					
					if(curElem >= pre_result_list.length){
						curElem = 0;
					}
					if(curElem < 0){
						curElem = pre_result_list.length-1;
					}
					
					$(pre_result_list[curElem]).addClass('active');
				
			
				
				}
		
				if(event.keyCode == 13){
					
					if($(".search_result").is(":visible")){
				
						$(".who").val(pre_result_list[curElem].innerHTML);
						$(".who_id").val($(pre_result_list[curElem]).attr('id_user')) ;
						$("select[name='cod_branch'] option[value="+$(pre_result_list[curElem]).attr('user_cod_branch')+"]").attr("selected","selected");
						$(".search_result").fadeOut();
					}
				}
			
			}
		}





$(".search_result").hover(function(){
	$(".who").blur();
});

$(".search_result").on("click", "li", function(){
	$(".who").val($(this).text()); 
	$(".who_id").val($(this).attr('id_user'));

	$("select[name='cod_branch'] option[value="+$(this).attr('user_cod_branch')+"]").attr("selected","selected");
  $(".search_result").fadeOut();
	
});


});

$('form').submit(function(){
	if($(".search_result").is(":visible") && $("input[name='password']").length > 0){
		event.preventDefault();	
					}
	
})










 })
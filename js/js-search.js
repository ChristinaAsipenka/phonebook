$(document).ready(function(){
	
	$('.clear_field').click(function(){
		//if($("#search").val() > 0) {
			$("#search").val('');
			$('.clear_field').fadeOut();
			$('#pre-result').fadeOut();
		//}
	});
	
	
	$("#search").bind("change keyup click input", function(event) {
			event.preventDefault(); 
			
		  // получаем то, что написал пользователь
        var searchString    = $("#search").val();

        // формируем строку запроса
       
		if(searchString.length > 0) {
			$('.clear_field').fadeIn();
		}

		
		if(event.keyCode == 13){
			mainSearch();
		}else if(event.keyCode == 40 || event.keyCode == 38){	
			if($("#pre-result").is(":visible")){
				var pre_result_list = Array.from($("#pre-result li"));
				$(pre_result_list[curElem]).removeClass('active');
				
				if(event.keyCode == 40){
					curElem = curElem + 1;
				}else{
					
					curElem = curElem - 1;
				}
				
				if(curElem > pre_result_list.length){
					curElem = 0;
				}
				if(curElem < 0){
					curElem = pre_result_list.length;
				}
				
				$(pre_result_list[curElem]).addClass('active');
				$("#search").val(pre_result_list[curElem].innerHTML);
			}
		}else{
		
		
			var searchString    = $("#search").val().trim();
			var data = 'pre-search='+ searchString;
			$("#pre-result li").remove();
				if(searchString.length >= 3) {
					$.ajax({
						type: "POST",
						url: "search.php",
						data: data,
					   success: function(html){ // запустится после получения результатов
							$("#pre-result").fadeIn();
							$("#pre-result").append(html);
							curElem = -1;
					  }
					});
				}else{
					 $("#pre-result").fadeOut();
				}
				
					//$("#pre-result").focus();
					/*var pre_result_list = Array.from($("#pre-result li"));
					var curElem = 0;
					if(event.keyCode == 40 || event.keyCode == 38){
					
					if($("#pre-result").is(":visible")){
						
							if(event.keyCode == 40){
								curElem = curElem + 1;
								$("#search").val(pre_result_list[curElem].text());
							}else{
								curElem = curElem - 1;
								$("#search").val(pre_result_list[curElem].text());
							}
						//	console.log(pre_result_list);
					}*/
				
				
				return false;
		}
		
		
	 });
	
$("#pre-result").hover(function(){
	$("#search").blur();
});

$("#pre-result").on("click", "li", function(){
	$("#search").val($(this).text()); 
	mainSearch();
   
	
});

});
/*$(document).keydown(function(){
	
	if($("#pre-result").is(":visible")){
		var pre_result_list = Array.from($("#pre-result li"));
		
			if($("#pre-result li").is(":hover")){
				
			}else{
				var curElem = 0;	
			}
		
						if(event.keyCode == 40){
							curElem = curElem + 1;
							$("#search").val(pre_result_list[curElem].text());
						}else{
							curElem = curElem - 1;
							$("#search").val(pre_result_list[curElem].text());
						}
					//	console.log(pre_result_list);
				}
	
	console.log(event.keyCode);
});*/
function mainSearch(){
	 var searchString    = $("#search").val().trim();
		 var data = 'search='+ searchString;

        // если searchString не пустая
		$(".result_html").remove();
        if(searchString.length >= 3) {
            // делаем ajax запрос
            $.ajax({
                type: "POST",
                url: "search.php",
                data: data,
             //   beforeSend: function(html) { // запустится до вызова запроса
                //    $("#results").html('');
                //    $("#searchresults").show();
                //    $(".word").html(searchString);
              // },
               success: function(html){ // запустится после получения результатов
					//$("#search").blur();
                    $("#search_result").append(html);
              }
            });
        }
		 $("#pre-result").fadeOut();
        return false;
}

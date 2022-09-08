$(document).ready(function(){

$('.spr_table tbody tr, button.new_element_spr').click(function(){




	//console.log('here');
	var spr_name = $('.spr_table').attr('name_spr');
	var id_work = $(this).attr('id');
	var user_delete = 0;
	
	if($(this).attr('class').length > 0){
		user_delete = 1; 
	}
	
	//console.log($(this).attr('class'));
	
		
	        $.ajax({
            type: 'POST',
			cache: false,
			datatype: 'JSON',
            url: "proc/download_modal.php",
			data:{
				name_spr: spr_name,
				id: id_work,
				user_delete: user_delete
			},
            success: function(data){
				//console.log(data);
               $("#form_body").html(data);
           }
       })


		
		$('#openModal').fadeIn(300);
		$('#overlay').fadeIn(300);
		return false;
	
});

		$('.close').click(function(){
		$('#openModal').fadeOut(300);
		$('#overlay').fadeOut(300);
		return false;
	});
	
	
	/********************************************** живой поиск ****************************************************************************/		
		$("#search").keyup(function(){
		_this = this;
		
		$.each($(".spr_table tbody tr"), function() {
        if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
            $(this).hide();
        } else {
            $(this).show(); 
		}			
        });
    });
	

});
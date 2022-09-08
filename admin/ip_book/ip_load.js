$(document).ready(function(){
	
$("#remote_contact_book, #contact_book, #export_vcard").submit(function(){
		event.preventDefault();
var html_otvet = '#otvet2';

	if($(this).attr('id') == 'remote_contact_book'){
		my_url = 'ip_book/pb_distant.php';
		var html_otvet = '#otvet2';
	}else if ($(this).attr('id') == 'export_vcard'){
		my_url = 'ip_book/pb_vcard.php';
		var html_otvet = '#otvet3';
	}else{
		my_url = 'ip_book/pb_contacts.php';
		var html_otvet = '#otvet1';	
	};

$(html_otvet).html("Wait....");
				$.ajax({
				type: 'POST',
				cache: false,
				url: my_url,
				data: $(this).serialize(),
				success: function(data){
					$(html_otvet).html(data);
					
				}
	
			})
					
	})


})
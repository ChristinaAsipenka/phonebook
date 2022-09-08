$(document).ready(function(){

$(function() {
	var Accordion = function(el, multiple) {
		this.el = el || {};
		this.multiple = multiple || false;

		// Variables privadas
		var links = this.el.find('.link');
		// Evento
		links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
	}

	Accordion.prototype.dropdown = function(e) {
		var $el = e.data.el;
			$this = $(this),
			$next = $this.next();

		$next.slideToggle();
		$this.parent().toggleClass('open');

		if (!e.data.multiple) {
			$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
		};
	}	

	var accordion = new Accordion($('#accordion'), false);
});


$('.submenu a').click(function(event){
	event.preventDefault();
	var  my_url=$(this).attr('href');

	
	   $.ajax({
            type: 'POST',
			cache: false,
            url: my_url,
			datatype: 'JSON',
			
            success: function(data){
			//	console.log(data);
                $(".admin-body").html(data);
           }
       })
	
	
	
	
	return false;
});







})
$(document).ready(function() {
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		if (scroll >= 5) {
			$("#masthead_pb").addClass("scrolled");
		} else {
			$("#masthead_pb").removeClass("scrolled");
		}
	});
});

var scrolled;
window.onscroll = function() {
    scrolled = window.pageYOffset || document.documentElement.scrollTop;
	const pageWidth = document.documentElement.scrollWidth;
	if(pageWidth > 1480){
		kol = 5
	} else {
		kol = 172
	}		
    if(scrolled > 5){
        $(".sticky_body").css({"border-bottom": "2px solid var(--color-1)"})
		$(".emblem_gegn").css({"width": "10px"})
		$(".emblem_gegn").css({"height": "10px"})
    }
    if(kol > scrolled){
        $(".sticky_body").css({"border-bottom": "2px solid var(--color-255)"})         
    }
}
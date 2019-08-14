$(document).ready(function() {
	// scroll to id
	var $page = $("html, body");
	$('a[href*="#"]').click(function() {
		$page.animate({
			scrollTop: $($.attr(this, "href")).offset().top
		}, 650);
		return false;
	});

	// Resize bg_image
	// function heightDetect() {
	// 	$(".main_head").css("height", $(window).height());
	// };
	// heightDetect();
	// $(window).resize(function() {
	// 	heightDetect();
	// });

	// OWL carousel 
	$('.owl-carousel').owlCarousel({
    loop: true,
    margin: 10,
    navigation: false,
    autoplay: true,
    autoplayTimeout: 3600, 
    autoplaySpeed:2200,
    nav: false,
    lazyLoad: true,
    responsive:{
        0:{
            items:1
        },
        991:{
            items:2
        }
    }
	})

}); 
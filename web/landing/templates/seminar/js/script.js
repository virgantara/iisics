$(window).scroll(function() {
	if ($(window).scrollTop() >= 92) { $('.header-menu').addClass('fixed-top'); }
	if ($(window).scrollTop() >= 93) { $('.header-menu').addClass('show'); } else { $('.header-menu').removeClass('show fixed-top'); }
});

// Testimonial carousel
if($('.testimonial-carousel').length){
	$('.testimonial-carousel').owlCarousel({
		rtl:false,
		loop: true,
		dots: true,
		nav:true,
		animateIn: 'fadeIn',
		autoplayHoverPause: false,
		autoplay: false,
		smartSpeed: 700,
		navText: [
		  '<i class="fa fa-angle-left" aria-hidden="true"></i>',
		  '<i class="fa fa-angle-right" aria-hidden="true"></i>'
		],
		responsive:{
			0: {
				items: 1,
				center: false
			},
			480:{
				items:1,
				center: false
			},
			600: {
				items: 1,
				center: false
			},
			768: {
				items: 1
			},
			992: {
				items: 1
			},
			1200: {
				items: 1
			}
		}
	});
}

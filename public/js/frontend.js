$(document).ready(function() {
	// sets the min height for the content wrapper so that the footer bar doesn't stick to the middle of the screen
	$minheightreq = $(window).height() - ($('footer.footer').height() * 2) - $('.navbar.header').outerHeight() - 30;
	$('.content-wrapper').css('min-height', $minheightreq)
});
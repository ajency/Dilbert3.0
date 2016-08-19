$.fn.inView = function(){
    //Window Object
    var win = $(window);
    //Object to Check
    obj = $(this);
    //the top Scroll Position in the page
    var scrollPosition = win.scrollTop();
    //the end of the visible area in the page, starting from the scroll position
    var visibleArea = win.scrollTop() + win.height();
    //the end of the object to check
    var objEndPos = (obj.offset().top + obj.outerHeight());
    var objStartPos = obj.offset().top;
    return(visibleArea >= objEndPos && scrollPosition <= objStartPos ? true : false)
};

$(document).ready(function() {
	// sets the min height for the content wrapper so that the footer bar doesn't stick to the middle of the screen
	$minheightreq = $(window).height() - ($('footer.footer').height() * 2) - $('.navbar.header').outerHeight() - 30;
	$('.content-wrapper').css('min-height', $minheightreq);

	function checkifpositive(count) {
		if (count > 0) {
			return true;
		} else {
			return false;
		}
	}

	$chromeFloats = $('.chrome-store-section').html();
	$floatingDiv = '<div class="chrome-float-section"><div class="chrome-float-inner">' + $chromeFloats + '</div></div>';
	$('body').append($floatingDiv);
	$(window).scroll(function() {
		if ($(window).width() > 767) {
			$scrollTop_val = $(window).scrollTop();
			$elementOffset = $('.chrome-store-section').offset().top;
			$distance = ($elementOffset - $scrollTop_val);

			// console.log($('.chrome-store-section').offset().top + ': offset\n' + $distance + ': distance')

			if (checkifpositive($distance) && $('.chrome-store-section').offset().top >= $distance) {
				// $('.chrome-float-section').addClass('stick-to-bottom').removeClass('stick-to-top');
				$('.chrome-float-section').removeClass('stick-to-top');
			} else {
				$('.chrome-float-section').addClass('stick-to-top').removeClass('stick-to-bottom');
			}

			if ($('.chrome-store-section').inView()) {
				$('.chrome-float-section').addClass('hidden');
			} else {
				$('.chrome-float-section').removeClass('hidden');
			}
		}
	})
	$(window).load(function() {
		$(window).trigger('scroll')
	});
});
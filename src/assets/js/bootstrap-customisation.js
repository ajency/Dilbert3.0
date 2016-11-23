$(document).ready(function() {
	$('body').on('click', '.last-week', function() {
		if ($(this).hasClass('opened')) {
			$(this).removeClass('opened').addClass('closed');
			$(this).find('.card-progress').animate({width: 0}, 500)
		} else {
			$(this).addClass('opened').removeClass('closed');
			$wid = $(this).find('.card-progress').attr('data-width') + '%';
			$(this).find('.card-progress').show().animate({width: $wid}, 500)
		}
	});

	if (!$('.navbar-toggle').length) {
		if (!$('.navbar-toggle').hasClass('collapsed')) {
			// menu is open - will be closed
			if (!$('.menu-overlay').length) {
				$('.navbar-collapse').before('<div class="menu-overlay"></div>');
			}
			$('.menu-overlay').fadeIn('slow');
		}
	}

	$(document).on('click', '.navbar-toggle', function() {
		if (!$(this).hasClass('collapsed')) {
			// menu is open - will be closed
			if (!$('.menu-overlay').length) {
				$('.navbar-collapse').before('<div class="menu-overlay"></div>');
			}
			$('.menu-overlay').fadeIn('slow');
		} else {
			// menu is closed - will be opened
		}
	});
	$(document).on('click', '.menu-overlay', function() {
		$('.navbar-toggle').addClass('collapsed');
		$('.navbar-collapse').removeClass('in');
		$(this).fadeOut('slow');
	});
});
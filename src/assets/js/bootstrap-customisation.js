$(document).ready(function() {

	$('body').on('click', '.last-week', function() {
		if (!$(this).hasClass('monday-card')) {
			if ($(this).hasClass('opened')) {
				$(this).removeClass('opened').addClass('closed');
				$(this).find('.card-progress').animate({width: 0}, 500)
			} else {
				$(this).addClass('opened').removeClass('closed');
				
				$totalWeekdays = $(this).find('.card-progress').attr('data-weekdays');
				//$weekdaysDone = $(this).find('.listy').length; // Get Number of records that week
				$weekdaysLeave = $('.last-week').find('.listy').find('.show-opened');
				$weekdaysDone = $weekdaysLeave.length; // Get Number of records that week
				$leavetaken = 0;

				$leaveKeyWorkList = ["Absent", "Leave"]; // Leave Key word list -> This list can later be upgraded to AJAX call to remain up-to-date

				// $weekdaysDone.each(function(index) {
				for (var index = 0; index < $weekdaysLeave.length; index++) { // Made it Synchronous
					if ($weekdaysLeave != undefined && $weekdaysLeave[index].outerHTML.search('Status') != -1){
						
						// If not -1, then there exist Status key word & Status keyword is only used for leave
						for(var i = 0; i < $leaveKeyWorkList.length; i++) { // checks if it is matching the list
							if($weekdaysLeave[index].outerHTML.search($leaveKeyWorkList[i]) != -1) {
								$leavetaken += 1;
							}
						}
					}
				}

				$eachDay = 100 / $totalWeekdays;
				$percentToday = $eachDay * ($weekdaysDone - $leavetaken);
				if($percentToday > 100) { // Saturday is not a working day - hence some request to Work on Sat, else Mon-Fri is considered working hence if progress is greater than 100%, then set it back to 100%
					$percentToday = 100;
				} 
				$(this).find('.card-progress').attr('data-width', $percentToday);

				$wid = $(this).find('.card-progress').attr('data-width') + '%';
				$(this).find('.card-progress').show().animate({width: $wid}, 500)
			}
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
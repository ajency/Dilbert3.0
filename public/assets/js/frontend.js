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

	

	// for the tables in the dashboard
	if ($('.hscroll-table').length) {
		$('.hscroll-table').each(function() {
			$headerCol = $(this).find('thead th:last-child').html();
			$(this).find('thead th:last-child').detach();
			$bodyCol = [];
			$(this).find('tbody tr').each(function() {
				$singleCell = $(this).find('td:last-child').html();
				$(this).find('td:last-child').detach();
				$bodyCol.push($singleCell);
			});

			if ($(this).children().first().is('table')) {
				$contElement = $(this);
			} else {
				$contElement = $(this).children().first();
			}

			$contElement.append(
				'<table class="table fixed-right">' +
					'<thead><tr><th>' + $headerCol + '</th></tr></thead><tbody></tbody>' +
				'</table>'
			);
			for (var i = 0; i < $bodyCol.length; i++) {
				$contElement.find('.fixed-right').append(
					'<tr>' +
						'<td>' + $bodyCol[i] + '</td>' +
					'</tr>'
				);
			}
		});
	}


	$('[data-toggle="tooltip"]').tooltip()
	// commented section is used when there is idle and work times
	// $('.table-view.month [data-toggle="tooltip"]').each(function() {
	// 	$day = $(this).find('.th-day').text().trim();
	// 	$totalTime = $(this).find('.th-total').text().trim();
	// 	$workTime = $(this).find('.th-work').text().trim();
	// 	$breakTime = $(this).find('.th-break').text().trim();
	// 	$(this).attr('data-original-title',
	// 		'<div class="month-days">' +
	// 			'<div class="month-date">' + $day + '</div>' +
	// 			'<div class="month month-total"><span class="j-t">Total time</span><strong class="pull-right">' + $totalTime + ' <small>hrs</small></strong></div>' +
	// 			'<div class="month month-work"><span class="j-t">Work Time</span><strong class="pull-right">' + $workTime + ' <small>hrs</small></strong></div>' +
	// 			'<div class="month month-break"><span class="j-t">Break Time</span><strong class="pull-right">' + $breakTime + ' <small>hrs</small></strong></div>' +
	// 		'</div>'
	// 	);
	// });
	$('.table-view.month .single-day').each(function() {
		if ($(this).hasClass('total')) {
			$startText = 'Avg. Start Time';
			$endText = 'Avg. End Time';
		} else {
			$startText = 'Start Time';
			$endText = 'End Time';
		}
		if (!$(this).parents().hasClass('company-summary')) {
			$day = $(this).find('.th-day').text().trim();
			$totalTime = $(this).find('.th-total').text().trim();
			$startTime = $(this).find('.th-work').text().trim();
			$endTime = $(this).find('.th-break').text().trim();
			$(this).attr('data-original-title',
				'<div class="month-days">' +
					'<div class="month-date">' + $day + '</div>' +
					'<div class="month month-total"><span class="j-t">Total time</span><strong class="pull-right">' + $totalTime + ' <small>hrs</small></strong></div>' +
					'<div class="month month-work"><span class="j-t">' + $startText + '</span><strong class="pull-right">' + $startTime + ' <small></small></strong></div>' +
					'<div class="month month-break"><span class="j-t">' + $endText + '</span><strong class="pull-right">' + $endTime + ' <small></small></strong></div>' +
				'</div>'
			);
		} else {
			$totalTime = $(this).find('.th-total').text().trim();
			$startTime = $(this).find('.th-work').text().trim();
			$endTime = $(this).find('.th-break').text().trim();
			$(this).attr('data-original-title',
					'<div class="month-days">' +
						'<div class="month month-total"><span class="j-t">Total time</span><strong class="pull-right">' + $totalTime + ' <small>hrs</small></strong></div>' +
						'<div class="month month-work"><span class="j-t">' + $startText + '</span><strong class="pull-right">' + $startTime + ' <small></small></strong></div>' +
						'<div class="month month-break"><span class="j-t">' + $endText + '</span><strong class="pull-right">' + $endTime + ' <small></small></strong></div>' +
					'</div>'
				);
		}
	});


	$(document).on('click', '.stay-open.dropdown-menu', function (e) {
		e.stopPropagation();
	});
});
(function ($) {
	'use strict';

	let currentPage = 1;

	function loadCaseResults(page = 1) {
		const caseType = $('#lcr-case-type-filter').val();
		const clientState = $('#lcr-state-filter').val();

		$('#lcr-loading').removeClass('d-none');
		$('#lcr-case-results-wrap').css('opacity', '0.4');

		$.ajax({
			url: lcr_ajax.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'lcr_filter_case_results',
				nonce: lcr_ajax.nonce,
				case_type: caseType,
				client_state: clientState,
				page: page
			},
			success: function (response) {
				if (response.success) {
					$('#lcr-case-results-wrap').html(response.data.html);
					$('#lcr-pagination-wrap').html(response.data.pagination);
					currentPage = page;
				} else {
					$('#lcr-case-results-wrap').html(
						'<div class="alert alert-danger">Unable to load case results.</div>'
					);
				}
			},
			error: function () {
				$('#lcr-case-results-wrap').html(
					'<div class="alert alert-danger">Something went wrong. Please try again.</div>'
				);
			},
			complete: function () {
				$('#lcr-loading').addClass('d-none');
				$('#lcr-case-results-wrap').css('opacity', '1');
			}
		});
	}

	$(document).on('submit', '#lcr-filter-form', function (e) {
		e.preventDefault();
		loadCaseResults(1);
	});

	$(document).on('change', '#lcr-case-type-filter', function () {
		loadCaseResults(1);
	});

	$(document).on('click', '.lcr-pagination-link', function (e) {
		e.preventDefault();

		const page = parseInt($(this).data('page'), 10);

		if (!page || $(this).closest('.page-item').hasClass('disabled')) {
			return;
		}

		loadCaseResults(page);

		$('html, body').animate({
			scrollTop: $('#lcr-case-results-wrap').offset().top - 120
		}, 300);
	});

	/**
	 * GTM event tracking.
	 */
	$(document).on('click', '.lcr-case-link', function () {
		const caseType = $(this).data('case-type') || '';
		const settlementAmount = $(this).data('settlement-amount') || '';

		window.dataLayer = window.dataLayer || [];

		window.dataLayer.push({
			event: 'case_result_view',
			case_type: caseType,
			settlement_amount: settlementAmount
		});
	});

})(jQuery);

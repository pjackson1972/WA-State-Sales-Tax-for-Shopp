jQuery(document).ready(function ($) {
	var $taxEnable = $('#wadbt_enable_tax');
	var $taxDisable = $('#wadbt_disable_tax');
	var $downloadEnable = $('#wadbt_enable_downloads');
	var $downloadDisable = $('#wadbt_disable_downloads');
	
	toggleRadios = function () {
		console.log('toggle');
		if($taxDisable.attr('checked') == 'checked') {
			$($downloadDisable).attr({
				disabled: true
			}).attr({
				checked: 'checked'
			});
			$($downloadEnable).attr({
				disabled: true
			});
		} else {
			$($downloadDisable).removeAttr('disabled');
			$($downloadEnable).removeAttr('disabled');
		}
	};
	// on load
	toggleRadios();

	// on click disable
	$('input[type=radio]').on('change', function () {
		console.log( 'change' );
		toggleRadios();
	});
});

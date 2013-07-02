jQuery(document).ready(function ($) {
	var $taxEnable = $('#wadbt_enable_tax');
	var $taxDisable = $('#wadbt_disable_tax');
	var $downloadEnable = $('#wabdt_enable_downloads');
	var $downloadDisable = $('#wadbt_disable_downloads');

	$($taxDisable).on('click', function () {
		$($downloadDisable).attr({
			disabled: true
		}).attr({
			checked: 'checked'
		});
		$($downloadEnable).attr({
			disabled: true
		});
	});

	$($taxEnable).on('click', function () {
		$($downloadDisable).removeAttr('disabled');
		$($downloadEnable).removeAttr('disabled');
	});
});

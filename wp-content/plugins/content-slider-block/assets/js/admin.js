function csbHandleShortcode(id) {
	var input = document.querySelector('#csbFrontShortcode-' + id + ' input');
	var tooltip = document.querySelector('#csbFrontShortcode-' + id + ' .tooltip');
	input.select();
	input.setSelectionRange(0, 30);
	document.execCommand('copy');
	tooltip.innerHTML = wp.i18n.__('Copied Successfully!', 'content-slider-block');
	setTimeout(() => {
		tooltip.innerHTML = wp.i18n.__('Copy To Clipboard', 'content-slider-block');
	}, 1500);
}
jQuery( document ).ready(function() {
	jQuery('section.awcp_section').on('click', function() { 
		jQuery(this).next().toggle();
	});
});
jQuery(document).ready(function($) {

/* Sets the initially showing settings section */
if(window.location.hash) {
	$(window.location.hash+'-tab').addClass('nav-tab-active');
	$(window.location.hash).addClass('showing');
} else {
 	$('.nav-tab').first().addClass('nav-tab-active');
	$('.section').first().addClass('showing');
}

		



/* Function for switching between tabs on the settings page */
	$('.nav-tab').click(function() {
		var tab = $(this).attr('id');
		var section = tab.replace('-tab','');
		
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		
		$('.section').removeClass('showing').addClass('hidden');
		$('#'+section).removeClass('hidden').addClass('showing');
	});

	$('.select-all-toggle').toggle(function(event){
		event.preventDefault();
		$(this).siblings('input:checkbox').prop("checked", true);
		$(this).val("Deselect All");
	}, function(event){
		event.preventDefault();
		$(this).siblings('input:checkbox').prop("checked", false);
		$(this).val("Select All");	
	});

	$('.select-all-toggle').each(function() {

		var unchecked = $(this).siblings('input:checkbox').filter(':not(:checked)');
		if (unchecked.length == 0) {
			$(this).click();
		}
	});	


});
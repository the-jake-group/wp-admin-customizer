jQuery(document).ready(function($) {

/* ==========================================================================
	Color Field
	========================================================================= */

	$(".wp-color-field").wpColorPicker({
		defaultColor: false,
		change: function(event, ui) {
			var $input = $('<input type="hidden" name="color_update" id="color_update" value="true">');
			$(this).closest("form").append($input);
		},
	});

/* ==========================================================================
	Upload Media
	========================================================================= */

	$('.media-upload-button').click(function(event) {
		event.preventDefault();

		$container = $(this).closest(".media-upload-container");

		if (typeof uploader !== "undefined") {
			uploader.open();
			return;
		}

		uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		uploader.on('select', function() {
			var field      = $container.data("field");
			var display    = $container.data("display");
			var attachment = uploader.state().get('selection').first().toJSON();

			$(display).html('<img src="' + attachment.url + '" />')
			$(field).val(attachment.id);

			$container.removeClass("no-img").addClass("has-img");
		});

		uploader.open();
	});

/* ==========================================================================
	Remove Media
	========================================================================= */

	$('.media-remove-button').click(function(event) {
		event.preventDefault();

		$container = $(this).closest(".media-upload-container");

		var field   = $container.data("field");
		var display = $container.data("display");

		$(display).html('');
		$(field).val(0);

		$container.removeClass("has-img").addClass("no-img");
	});

/* ==========================================================================
	Form submission
	========================================================================= */

	$(".compiler-form").submit(function(event) {
		// event.preventDefault();

		if ( $(this).find("#color_update").length && $(this).find("#color_update").val()) {
			var lessFile   = plugin_root + '/assets/less/master.less';
			var lessScript = plugin_root + '/assets/js/less.js';

			return compileLess($(this), lessFile, lessScript);
		} else {
			return true;
		}
	});

/* ==========================================================================
	Less Compiler
	========================================================================= */

	compileLess = function($form, lessFile, lessScript) {
		var lessVars = getLessVars(".less-variable");
		var $wrap    = $('<div id="less-container" style="display: none;"></div>');
		var $link    = $('<link rel="stylesheet/less" type="text/css" href="' + lessFile + '">');
		var $script  = $('<script type="text/javascript" src="' + lessScript + '"></script>');
		var $input   = $('<input type="hidden" name="compiled_css">')

		$wrap.append($link)
		$wrap.appendTo("body")
		$wrap.append($script)

		less.modifyVars(lessVars);

		$style           = $('style[id*="less"]')
		var compiled_css = $style.html();

		$style.remove();

		$input.val( compiled_css );

		$form.append($input);

		return true; // Chain up, so that call stack waits for this to execute
	}


	function getLessVars(selector) {
		fields = {};

		$(selector).each(function() {
			var name     = '@' + $(this).data("less-var-name");
			fields[name] = $(this).val();
		});

		return fields;
	}


});
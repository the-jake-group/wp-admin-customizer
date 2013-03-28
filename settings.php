<?php

if (isset($_POST['updateRequest']))	{
	include TJG_AT_PATH."/processors/settings_update.php";
}

	$primary 		= get_option("admin-theme-primary-color");
	$sm_logo 		= get_option("admin-theme-small-logo");
	$custom_hover 	= get_option("admin-theme-custom-hover");
	$lg_logo 		= get_option("admin-theme-login-logo");
	$rounded 		= get_option("admin-theme-rounded-corners");
	
//	Dashboard Widget Settings
	global $dashboard_widget_array;
	foreach ($dashboard_widget_array as $widget => $ignored)	{
		global $$widget;
		$$widget = get_option("admin-theme-$widget");		
	}

?>
<div id="icon-options-general" class="icon32"><br></div>
<h2 class="nav-tab-wrapper">
	<a id="at-option-theme-tab" class="nav-tab" title="Theme" href="#at-option-theme">Theme</a>
	<a id="at-option-login-tab" class="nav-tab" title="Login" href="#at-option-login">Login</a>
	<a id="at-option-dashboard-tab" class="nav-tab" title="Dashboard" href="#at-option-dashboard">Dashboard</a>
</h2>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#ilctabscolorpicker').farbtastic("#primary");
	}); 
</script>

<div class="wrap" id="tjg-admin-theme">
	<form method="post" enctype="multipart/form-data" class="admin-form">
		<h2>Custom Admin Theme Settings</h2>
		<input type="hidden" name="updateRequest" value="true">
		<div class="section hidden" id="at-option-theme">
			<table class="form-table">
				<tbody>
	
					<tr valign="top">
						<th scope="row">
							<label for="primary">Primary Color</label>
							<small>
								<p>Use the color picker, or type in a hexadecimal value (e.g. #000000).</p>
								<p>To restore the default wordpress color, use: #464646.</p>
							</small>
						</th>
						<td>
							<input type="text" id="primary" name="primary" value="<?php echo $primary; ?>" />
							<div id="ilctabscolorpicker"></div>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="logo">Small Logo</label>
							<small>This image should be 20px x 20px.</small>
						</th>
						<td>
							<input name="sm-logo" type="file" id="sm-logo" value="<?php echo $sm_logo; ?>" class="regular-text">
							<?php if ($sm_logo != "wordpress") { ?>
								<div class="logo-holder group">
									<img src="<?php echo TJG_AT_URL.'/img/'.$sm_logo; ?>" class="logo-display" />
									<input name="smReset" type="checkbox" id="smReset">
									<label for="smReset" class="inline-label">Reset logo to Wordpress Default</label>
								</div>
							<?php }	?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="custom_hover">Custom Hover Effect</label>
							<small>Select this if the image you uploaded is a CSS sprite with a hover effect.  The hover must be the bottom half of the image, and the image itself must be 20px x 40px.</small>
						</th>
						<td>
							<input name="custom_hover" type="checkbox" id="custom_hover" <?php if ($custom_hover == "true") { echo 'checked="checked"'; } ?>>
						</td>
					</tr>				
				</tbody>
			</table>
		</div>

		<div class="section hidden" id="at-option-login">
			<div class="at-section">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="logo">Large Logo</label>
								<small>No larger than 320px x 82px.</small>
							</th>
							<td>
								<input name="lg-logo" type="file" id="lg-logo" value="<?php echo $lg_logo; ?>" class="regular-text">
								<?php if ($lg_logo != "wordpress") { ?>
									<div class="logo-holder group">
										<img src="<?php echo TJG_AT_URL.'/img/'.$lg_logo; ?>" class="logo-display" />
										<input name="lgReset" type="checkbox" id="lgReset">
										<label for="lgReset" class="inline-label">Reset logo to Wordpress Default</label>
									</div>
								<?php }	?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="rounded">Rounded Corners</label>
								<small>This gives the box on the login screen rounded corners.</small>
							</th>
							<td>
								<input name="rounded" type="checkbox" id="rounded" <?php if ($rounded == "true") { echo 'checked="checked"'; } ?>>
							</td>
						</tr>
					</tbody>
				</table>			
			</div>
		</div>



		
		<div class="section hidden" id="at-option-dashboard">
			<div class="at-section">
				<h3>Uncheck to Disable Dashboard Widgets</h3>
				<?php foreach ($dashboard_widget_array as $widget => $label)	{
							global $$widget;	?>
							<input name="<?php echo $widget; ?>" type="checkbox" id="<?php echo $widget; ?>" <?php tjg_at_is_checked($$widget, 'true') ?>>
							<label for="<?php echo $widget; ?>" class="inline-label"><?php echo $label; ?></label>

				<?php 	} ?>	
				<input type="button" id="select-all-dashboard" value="Select All" class="select-all-toggle" />
			</div>
		</div>
		
		<div class="form-bottom">		
			<input type="submit" id="submit" value="Submit">
			<input type="reset" id="reset" value="Reset">
		</div>
	</form>
</div>
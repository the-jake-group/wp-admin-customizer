<?php

	$dashboard_widget_array = array(
/* 		'dashboard_plugins' => 'Plugins', */
		'dashboard_recent_comments' => 'Recent Comments',
		'dashboard_primary' => 'Wordpress Blog',
		'dashboard_incoming_links' => 'Incoming Links',
		'dashboard_right_now' => 'Right Now',
		'dashboard_secondary' => 'Other Wordpress News',
		'dashboard_recent_drafts' => 'Recent Drafts',
		'dashboard_quick_press' => 'Quick Press',
/*		'wp_welcome_panel' => 'Welcome' */
	);

	function tjg_at_is_selected($var, $value){
		if ($var == $value)	{ echo 'selected="selected"'; }
	}
	
	function tjg_at_is_checked($var, $value){
		if ($var == $value)	{ echo 'checked="checked"'; }
	}
	
?>
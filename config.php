<?php

/**
 * Enables/Disables the developer features of the plugin.
 * So far this only includes the dashboard contact widget
 * and the footer text in the admin area.
 */

define('DEVELOPER_FEATURES', false);

/**
 * If developer features are enables, these values will be 
 * used for various functions and text around the admin.  If a
 * necessary piece of information is not filled out, the corresponding 
 * section of the site will not be displayed.  For example:  
 * The Dashboard contact widget will only display if all three constants
 * are defined.
 */

// define('DEVELOPER_NAME', '');
// define('DEVELOPER_EMAIL', '');
// define('DEVELOPER_PHONE', '');

/**
 * Hides the Admin Settings page completely in wordpress.
 * No changes can be made if this is set to true.
 */

define('HIDE_SETTINGS', false);

?>
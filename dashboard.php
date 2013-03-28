<?php

/**
	Custom Widgets
**/
//	The content of this function can be duplicated for each widget you create
	function tjg_at_add_dashboard_add_widgets() {
		if ( isset( $_POST['contact_submit'] ) )	{
			include TJG_AT_PATH."/processors/contact_form.php";
		}

		if ( DEVELOPER_FEATURES && defined( 'DEVELOPER_NAME' ) && defined( 'DEVELOPER_EMAIL' ) ) {
			wp_add_dashboard_widget('wp_dashboard_contact_form', 'Contact '.DEVELOPER_NAME, 'tjg_at_dashboard_contact_form');
		}
		wp_add_dashboard_widget( 'tjg_at_dashboard_list_pages', 'All Pages List', 'tjg_at_dashboard_list_pages' );
	}

//	Contact form widget
	function tjg_at_dashboard_contact_form() {
		global $mail_success, $form_message;
	
		$user_id = get_current_user_id();
		$user_data = get_userdata($user_id);

		$preferred_name = $user_data->display_name;
		$first_name 	= $user_data->user_firstname;
		$last_name 		= $user_data->user_lastname;
		$username 		= $user_data->user_login;

		if ( $preferred_name != $username ) {
			$name = $preferred_name;
		}
		else {
			$name = ( $user_data->display_name ) ? $user_data->display_name : "$first_name $last_name";
		}
		if ( !$_POST['name'] ) { $_POST['name'] = $name; }

		if ( $mail_success ) { unset($_POST); }

	?>
	<div id="tjg-at-db-contact-form" class="group">
		<form method="post" enctype="multipart/form-data" id="contact-form" class="admin-form">
			<input type="hidden" name="contact_submit" value="true">
			<div>
	 			<div class="formLeft">
					<label for="name">Your Name<span>*</span></label>
					<input type="text" id ="name" name="name" maxlength="35" size="35" value="<?php echo $_POST['name']; ?>" class="required">
				</div>
	 			<div class="formRight">
					<label for="email">Your Email<span>*</span></label>
					<input type="text" id ="email" name="email" maxlength="35" size="35" value="<?php echo $_POST['email']; ?>" class="required">
				</div>
			</div>
			<div>
				<label for="category" class="inline-label">Category<span>*</span></label>
				<select name="category" id="category">
					<option value=""></option>
					<option value="Design Update" <?php tjg_at_is_selected($_POST['category'], "Design Update") ?>>Design Update</option>
					<option value="Feature Request" <?php tjg_at_is_selected($_POST['category'], "Feature Request") ?>>Feature Request</option>
					<option value="Site Maintenance Request" <?php tjg_at_is_selected($_POST['category'], "Site Maintenance Request") ?>>Site Maintenance</option>
					<option value="Support Request" <?php tjg_at_is_selected($_POST['category'], "Support Request") ?>>Support</option>
				</select>
			</div>
			<div>
				<label for="message">Message<span>*</span></label>
				<textarea id="message" name="message"><?php echo $_POST['message']; ?></textarea>
			</div>
			<div>
				<input type="submit" value="Submit">
					<?php	if ($mail_success) { ?>
							<span class="success-message">
								<?php echo $form_message; ?>
							</span>
					<?php } else { ?>
							<span class="error-message">
								<?php echo $form_message; ?>
							</span>
					<?php } ?>					

			</div>
		</form>
		<?php if (defined('DEVELOPER_NAME') && defined('DEVELOPER_PHONE')) { ?>
			<p class="footnote">If you have an urgent request, please call <?php echo DEVELOPER_NAME ?> at <?php echo DEVELOPER_PHONE ?></p>
		<?php }	
		echo '</div>';
	}

//	Page List widget
	function tjg_at_dashboard_list_pages() {
		$site = get_bloginfo('wpurl');
		echo '<div id="tjg-list-all-dashboard" class="group">';
		
		$allpages = get_pages('parent=0&sort_column=menu_order' ); 

		if (!function_exists("getChildren")) {
			function getChildren($page)	{
				if ($page->post_parent)	{
					$ancestors=get_post_ancestors($page->ID);
					$root=count($ancestors)-1;
					$pageparent = $ancestors[$root];
				} else {
					$pageparent = $page->ID;
				}
				$haschildren = get_pages("child_of=$pageparent&sort_column=menu_order" );
				if ($haschildren) {

					foreach ( $haschildren as $childpage ) {
						$childtitle = $childpage->post_title;
						$childID = $childpage->ID;
						$childDepth = count(get_ancestors($childID, 'page' ));
						$site = get_bloginfo('wpurl');
						$childlink =  "$site/wp-admin/post.php?post=$childID&action=edit";
						echo	'<dd class="depth-' .$childDepth . '"> <a href="' . $childlink . '">' . $childtitle . '</a></dd>';
					}
				}	
			}
		}
		
		foreach ( $allpages as $page ) {
			$title = $page->post_title;
			$theID = $page->ID;
			$site = get_bloginfo('wpurl');
			$thelink =  "$site/wp-admin/post.php?post=$theID&action=edit";
			$theslug = $page->post_name;	
			echo	'<dl class="pagelist" id="' . $theslug . '-pagelist">'.
						'<dt> <a href="' . $thelink . '">' . $title . '</a></dt>';

			getChildren($page);
			echo	"</dl>";
		}	
		echo '</div>';
	}



/**
	Removal of Dashboard widgets
**/
	function tjg_at_remove_dashboard_widgets(){
		global $wp_meta_boxes;
		global $dashboard_widget_array;
	
		foreach ( $dashboard_widget_array as $widget => $ignored )	{
			$enabled = get_option("admin-theme-$widget");
			if ( $enabled == "false" )	{
				unset( $wp_meta_boxes['dashboard']['normal']['core']["$widget"] );
				unset( $wp_meta_boxes['dashboard']['side']['core']["$widget"] );		
			}	
		}
	
	}

?>
<?php

return array(
	
	"colors" => array(
		"base-color" => array(
			"type"        => "color",
			"label"       => "Base Color",
			"allow_null"  => false,
			"classes"     => array(
				"field" => "wp-color-field less-variable",
				"label" => "label-column",
			),
			"attr"        => array(
				"data-less-var-name" => "base-color",
			),
		),
		"highlight-color" => array(
			"type"        => "color",
			"label"       => "Highlight Color",
			"allow_null"  => false,
			"classes"     => array(
				"field" => "wp-color-field less-variable",
				"label" => "label-column",
			),
			"attr"        => array(
				"data-less-var-name"   => "highlight-color",
			),
		),
		"notification-color" => array(
			"type"        => "color",
			"label"       => "Notification Color",
			"allow_null"  => false,
			"classes"     => array(
				"field" => "wp-color-field less-variable",
				"label" => "label-column",
			),
			"attr"        => array(
				"data-less-var-name"   => "notification-color",
			),
		),
		"action-color" => array(
			"type"        => "color",
			"label"       => "Action Color",
			"allow_null"  => false,
			"classes"     => array(
				"field" => "wp-color-field less-variable",
				"label" => "label-column",
			),
			"attr"        => array(
				"data-less-var-name"   => "action-color",
			),
		),
	),

	"color-schemes" => array(
		"default-scheme" => array(
			"label"     => "Set as Default Color Scheme",
			"type"      => "checkbox",	
		),
		"hide-schemes" => array(
			"label"     => "Hide Other Color Schemes",
			"type"      => "checkbox",			
		),		
	),

	"login" => array(
		"login-img" => array(
			"type"     => "media",
			"label"    => "Login Image",
			"classes"     => array(
				"label" => "label-column",
			),
		),
	),

	"dashboard" => array(
		"dashboard-widgets" => array(
			"type" => "checkbox-group",
			"choices" => array(
				"dashboard_activity" => array(
					"label" => "Activity",
					"type"  => "checkbox",
				),
				"dashboard_right_now"       => array(
					"label" => "At a Glance",
					"type"  => "checkbox",
				),
				"dashboard_quick_press"     => array(
					"label" => "Quick Draft",
					"type"  => "checkbox",
				),
				"dashboard_primary"         => array(
					"label" => "Wordpress News",
					"type"  => "checkbox",
				),
				// "dashboard_recent_comments" => array(
				// 	"label" => "Recent Comments",
				// 	"type"  => "checkbox",
				// ),
				// "dashboard_incoming_links"  => array(
				// 	"label" => "Incoming Links",
				// 	"type"  => "checkbox",
				// ),
				// "dashboard_secondary"       => array(
				// 	"label" => "Other Wordpress News",
				// 	"type"  => "checkbox",
				// ),
				// "dashboard_recent_drafts"   => array(
				// 	"label" => "Recent Drafts",
				// 	"type"  => "checkbox",
				// ),
			),
		),
	),


);
<?php
/*
  Plugin Name: Event Espresso - Calendar Table Template (EE 4.3+)
  Plugin URI: http://www.eventespresso.com
  Description: The Event Espresso Calendar Table Template adds a calendar table view to Event Espresso 4. Add [ESPRESSO_CALENDAR_TABLE_TEMPLATE] to any WordPress page/post.
  Requirements: (optional) CSS skills to customize styles, some renaming of the table columns
  Shortcode Example: [ESPRESSO_CALENDAR_TABLE_TEMPLATE]
  Shortcode Parameters: show_featured=1 (shows the featured image), table_header=0 (hides the TH row), title="Band / Artist", limit = 10, show_expired = FALSE, month = NULL, category_slug = NULL, order_by = start_date, sort = ASC
  Version: 1.0.1.rc.000
  Author: Event Espresso
  Author URI: http://www.eventespresso.com
  Copyright 2014 Event Espresso (email : support@eventespresso.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
 *
 * ------------------------------------------------------------------------
 *
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Event Espresso
 * @ copyright	(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version	 	EE4
 *
 * ------------------------------------------------------------------------
 */
// calendar_table_template version
define( 'EE_CALENDAR_TABLE_TEMPLATE_VERSION', '1.0.1.rc.000' );
define( 'EE_CALENDAR_TABLE_TEMPLATE_PLUGIN_FILE', plugin_basename( __FILE__ ));

function load_espresso_calendar_table_template() {
	if ( class_exists( 'EE_Addon' )) {
		require_once ( plugin_dir_path( __FILE__ ) . 'EE_Calendar_Table_Template.class.php' );
		EE_Calendar_Table_Template::register_addon();
	}
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_calendar_table_template' );

// End of file espresso_calendar_table_template.php
// Location: wp-content/plugins/espresso-new-addon/espresso_calendar_table_template.php

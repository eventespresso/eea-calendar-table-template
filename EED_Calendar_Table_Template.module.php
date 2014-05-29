<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/*
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Event Espresso
 * @ copyright	(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version		$VID:$
 *
 * ------------------------------------------------------------------------
 */
/**
 * Class  EED_Calendar_Table_Template
 *
 * @package			Event Espresso
 * @subpackage		espresso-new-addon
 * @author 				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Calendar_Table_Template extends EED_Module {

	/**
	 * @var 		bool
	 * @access 	public
	 */
	public static $shortcode_active = FALSE;



	 /**
	  * 	set_hooks - for hooking into EE Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks() {
		 EE_Config::register_route( 'calendar_table_template', 'EED_Calendar_Table_Template', 'run' );
	 }

	 /**
	  * 	set_hooks_admin - for hooking into EE Admin Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks_admin() {
		 // ajax hooks
		 add_action( 'wp_ajax_get_calendar_table_template', array( 'EED_Calendar_Table_Template', '_get_calendar_table_template' ));
		 add_action( 'wp_ajax_nopriv_get_calendar_table_template', array( 'EED_Calendar_Table_Template', '_get_calendar_table_template' ));
	 }





	/**
	 *    set_config
	 *
	 * @return EE_Calendar_Table_Template_Config
	 */
	protected static function _set_config(){
		return EED_Calendar_Table_Template::instance()->set_config( 'addons', 'EED_Calendar_Table_Template', 'EE_Calendar_Table_Template_Config' );
	}



	/**
	 *    _get_config
	 *
	 * @return EE_Calendar_Table_Template_Config
	 */
	protected static function _get_config(){
		$config = EED_Calendar_Table_Template::instance()->get_config( 'addons', 'EED_Calendar_Table_Template', 'EE_Calendar_Table_Template_Config' );
		return $config instanceof EE_Calendar_Table_Template_Config ? $config : EED_Calendar_Table_Template::_set_config();
	}





	 /**
	  *    run - initial module setup
	  *
	  * @access    public
	  * @param  WP $WP
	  * @return    void
	  */
	 public function run( $WP ) {
		 EED_Calendar_Table_Template::_set_config();
		 add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ));
	 }






	/**
	 * 	enqueue_scripts - Load the scripts and css
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function enqueue_scripts() {
		//Check to see if the calendar_table_template css file exists in the '/uploads/espresso/' directory
		if ( is_readable( EVENT_ESPRESSO_UPLOAD_DIR . "css/calendar_table_template.css")) {
			//This is the url to the css file if available
			wp_register_style( 'espresso_calendar_table_template', EVENT_ESPRESSO_UPLOAD_URL . 'css/espresso_calendar_table_template.css' );
		} else {
			// EE calendar_table_template style
			wp_register_style( 'espresso_calendar_table_template', EE_CALENDAR_TABLE_TEMPLATE_URL . 'css/espresso_calendar_table_template.css' );
		}
		// calendar_table_template script
		wp_register_script( 'espresso_calendar_table_template', EE_CALENDAR_TABLE_TEMPLATE_URL . 'scripts/espresso_calendar_table_template.js', array( 'jquery' ), EE_CALENDAR_TABLE_TEMPLATE_VERSION, TRUE );

		// is the shortcode or widget in play?
		if ( EED_Calendar_Table_Template::$shortcode_active ) {
			wp_enqueue_style( 'espresso_calendar_table_template' );
			wp_enqueue_script( 'espresso_calendar_table_template' );
		}
	}




	 /**
	  *    _get_calendar_table_template
	  *
	  * @access    	public
	  * @return    	string
	  */
	public static function _get_calendar_table_template(  ) {
		// get calendar_table_template options
		$config = EED_Calendar_Table_Template::_get_config();
		return '';
	}




	 /**
	  *    display_calendar_table_template
	  *
	  * @access    	public
	  * @return    	string
	  */
	public function display_calendar_table_template(  ) {
		// get calendar_table_template options
		$config = EED_Calendar_Table_Template::_get_config();
		return '';
	}



	/**
	 *		@ override magic methods
	 *		@ return void
	 */
	public function __set($a,$b) { return FALSE; }
	public function __get($a) { return FALSE; }
	public function __isset($a) { return FALSE; }
	public function __unset($a) { return FALSE; }
	public function __clone() { return FALSE; }
	public function __wakeup() { return FALSE; }
	public function __destruct() { return FALSE; }

 }
// End of file EED_Calendar_Table_Template.module.php
// Location: /wp-content/plugins/espresso-new-addon/EED_Calendar_Table_Template.module.php

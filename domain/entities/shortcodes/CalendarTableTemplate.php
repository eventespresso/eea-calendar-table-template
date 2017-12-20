<?php

namespace EventEspresso\CalendarTableTemplate\domain\entities\shortcodes;

use EEH_Template;
use EventEspresso\core\services\shortcodes\EspressoShortcode;
// use EventEspresso\TableTemplate\domain\queries\EventsTableTemplateQuery;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EventsTableTemplate
 * new shortcode class for the ESPRESSO_CALENDAR_TABLE_TEMPLATE shortcode
 *
 * @package       Event Espresso
 * @author        Brent Christensen
 * @since         $VID:$
 */
class CalendarTableTemplate extends EspressoShortcode
{



    /**
     * the actual shortcode tag that gets registered with WordPress
     *
     * @return string
     */
    public function getTag()
    {
        return 'ESPRESSO_CALENDAR_TABLE_TEMPLATE';
    }



    /**
     * the length of time in seconds to cache the results of the processShortcode() method
     * 0 means the processShortcode() results will NOT be cached at all
     *
     * @return int
     */
    public function cacheExpiration()
    {
        return 10;
        // return MINUTE_IN_SECONDS * 15;
    }



    /**
     * a place for adding any initialization code that needs to run prior to wp_header().
     * this may be required for shortcodes that utilize a corresponding module,
     * and need to enqueue assets for that module
     *
     * @return void
     */
    public function initializeShortcode()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueScriptsAndStyles'), 10);
    }




    /**
     *  enqueueScriptsAndStyles - Load the scripts and css
     *
     *  @access     public
     *  @return     void
     */
    public function enqueueScriptsAndStyles()
    {
        //Check to see if the calendar_table_template css file exists in the '/uploads/espresso/' directory
        if (is_readable(EVENT_ESPRESSO_UPLOAD_DIR . 'css' . DS . 'espresso_calendar_table_template.css')) {
            //This is the url to the css file if available
            wp_register_style(
                'espresso_calendar_table_template', 
                EVENT_ESPRESSO_UPLOAD_URL . 'css' . DS . 'espresso_calendar_table_template.css'
            );
        } else {
            // EE calendar_table_template style
            wp_register_style(
                'espresso_calendar_table_template', 
                EE_CALENDAR_TABLE_TEMPLATE_URL . 'css' . DS . 'espresso_calendar_table_template.css'
            );
        }
        // calendar_table_template script
        wp_register_script(
            'espresso_calendar_table_template', 
            EE_CALENDAR_TABLE_TEMPLATE_URL . 'scripts' . DS . 'espresso_calendar_table_template.js', 
            array( 'jquery' ), 
            EE_CALENDAR_TABLE_TEMPLATE_VERSION, true
        );
        
        // enqueue
        wp_enqueue_style('espresso_calendar_table_template');
        wp_enqueue_script('espresso_calendar_table_template');
    }



    /**
     * array for defining custom attribute sanitization callbacks,
     * where keys match keys in your attributes array,
     * and values represent the sanitization function you wish to be applied to that attribute.
     * So for example, if you had an integer attribute named "event_id"
     * that you wanted to be sanitized using absint(),
     * then you would return the following:
     *      array('event_id' => 'absint')
     * Entering 'skip_sanitization' for the callback value
     * means that no sanitization will be applied
     * on the assumption that the attribute
     * will be sanitized at some point... right?
     * You wouldn't pass around unsanitized attributes would you?
     * That would be very Tom Foolery of you!!!
     *
     * @return array
     */
    protected function customAttributeSanitizationMap()
    {
        return array(
            'category_slug' => 'skip_sanitization',
            'show_expired'  => 'skip_sanitization',
            'order_by'      => 'skip_sanitization',
            'month'         => 'skip_sanitization',
            'sort'          => 'skip_sanitization',
        );
    }

    /**
     * callback that runs when the shortcode is encountered in post content.
     * IMPORTANT !!!
     * remember that shortcode content should be RETURNED and NOT echoed out
     *
     * @param array $attributes
     * @return string
     */
    public function processShortcode($attributes = array())
    {
        // grab attributes and merge with defaults
        $attributes = $this->getAttributes($attributes);
        // now filter the array of locations to search for templates
        add_filter(
            'FHEE__EEH_Template__locate_template__template_folder_paths',
            array($this, 'templateFolderPaths')
        );
        // load our template
        $calendar_table_template = EEH_Template::get_template_part(
            'loop',
            'espresso_events-calendar-table.template',
            array('attributes' => $attributes)
        );
        return $calendar_table_template;
    }



    /**
     * merge incoming attributes with filtered defaults
     *
     * @param array $attributes
     * @return array
     */
    private function getAttributes(array $attributes)
    {
        // make sure $attributes is an array
        $attributes = array_merge(
            // defaults
            array(
                'title'             => null,
                'limit'             => 10,
                'show_expired'      => false,
                'month'             => null,
                'category_slug'     => null,
                'order_by'          => 'DTT_EVT_start',
                'sort'              => 'ASC',
                'show_featured'     => '0',
                'table_header'      => '0',
                'button_text'       => null,
                'sold_out_btn_text' => null,
                'fallback_img'      => null
            ),
            (array)$attributes
        );
        return $attributes;
    }



    /**
     * @param array $template_folder_paths
     * @return    array
     */
    public function templateFolderPaths($template_folder_paths = array())
    {
        $template_folder_paths[] = EE_CALENDAR_TABLE_TEMPLATE_TEMPLATES;
        return $template_folder_paths;
    }




}

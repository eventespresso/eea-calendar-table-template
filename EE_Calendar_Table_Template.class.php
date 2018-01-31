<?php 
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\collections\CollectionInterface; 
use EventEspresso\core\services\loaders\Loader;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\shortcodes\ShortcodeInterface;

defined('EVENT_ESPRESSO_VERSION') || exit; 

// define the plugin directory path and URL
define('EE_CALENDAR_TABLE_TEMPLATE_PATH', plugin_dir_path(__FILE__));
define('EE_CALENDAR_TABLE_TEMPLATE_URL', plugin_dir_url(__FILE__));
define('EE_CALENDAR_TABLE_TEMPLATE_TEMPLATES', EE_CALENDAR_TABLE_TEMPLATE_PATH . DS . 'templates');

/**
 * ------------------------------------------------------------------------
 *
 * Class  EE_Calendar_Table_Template
 *
 * @package         Event Espresso
 * @subpackage      espresso-new-addon
 * @author          Brent Christensen
 * @ version        $VID:$
 *
 * ------------------------------------------------------------------------
 */

class EE_Calendar_Table_Template extends EE_Addon
{

    /**
     * @var LoaderInterface $loader ;
     */
    private static $loader;



    /**
     * EE_Calendar_Table_Template constructor.
     *
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader = null)
    {
        EE_Calendar_Table_Template::$loader = $loader;
        parent::__construct();
    }



    /**
     * @return LoaderInterface
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     */
    public static function loader()
    {
        if (! EE_Calendar_Table_Template::$loader instanceof LoaderInterface) {
            EE_Calendar_Table_Template::$loader = LoaderFactory::getLoader();
        }
        return EE_Calendar_Table_Template::$loader;
    }

    public static function register_addon()
    {
        
        // register addon via Plugin API
        EE_Register_Addon::register(
            'Calendar_Table_Template',
            array(
                'version'          => EE_CALENDAR_TABLE_TEMPLATE_VERSION,
                'min_core_version' => '4.9.40',
                'base_path'        => EE_CALENDAR_TABLE_TEMPLATE_PATH,
                'main_file_path'   => EE_CALENDAR_TABLE_TEMPLATE_PATH . 'espresso-calendar-table-template.php',
                'namespace'        => array(
                    'FQNS' => 'EventEspresso\CalendarTableTemplate',
                    'DIR'  => __DIR__,
                ),
                //'admin_callback' => 'additional_admin_hooks',
                'autoloader_paths' => array(
                    'EE_Calendar_Table_Template'    => EE_CALENDAR_TABLE_TEMPLATE_PATH . 'EE_Calendar_Table_Template.class.php',
                ),
                //The below is for if plugin update engine is being used for auto-updates. not needed if PUE is not being used.
                'pue_options'      => array(
                    'pue_plugin_slug' => 'espresso_calendar_table_template',
                    'plugin_basename' => EE_CALENDAR_TABLE_TEMPLATE_PLUGIN_FILE,
                    'checkPeriod'     => '24',
                    'use_wp_update'   => false
                )
            )
        );
    }



    /**
     * Register things that have to happen early in loading.
     */
    public function after_registration()
    {
        EE_Dependency_Map::register_dependencies(
            'EventEspresso\CalendarTableTemplate\domain\entities\shortcodes\CalendarTableTemplate',
            array(
                'EventEspresso\core\services\cache\PostRelatedCacheManager' => EE_Dependency_Map::load_from_cache,
            )
        );
        // register our activation hook
        register_activation_hook(__FILE__, array($this, 'set_activation_indicator_option'));
        add_filter(
            'FHEE__EventEspresso_core_services_shortcodes_ShortcodesManager__registerShortcodes__shortcode_collection',
            array($this, 'registerShortcodes')
        );
    }



    /**
     * @param CollectionInterface|ShortcodeInterface[] $shortcodeCollection
     * @return CollectionInterface|ShortcodeInterface[]
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    public function registerShortcodes(CollectionInterface $shortcodeCollection)
    {
        $shortcodeCollection->add(
            EE_Calendar_Table_Template::loader()->getNew(
                'EventEspresso\CalendarTableTemplate\domain\entities\shortcodes\CalendarTableTemplate'
            )
        );
        return $shortcodeCollection;
    }
 


    /**
     *  additional_admin_hooks
     *
     *  @access     public
     *  @return     void
     */
    public function additional_admin_hooks()
    {
        // is admin and not in M-Mode ?
        if (is_admin() && ! EE_Maintenance_Mode::instance()->level()) {
            add_filter('plugin_action_links', array( $this, 'plugin_actions' ), 10, 2);
        }
    }




    /**
     * plugin_actions
     *
     * Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
     * @param $links
     * @param $file
     * @return array
     */
    public function plugin_actions($links, $file)
    {
        if ($file == EE_CALENDAR_TABLE_TEMPLATE_PLUGIN_FILE) {
            // before other links
            array_unshift($links, '<a href="admin.php?page=espresso_calendar_table_template">' . __('Settings') . '</a>');
        }
        return $links;
    }
}
// End of file EE_Calendar_Table_Template.class.php
// Location: wp-content/plugins/espresso-new-addon/EE_Calendar_Table_Template.class.php


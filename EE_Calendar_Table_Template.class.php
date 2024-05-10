<?php

/**
 * Class  EE_Calendar_Table_Template
 *
 * @package         Event Espresso
 * @subpackage      espresso-new-addon
 * @author          Brent Christensen
 * @ version        $VID:$
 */
class EE_Calendar_Table_Template extends EE_Addon
{
    /**
     * @return void
     * @throws EE_Error
     */
    public static function register_addon()
    {
        if (! defined('EE_CALENDAR_TABLE_TEMPLATE_PATH')) {
            define('EE_CALENDAR_TABLE_TEMPLATE_PATH', plugin_dir_path(__FILE__));
            define('EE_CALENDAR_TABLE_TEMPLATE_URL', plugin_dir_url(__FILE__));
            define('EE_CALENDAR_TABLE_TEMPLATE_TEMPLATES', EE_CALENDAR_TABLE_TEMPLATE_PATH . DS . 'templates');
        }

        EE_Register_Addon::register(
            'Calendar_Table_Template',
            [
                'version' => EE_CALENDAR_TABLE_TEMPLATE_VERSION,
                'min_core_version' => '4.3.0',
                'base_path' => EE_CALENDAR_TABLE_TEMPLATE_PATH,
                'main_file_path' => EE_CALENDAR_TABLE_TEMPLATE_PATH . 'espresso-calendar-table-template.php',
                // 'admin_callback' => 'additional_admin_hooks',
                'autoloader_paths' => [
                    'EE_Calendar_Table_Template' => EE_CALENDAR_TABLE_TEMPLATE_PATH . 'EE_Calendar_Table_Template.class.php',
                ],
                'shortcode_paths' => [EE_CALENDAR_TABLE_TEMPLATE_PATH . 'EES_Espresso_Calendar_Table_Template.shortcode.php'],
                // The below is for if plugin update engine is being used for auto-updates. not needed if PUE is not being used.
                'pue_options' => [
                    'pue_plugin_slug' => 'espresso_calendar_table_template',
                    'plugin_basename' => EE_CALENDAR_TABLE_TEMPLATE_PLUGIN_FILE,
                    'checkPeriod'     => '24',
                    'use_wp_update'   => false,
                ],
            ]
        );
    }


    /**
     *  additional_admin_hooks
     *
     * @access     public
     * @return     void
     */
    public function additional_admin_hooks()
    {
        // is admin and not in M-Mode ?
        if (
            is_admin()
            && (
                class_exists('EventEspresso\core\domain\services\database\MaintenanceStatus')
                && EventEspresso\core\domain\services\database\MaintenanceStatus::isDisabled()
            ) || ! EE_Maintenance_Mode::instance()->level()
        ) {
            add_filter('plugin_action_links', [$this, 'plugin_actions'], 10, 2);
        }
    }


    /**
     * plugin_actions
     *
     * Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
     *
     * @param $links
     * @param $file
     * @return array
     */
    public function plugin_actions($links, $file)
    {
        if ($file == EE_CALENDAR_TABLE_TEMPLATE_PLUGIN_FILE) {
            // before other links
            array_unshift(
                $links,
                '<a href="admin.php?page=espresso_calendar_table_template">' . __('Settings') . '</a>'
            );
        }
        return $links;
    }
}
// End of file EE_Calendar_Table_Template.class.php
// Location: wp-content/plugins/espresso-new-addon/EE_Calendar_Table_Template.class.php

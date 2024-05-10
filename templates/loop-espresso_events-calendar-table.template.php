<?php
// Options
$date_option = get_option('date_format');
$time_option = get_option('time_format');
$temp_month = '';
$button_text = !isset($attributes['button_text']) ?
                    esc_html__('View Details', 'event_espresso') :
                    $attributes['button_text'];
$sold_out_btn_text = $attributes['sold_out_btn_text'];
$sold_out_btn_text = !isset($sold_out_btn_text) ? esc_html__('Sold Out', 'event_espresso') : $sold_out_btn_text;

$category_id_or_slug = $attributes['category_slug'];
$title = $attributes['title'];
$table_header = filter_var($attributes['table_header'], FILTER_VALIDATE_BOOLEAN);
$show_expired = filter_var($attributes['show_expired'], FILTER_VALIDATE_BOOLEAN);
$show_featured = filter_var($attributes['show_featured'], FILTER_VALIDATE_BOOLEAN);
$fallback_img = $attributes['fallback_img'];

if ($category_id_or_slug) {
    // Allow for multiple categories
    $category_id_or_slug = explode(',', $category_id_or_slug);
    foreach ($category_id_or_slug as $key => $value) {
        // sanitize all of the values
        $category_id_or_slug[ $key ] = sanitize_key($value);
    }
    // Set the category (or categories) within the query
    $where['OR*category'] = array(
        'Event.Term_Taxonomy.Term.slug'    => array( 'IN', $category_id_or_slug),
        'Event.Term_Taxonomy.Term.term_id' => array( 'IN', $category_id_or_slug)
    );
    // Parent category passed as single category?
    if (count($category_id_or_slug) == 1) {
        // Pull the category id or slug from the array
        $ee_term_id = $category_id_or_slug[0];
        // Check if we have an ID or a slug
        if (! is_int($ee_term_id)) {
            // Not an int so must be the slug
            $ee_term = get_term_by('slug', $ee_term_id, 'espresso_event_categories');
            $ee_term_id = $ee_term instanceof WP_Term
                ? $ee_term->term_id
                : null;
        }
        // Check we have a term_id to use before adding to the where params
        if ($ee_term_id) {
            $where['OR*category']['Event.Term_Taxonomy.parent'] = $ee_term_id;
        }
    }
}

$public_event_stati = EEM_Event::instance()->public_event_stati();
$where['Event.status'] = array(
    'IN',
    apply_filters(
        'FHEE__loop_espresso_events_calendar_table_template__public_event_stati',
        $public_event_stati
    )
);
if ($show_expired == false) {
    $where['DTT_EVT_start*3'] = array('>=',current_time('mysql'));
}
$where = apply_filters(
    'FHEE__loop_espresso_events_calendar_table_template__where_params',
    $where,
    $show_expired
);

// run the query
if (class_exists('EE_Registry')) :
    $datetimes = EE_Registry::instance()->load_model('Datetime')->get_all(array(
        $where,
        'limit' => $attributes['limit'],
        'order_by' => $attributes['order_by'],
        'order' => $attributes['sort'],
    ));
// the loop
    if (! empty($datetimes)) {
        // allow other stuff
        do_action('AHEE__espresso_calendar_table_template_template__before_loop');
        echo '<table class="cal-table-list">';

        foreach ($datetimes as $datetime) {
            $full_month = $datetime->start_date('M');

            if ($temp_month != $full_month) {
                ?>
                <tr class="cal-header-month">
                <th class="cal-header-month-name" id="calendar-header-<?php echo $full_month; ?>" colspan="3">
                    <?php echo date_i18n('F', strtotime($full_month)); ?>
                </th>
                </tr>
                <?php
                if (isset($table_header) && $table_header == '1') { ?>
                    <tr class="cal-header">
                        <th><?php echo !isset($show_featured) || $show_featured === 'false' ? __('Date', 'event_espresso') :  '' ?></th>
                        <th class="th-event-info">
                            <?php if (isset($title)) {
                                echo $title;
                            } else {
                                esc_html_e('Band / Artist', 'event_espresso');
                            } ?></th>
                        <th class="th-tickets"><?php esc_html_e('Tickets', 'event_espresso'); ?></th>
                    </tr>
                <?php
                }
                $temp_month = $full_month;
            }

            $event = $datetime->event();
            if ($event instanceof EE_Event) {
                EEH_Template::get_template_part(
                    'content',
                    'espresso_events-calendar.template',
                    array(
                        'datetime'          => $datetime,
                        'event'             => $event,
                        'date_option'       => $date_option,
                        'time_option'       => $time_option,
                        'show_featured'     => $show_featured,
                        'table_header'      => $table_header,
                        'button_text'       => $button_text,
                        'sold_out_btn_text' => $sold_out_btn_text,
                        'fallback_img'      => $fallback_img
                    )
                );
            }
        }
        echo '</table>';
        // allow moar other stuff
        do_action('AHEE__espresso_calendar_table_template_template__after_loop');
    }
endif; ?>
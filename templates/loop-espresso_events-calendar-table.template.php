<?php

// the loop
if (! empty($datetimes)) {
    // allow other stuff

    $page = isset($_GET['calpage']) ? intval($_GET['calpage']-1) : 0;
    $number_of_pages = intval(count($datetimes)/$attributes['per_page'])+1;

    do_action('AHEE__espresso_calendar_table_template_template__before_loop', $datetimes);
    ?>
  <div id="lazyload">
    <table class="cal-table-list page" id="p<?php echo $page; ?>">
    <?php
    foreach (array_slice($datetimes, $attributes['per_page']*$page, $attributes['per_page']) as $datetime) {
        $full_month = $datetime->start_date('M');

        if ($options['temp_month'] != $full_month) {
            ?>
            <tr class="cal-header-month">
            <th class="cal-header-month-name" id="calendar-header-<?php echo $full_month; ?>" colspan="3">
                <?php echo date_i18n('F', strtotime("first day of " . $full_month)); ?>
            </th>
            </tr>
            <?php
            if ($attributes['table_header'] && $attributes['table_header'] == '1') { ?>
                <tr class="cal-header">
                    <th><?php esc_html_e('Date', 'event_espresso'); ?></th>
                    <th>
                        <?php if ($attributes['title']) {
                            esc_html_e($attributes['title']);
                        } else {
                            esc_html_e('Band / Artist', 'event_espresso');
                        } ?></th>
                </tr>
            <?php
            }
            $options['temp_month'] = $full_month;
        }

        $event = $datetime->event();
        if ($event instanceof EE_Event) {
            EEH_Template::get_template_part(
                'content',
                'espresso_events-calendar.template',
                array(
                    'datetime'          => $datetime,
                    'event'             => $event,
                    'date_option'       => $options['date_option'],
                    'time_option'       => $options['time_option'],
                    'show_featured'     => $attributes['show_featured'],
                    'table_header'      => $attributes['table_header'],
                    'button_text'       => $attributes['button_text'],
                    'sold_out_btn_text' => $attributes['sold_out_btn_text'],
                    'fallback_img'      => $attributes['fallback_img']
                )
            );
        }
    }
    ?> 
    </table>
  </div>
    <?php
    // allow moar other stuff
    do_action('AHEE__espresso_calendar_table_template_template__after_loop', $datetimes);
}

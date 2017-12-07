<?php
//Check if external URL
$external_url = $event->external_url();

//Create the URL to the event
$registration_url = !empty($external_url) ? $event->external_url() : $event->get_permalink();

//Create the registrer now button
$live_button      = '<a id="a_register_link-'.$event->id().'" href="'.$registration_url.'"><img class="buytix_button" src="'.EE_CALENDAR_TABLE_TEMPLATE_URL . 'images' . DS .'register-now.png" alt="Buy Tickets"></a>';

//Get the venue for this event

$venues = $event->venues();
$venue = reset($venues);

if ($venue instanceof EE_Venue) {
    $venue_name = $venue->name();
    $venue_address = $venue->address();
    $venue_city = $venue->city();
    if ($venue->state_obj() instanceof EE_State) {
        $state = $venue->state_obj()->name();
    }
} else {
    $venue_name = '';
    $venue_address = '';
    $venue_city = '';
    $state = '';
}

//Start the table
echo '<tr class="event-row" id="event-row-' . $event->id() . '-' . $datetime->id() . '">';
if (isset($show_featured) && $show_featured == true && has_post_thumbnail($event->id())) : ?>
    <td class="td-fet-image">
        <div class="featured-image">
        <?php echo $event->feature_image('thumbnail'/*, array('align'=>'left', 'style'=>'margin:10px; border:1px solid #ccc')*/); ?>
        </div>
    </td>
<?php else : ?>
    <td class="td-date-holder">
        <div class="dater">
            <div class="cal-day-title"><?php $datetime->e_start_date('l'); ?></div>
            <div class="cal-day-num"><?php $datetime->e_start_date('j'); ?></div>
            <div><span><?php $datetime->e_start_date('M'); ?></span></div>
        </div>
    </td>
<?php endif;

echo '<td class="td-event-info"><span class="event-title"><a href="'. $registration_url .'">'.$event->name().'</a></span>';
// Start date/time
echo '<div class="event-time-info">';
$datetime->e_start_date_and_time($date_option, $time_option);
echo '</div>';
// Venue info
echo '<div class="venue-info">';
echo (isset($venue_name) && !empty($venue_name)) ? $venue_name : '';
echo (isset($venue_city) && !empty($venue_city)) ? ', '.$venue_city :'';
echo (isset($state) && !empty($state)) ? ', '.$state : '';
echo '</div>';
//Event description
$event_desc = explode('<!--more-->', $event->description_filtered());
$event_desc = array_shift($event_desc);
echo '<div class="description">'.$event_desc.'</div>';
echo '</td>';
echo '<td class="td-event-register">'.$live_button.'</td>';
echo '</tr>';


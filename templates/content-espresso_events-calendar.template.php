<?php
//Check if external URL
$external_url       = $event->external_url();

//Create the URL to the event
$registration_url   = !empty($external_url) ? $external_url : $event->get_permalink();
$ext_link_class		= !empty($external_url) ? 'external-url' : '';
//Button text
$live_button 		= '<a class="button btn a-register-link '.$ext_link_class.'" href="'.$registration_url.'">'.$button_text.'</a>';
if ( $event->is_sold_out() ) {
	$live_button	= '<a class="button btn a-register-link-sold-out a-register-link" href="'.$registration_url.'">'.$sold_out_btn_text.'</a>';
}

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
if (isset($show_featured) && $show_featured == true) : ?>
    <td class="td-fet-image">
        <div class="featured-image">
        <?php if (has_post_thumbnail($event->id())) : 
        	echo '<div class="has-featured-image">';
        	echo $event->feature_image('thumbnail'/*, array('align'=>'left', 'style'=>'margin:10px; border:1px solid #ccc')*/); 
    	elseif (isset($fallback_img)) :
    		echo '<div class="has-fallback-image">';
    		echo '<img src="' . esc_url($fallback_img) . '">';
    	elseif (get_theme_mod('custom_logo')) :
    		echo '<div class="has-image-logo">';
    		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
			echo '<img src="' . esc_url($logo[0]) . '">';
		else :
			echo '<div class="has-image-placeholder">';
		endif;
		?>
				<div class="fet-day-num"><?php $datetime->e_start_date('j'); ?></div>
			</div>
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
?>
<td class="td-event-info">
	<div class="info-wrapper">
		<div class="info-main">
			<span class="event-title">
				<a href="<?php echo $registration_url; ?>"><?php echo $event->name(); ?></a>
			</span>
			<div class="event-time-info">
				<?php $datetime->e_start_date_and_time($date_option, $time_option); // Start date/time ?>
			</div><!-- end .event-time-info -->
			<div class="venue-info">
				<?php $venue_info  = (isset($venue_name) && !empty($venue_name)) ? $venue_name : '';
					  $venue_info .= (isset($venue_city) && !empty($venue_city)) ? ', '.$venue_city :'';
					  $venue_info .= (isset($state) && !empty($state)) ? ', '.$state : '';
					  echo $venue_info;
				?>
			</div><!-- end .venue-info -->
			<?php //Event description
			$event_desc = explode('<!--more-->', $event->description_filtered());
			$event_desc = array_shift($event_desc);
			?>
			<div class="description"><?php echo $event_desc; ?></div>
		</div><!-- end .info-main -->
		<div class="status-action-text"><?php echo $live_button; ?></div>
	</div><!-- end .info-wrapper -->
</td>
</tr>


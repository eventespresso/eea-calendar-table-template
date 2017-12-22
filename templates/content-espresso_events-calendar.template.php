<?php

$eventLinks = new EventEspresso\CalendarTableTemplate\presentation\helpers\EventLinks($event, $button_text, $sold_out_btn_text);
$venueInfo  = new EventEspresso\CalendarTableTemplate\presentation\helpers\VenueInfo($event);

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
				<a href="<?php echo $eventLinks->getUrl(); ?>"><?php echo $event->name(); ?></a>
			</span>
			<div class="event-time-info">
				<?php $datetime->e_start_date_and_time($date_option, $time_option); // Start date/time ?>
			</div><!-- end .event-time-info -->
			<div class="venue-info">
				<?php echo $venueInfo->getVenue(); ?>
			</div><!-- end .venue-info -->
			<?php //Event description
			$event_desc = explode('<!--more-->', $event->description_filtered());
			$event_desc = array_shift($event_desc);
			?>
			<div class="description"><?php echo $event_desc; ?></div>
		</div><!-- end .info-main -->
		<div class="status-action-text"><?php echo $eventLinks->renderHtml(); ?></div>
	</div><!-- end .info-wrapper -->
</td>
</tr>


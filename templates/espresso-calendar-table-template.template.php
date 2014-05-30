<h1>this is the espresso-calendar-table-template.php file</h1>
<?php
if ( have_posts() ) :
	// allow other stuff
	do_action( 'AHEE__espresso_calendar_table_template_template__before_loop' );
	echo '<table class="cal-table-list">';
	// Start the Loop.
	while ( have_posts() ) : the_post();
		// Include the post TYPE-specific template for the content.
		global $post;
		d( $post );
		echo '<tr class="event-row" id="event-row-'. $post->ID .'">';
		echo '<td class="td-event-info"><span class="event-title"><a href="'. $post->guid .'">'.$post->post_title.'</a></span>';
		echo '<p>'.__('When:', 'event_espresso'). ' '. date(get_option('date_format'). ' '.get_option('time_format'), strtotime($post->DTT_EVT_start)). '<br />' .array_shift(explode('<!--more-->', $post->post_content)).'</p>';
		echo '</td>';
		echo '</tr>';
	endwhile;
	echo '</table>';
	// allow moar other stuff
	do_action( 'AHEE__espresso_calendar_table_template_template__after_loop' );

else :
	// If no content, include the "No posts found" template.
	espresso_get_template_part( 'content', 'none' );

endif;
<h1>this is the espresso-calendar-table-template.php file</h1>
<?php
if ( have_posts() ) :
	// allow other stuff
	do_action( 'AHEE__espresso_calendar_table_template_template__before_loop' );
	// Start the Loop.
	while ( have_posts() ) : the_post();
		// Include the post TYPE-specific template for the content.
		global $post;
		d( $post );
	endwhile;
	// allow moar other stuff
	do_action( 'AHEE__espresso_calendar_table_template_template__after_loop' );

else :
	// If no content, include the "No posts found" template.
	espresso_get_template_part( 'content', 'none' );

endif;
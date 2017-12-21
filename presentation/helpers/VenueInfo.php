<?php

namespace EventEspresso\CalendarTableTemplate\presentation\helpers;

class VenueInfo
{

	public function getVenue($event)
	{	
		$venues = $event->venues();
		$venue = reset($venues);

		if ($venue instanceof \EE_Venue) {
		    $venue_name = $venue->name();
		    $venue_city = $venue->city();
		    if ($venue->state_obj() instanceof \EE_State) {
		        $state = $venue->state_obj()->name();
		    }
		} else {
		    $venue_name = '';
		    $venue_city = '';
		    $state = '';
		}
		$venue_info  = (isset($venue_name) && !empty($venue_name)) ? $venue_name : '';
		$venue_info .= (isset($venue_city) && !empty($venue_city)) ? ', '.$venue_city :'';
		$venue_info .= (isset($state) && !empty($state)) ? ', '.$state : '';
		return $venue_info;

	}
	
}

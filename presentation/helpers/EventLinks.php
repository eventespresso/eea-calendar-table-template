<?php

namespace EventEspresso\CalendarTableTemplate\presentation\helpers;

class EventLinks
{

	public function externalUrl($event)
	{
		return $event->external_url();
	}

	public function getUrl($event)
	{
		//Create the URL to the event
		$registration_url = !empty($this->externalUrl($event)) ? $this->externalUrl($event) : $event->get_permalink();
		return $registration_url;
	}

	public function getExtUrlClass($event)
	{
		//Check if external URL
		$ext_link_class	= !empty($this->externalUrl($event)) ? 'external-url' : '';
		return $ext_link_class;
	}

	public function renderHtml($event, $button_text, $sold_out_btn_text)
	{
		//Button text
		$live_button = '<a class="button btn a-register-link more-link '.$this->getExtUrlClass($event).'" href="'.$this->getUrl($event).'">'.$button_text.'</a>';
		if ( $event->is_sold_out() ) {
			$live_button = '<a class="button btn a-register-link-sold-out a-register-link more-link" href="'.$this->getUrl($event).'">'.$sold_out_btn_text.'</a>';
		}	
		return $live_button;	
	}

}
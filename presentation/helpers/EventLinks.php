<?php

namespace EventEspresso\CalendarTableTemplate\presentation\helpers;

class EventLinks
{

	protected $_event;

	public $button_text;

	public $sold_out_btn_text;

	public function __construct(\EE_Event $event, $button_text, $sold_out_btn_text)
	{
		$this->_event = $event;
		$this->button_text = $button_text;
		$this->sold_out_btn_text = $sold_out_btn_text;
	}

	public function externalUrl()
	{
		return $this->_event->external_url();
	}

	public function getUrl()
	{
		//Create the URL to the event
		$registration_url = !empty($this->externalUrl()) ? $this->externalUrl() : $this->_event->get_permalink();
		return $registration_url;
	}

	public function getExtUrlClass()
	{
		//Check if external URL
		$ext_link_class	= !empty($this->externalUrl()) ? 'external-url' : '';
		return $ext_link_class;
	}

	public function renderHtml()
	{
		//Button text
		$live_button = '<a class="button btn a-register-link more-link '.$this->getExtUrlClass().'" href="'.$this->getUrl().'">'.$this->button_text.'</a>';
		if ( $this->_event->is_sold_out() ) {
			$live_button = '<a class="button btn a-register-link-sold-out a-register-link more-link" href="'.$this->getUrl().'">'.$this->sold_out_btn_text.'</a>';
		}	
		return $live_button;	
	}

}
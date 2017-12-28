<?php

namespace EventEspresso\CalendarTableTemplate\presentation\helpers;

class FeaturedImage
{

    protected $_datetime;

    protected $_event;

    public $fallback_img;

    public function __construct(\EE_Datetime $datetime, \EE_Event $event, $fallback_img)
    {
        $this->_datetime    = $datetime;
        $this->_event       = $event;
        $this->fallback_img = $fallback_img;
    }

    public function renderHtml()
    {   
        if (has_post_thumbnail($this->_event->id())) { 
            $html  = '<div class="has-featured-image">';
            $html .= $this->_event->feature_image('thumbnail'); 
        } elseif (isset($this->fallback_img)) {
            $html  = '<div class="has-fallback-image">';
            $html .= '<img src="' . esc_url($this->fallback_img) . '">';
        } elseif (get_theme_mod('custom_logo')) {
            $logo  = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
            $html  =  '<div class="has-image-logo">';
            $html .=  '<img src="' . esc_url($logo[0]) . '">';
        } else {
            $html  = '<div class="has-image-placeholder">';
        }
        
        $html .= '<div class="fet-day-num">' . $this->_datetime->start_date('j') . '</div>';
        $html .= '</div>';      

        return $html;
    }
    
}
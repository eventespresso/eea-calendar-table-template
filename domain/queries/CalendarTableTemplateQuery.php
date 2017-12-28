<?php

namespace EventEspresso\CalendarTableTemplate\domain\queries;

use EventEspresso\TableTemplate\domain\queries\helpers\WhereParams;

defined('EVENT_ESPRESSO_VERSION') || exit;

class CalendarTableTemplateQuery
{


    private $_limit        = 10;

    private $_show_expired = false;

    private $_month;

    private $_category_slug;

    private $_order_by;

    private $_sort;




    /**
     * @param array $attributes
     */
    public function __construct($attributes = array())
    {
        foreach ($attributes as $key => $value) {
            $property = '_' . $key;
           	if (property_exists($this, $property)) {
                // set the property value
                $this->{$property} = $value;
            }
        }                  	
    }



    public function query()
    {
        $datetimes = array();

        $category_id_or_slug = $this->_category_slug;
        if ( $category_id_or_slug ) {
            //Allow for multiple categories
            $category_id_or_slug = explode( ',', $category_id_or_slug );
            foreach ($category_id_or_slug as $key => $value) {
                //sanitize all of the values
                $category_id_or_slug[$key] = sanitize_key($value);
            }
            //Set the category (or categories) within the query
            $where['OR*category'] = array(
                'Event.Term_Taxonomy.Term.slug'    => array( 'IN', $category_id_or_slug),
                'Event.Term_Taxonomy.Term.term_id' => array( 'IN', $category_id_or_slug)
            );
            //Parent category passed as single category?
            if( count($category_id_or_slug) == 1 ) {
                //Pull the category id or slug from the array
                $ee_term_id = $category_id_or_slug[0];
                //Check if we have an ID or a slug
                if(! is_int($ee_term_id) ) {
                    //Not an int so must be the slug
                    $ee_term = get_term_by('slug', $ee_term_id, 'espresso_event_categories');
                    $ee_term_id = $ee_term instanceof WP_Term
                        ? $ee_term->term_id
                        : null;
                }
                //Check we have a term_id to use before adding to the where params
                if( $ee_term_id ) {
                    $where['OR*category']['Event.Term_Taxonomy.parent'] = $ee_term_id;
                }
            }
        }

        $public_event_stati = \EEM_Event::instance()->public_event_stati();
        $where['Event.status'] = array(
            'IN',
            apply_filters(
                'FHEE__loop_espresso_events_calendar_table_template__public_event_stati',
                $public_event_stati
            )
        );
        if ($this->_show_expired == false) {
            $where['DTT_EVT_start*3'] = array('>=',current_time('mysql'));
        }
        $where = apply_filters(
            'FHEE__loop_espresso_events_calendar_table_template__where_params',
            $where,
            $this->_show_expired
        );
        if(class_exists('\EE_Registry')) {
            $datetimes = \EE_Registry::instance()->load_model('Datetime')->get_all(
            array(
                $where,
                'limit'    => $this->_limit,
                'order_by' => $this->_order_by,
                'order'    => $this->_sort
            )
        );
        }
        return $datetimes;
    }



}

<?php
add_action('vtt-search-folders', 'uncc_variations_add_variations_folder');
add_action('rss2_item', 'UNCC_event_location_rss');
add_filter('body_class', 'UNCC_event_body_class');

require_once( __DIR__ . '/widget/events-widget.php' );
UNCC_WidgetEventsListingControl::register_widget();
UNCC_WidgetEventsListingControl::register_shortcode();

/**
 * Add the plugin folder the list of folders used by VTT to determine available variations.
 */
if (!function_exists('uncc_variations_add_variations_folder')):

    function uncc_variations_add_variations_folder() {
	global $vtt_config;
	$vtt_config->add_search_folder(__DIR__, 6);
    }

endif;


if (!function_exists('UNCC_event_body_class')):

    function UNCC_event_body_class($classes) {
	return array_merge(array('events-section'), $classes);
    }

endif;


if (!function_exists('UNCC_event_location_rss')):

    function uncc_event_location_rss() {
	if ($event_location = get_post_meta(get_the_ID(), 'location', true)) {
	    ?>
	    <event_location><?php echo $event_location; ?></event_location>
	<?php
	}
    }

endif;

/**
 * Converts Event post to a Google Calendar event.
 * @param WP_Post The current post being displayed.
 */
if (!function_exists('convert_to_gcal')):

    function convert_to_gcal($post) {
	?><?php
	//initialize variables
	$gcal_startdate	 = '';
	$gcal_enddate	 = '';

	//Create the dynamic URL using variables from the post
	$gcal_link	= 'https://calendar.google.com/calendar/r/eventedit?text=';
	//The title of the Event will be the name of the Google Calendar event
	$gcal_link	.= $post->post_title;
	//Determine the start date+time format
	$gcal_link	.= '&dates=';

	//If we have a start date (no time) add to Google Calendar
	if (date('g:i A', strtotime($post->datetime)) == '12:00 AM') {
	    $gcal_startdate = date('Ymd', strtotime($post->datetime));
	    //if we have an end time or end date + end time, we'll need to create and add a start time for GCal's API
	    if ($post->enddatetime && (date('g:i A', strtotime($post->enddatetime)) != '12:00 AM') || (date('F j, Y') == date('F j, Y', strtotime($post->enddatetime)))){
		//Set the time to midnight
		$gcal_startdate .= 'T000000';
	    }
	//If we have a start date and time, add to Google Calendar
	} else {
	    $gcal_startdate = date('Ymd', strtotime($post->datetime)) . 'T' . date('His', strtotime($post->datetime));
	}

	//Determine the end date+time format if there is no end date + end time
	if (!$post->enddatetime) {	    
	    //If we have a start date and no end date, add to Google Calendar (sans end datetimes)
	    if (date('g:i A', strtotime($post->datetime)) == '12:00 AM') {
		//Because of Google Calendar's URL API, "start date to end date" with same day events & no time are calculated as "start date to start date + 1"
		$gcal_enddate = date('Ymd', strtotime($post->datetime . "+1 day"));
	    //No end date or end time, but a start time? Add to GCal with the end time being start time+1 hour
	    } else {
		$gcal_enddate = date('Ymd', strtotime($post->datetime)) . 'T' . date('His', strtotime($post->datetime . "+1 hour"));
	    }

	//If we have a start date and end date, add to Google Calendar without times
	} else if (date('g:i A', strtotime($post->enddatetime)) == '12:00 AM') {
	    //Display only the dates, no times
	    if (date('g:i A', strtotime($post->datetime)) == '12:00 AM') {
		$gcal_enddate = date('Ymd', strtotime($post->enddatetime . "+1 day"));
	    //Display the start date + start time, format end date as the same day and end time+1 hour for GCal
	    } else if (date('g:i A', strtotime($post->datetime)) != '12:00 AM') {
		$gcal_enddate	 = date('Ymd', strtotime($post->enddatetime));
		$gcal_enddate	 .= 'T' . date('His', strtotime($post->datetime . "+1 hour"));
	    }
	//If we have a start date, start time, and an end time, add to Google Calendar (same day, different times)
	} else if (date('F j, Y') == date('F j, Y', strtotime($post->enddatetime))) {
	    $gcal_enddate = date('Ymd', strtotime($post->datetime)) . 'T' . date('His', strtotime($post->enddatetime));
	//If we have an end date and end time, add to Google Calendar
	} else {
	    $gcal_enddate = date('Ymd', strtotime($post->enddatetime)) . 'T' . date('His', strtotime($post->enddatetime));
	}

	$gcal_link	 .= $gcal_startdate . '/' . $gcal_enddate;  //Event start/end dates+times, formatted as 20180101T050000/20180101T110000
	$gcal_link	 .= '&details=';
	$gcal_link	 .= get_permalink($post->ID);		    //Event description (permalink URL)
	$gcal_link	 .= '&location=';
	$gcal_link	 .= $post->location;				    //Event location
	$gcal_link	 .= '&trp=false&sprop&sprop=name&sf=true&ctz=America/New_York';

	//Push calendar link to event
	$gcal	    .= '<div class="gcal">';
	$gcal	    .= '<a href ="' . htmlentities($gcal_link) . '" target="_blank" rel="noopener noreferrer">+ Google Calendar</a></div>';
	$gcal	    .= '</div>';

	return $gcal;
	?><?php
    }

endif;




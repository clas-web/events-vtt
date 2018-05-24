<?php

add_action( 'vtt-search-folders', 'uncc_variations_add_variations_folder' );
add_action('rss2_item', 'UNCC_event_location_rss');		
add_filter( 'body_class', 'UNCC_event_body_class');

require_once( __DIR__.'/widget/events-widget.php' );
UNCC_WidgetEventsListingControl::register_widget();
UNCC_WidgetEventsListingControl::register_shortcode();

/**
 * Add the plugin folder the list of folders used by VTT to determine available variations.
 */
if( !function_exists('uncc_variations_add_variations_folder') ):
function uncc_variations_add_variations_folder()
{
	global $vtt_config;
	$vtt_config->add_search_folder( __DIR__, 6);
}
endif;


if( ! function_exists('UNCC_event_body_class') ):
function UNCC_event_body_class($classes){
	return array_merge(array('events-section'),$classes);
}
endif;


if( ! function_exists('UNCC_event_location_rss') ):
function uncc_event_location_rss() {
	if($event_location = get_post_meta(get_the_ID(), 'location', true)){ ?>
        <event_location><?php echo $event_location; ?></event_location>
	<?php }
}
endif;

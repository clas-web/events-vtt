<?php
//add_filter( 'nhs-events-featured-story', 'unht_events_get_featured_story', 99, 2 );
//add_filter( 'nhs-events-listing-story', 'unht_events_get_listing_story', 99, 2 );
//add_filter( 'nhs-event-story-excerpt', 'unht_event_get_excerpt', 99, 2 );
//add_filter( 'nhs-event-story-content', 'unht_events_get_content', 99, 2 );


/**
 * 
 */
if( ! function_exists( 'unht_events_get_featured_story' ) ):
function unht_events_get_featured_story( $story, $post )
{
	unset($story['byline']);
	unset($story['description']['excerpt']);

	$datetime = UNHT_CustomEventPostType::get_datetime( $post->ID );
	$story['datetime'] = $datetime['datetime'];
	$story['description']['datetime'] = $datetime['date'].', '.$datetime['time'];
	$story['description']['datetime'] = unht_event_get_datetime( $post->ID, true );
	$story['description']['location'] = UNHT_CustomEventPostType::get_location( $post->ID );
	
	return array_filter( $story );
}
endif;


/**
 * 
 */
if( ! function_exists( 'unht_events_get_listing_story' ) ):
function unht_events_get_listing_story( $story, $post )
{
	unset($story['byline']);
	
	$datetime = UNHT_CustomEventPostType::get_datetime( $post->ID );
	$story['datetime'] = $datetime['datetime'];
	$story['description']['event-info'] = array();
	$story['description']['event-info']['datetime'] = $datetime['date'].', '.$datetime['time'];
	$story['description']['event-info']['datetime'] = unht_event_get_datetime( $post->ID, true );
	$story['description']['event-info']['location'] = UNHT_CustomEventPostType::get_location( $post->ID );
	
	return array_filter( $story );
}
endif;
	

/**
 * 
 */
if( ! function_exists( 'unht_event_get_datetime' ) ):
function unht_event_get_datetime( $post_id, $format = false )
{
	$datetime = '';
	$datetime = get_post_meta( $post_id, 'datetime', true );
	if( !empty($datetime) )
	{
		$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
		if( $format !== false )
		{
			if( is_string($format) )
				$datetime = $datetime->format($format);
			else
				$datetime = $datetime->format('F d, Y g:i A');
		}
	}
	else
	{
		$datetime = null;
		if( $format ) $datetime = 'No date provided.';
	}
	
	return $datetime;
}
endif;


/**
 * 
 */
if( ! function_exists( 'unht_event_get_excerpt' ) ):
function unht_event_get_excerpt( $excerpt, $post )
{
	if( !empty($post->post_excerpt) )
	{
		$excerpt = $post->post_excerpt;
	}
	else
	{
		$excerpt = preg_replace( '@<(table)[^>]*>.*?</\1>@is', '', $post->post_content );
		$excerpt = strip_tags( $excerpt );
		if( strlen($excerpt) > 140 )
		{
			$excerpt = substr($excerpt, 0, 140);
			$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
			$excerpt .= ' [&hellip;]';
		}
	}
	
	return $excerpt;
}
endif;


/**
 * 
 */
if( ! function_exists( 'unht_events_get_content' ) ):
function unht_events_get_content( $content, $post )
{
	// Check to see if the featured image needs to be inserted.
	global $vtt_config;
	$featured_image_position = $vtt_config->get_value( 'featured-image-position' );
	if( $featured_image_position === 'header' || ! has_post_thumbnail($post->ID) ) {
		return $content;
	}
	
	// Get image attachment.
	$image = wp_get_attachment_image_src(
		get_post_thumbnail_id( $post->ID ), 'full'
	);
	if( ! $image ) return $content;
	
	// Determine where the table ends.
	$end_of_table = strpos( $content, '</table>' );
	if( false !== $end_of_table ) {
		$end_of_table += 8;
	} else {
		$end_of_table = 0;
	}
	
	// Divide the content into two strings.
	$before_substring = substr( $content, 0, $end_of_table );
	$after_substring = substr( $content, $end_of_table );
	
	// Insert featured image into content.
	$content = $before_substring . 
		'<div class="featured-image '. $featured_image_position . '">' .
			'<img src="' . $image[0] . '" title="Featured Image" />' .
		'</div>' . 
		$after_substring;
	
	return $content;
}
endif;


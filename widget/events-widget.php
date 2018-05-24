<?php

require_once( __DIR__.'/widget-shortcode-control.php' );


/**
 * The UNCC_WidgetEventsListingControl class for the Events for VTT plugin.
 * 
 * Shortcode Example:
 * [ items="2"]
 * 
 * @author     Aaron Forsyth
 */
 
if( !class_exists('UNCC_WidgetEventsListingControl') ):
class UNCC_WidgetEventsListingControl extends WidgetShortcodeControl
{
	/**
	 * The minimum number of upcoming events.
	 * @var  int
	 */	
	private static $MIN_ITEMS = 1;

	/**
	 * The maximum number of upcoming events.
	 * @var  int
	 */
	private static $MAX_ITEMS = 10;

	/**
	 * Constructor.
	 * Setup the properties and actions.
	 */
	public function __construct()
	{
		$widget_ops = array(
			'description'	=> 'Add an upcoming events list.',
		);
		
		parent::__construct( 'event_widget', 'Upcoming Events', $widget_ops );
	}


	/**
	 * Output the widget form in the admin.
	 * Use this function instead of form.
	 * @param   array   $options  The current settings for the widget.
	 */
	public function print_widget_form( $options )
	{
		$options = $this->merge_options( $options );
		extract( $options );
		?>

		<div id="event-listing-control-<?php echo self::$index; ?>" class="wsadmincontrol">
		<p>
		<label for="<?php echo $this->get_field_id( 'items' ); ?>"><?php _e( 'Number of events:' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" class="widefat">
			<?php for( $i = self::$MIN_ITEMS; $i < self::$MAX_ITEMS+1; $i++ ): ?>
			
				<option value="<?php echo $i; ?>" <?php selected($i, $items); ?>><?php echo $i; ?></option>
			
			<?php endfor; ?>
		</select>
		</p>
		</div><!-- .wsadmincontrol -->

		<?php
	}
	
	
	/**
	 * Get the default settings for the widget or shortcode.
	 * @return  array  The default settings.
	 */
	public function get_default_options()
	{
		$defaults = array();

		$defaults['title'] = 'EVENTS';
		$defaults['items'] = 3;
		$defaults['posts'] = array();

		return $defaults;
	}
	
	
	/**
	 * Process options from the database or shortcode.
	 * Designed to convert options from strings or sanitize output.
	 * @param   array   $options  The current settings for the widget or shortcode.
	 * @return  array   The processed settings.
	 */
	public function process_options( $options )
	{
		$options['title'] = 'title';
		return $options;
	}
	

	/**
	 * Echo the widget or shortcode contents.
	 * @param   array  $options  The current settings for the control.
	 * @param   array  $args     The display arguments.
	 */
	public function print_control( $options, $args = null )
	{		$options = $this->merge_options( $options );
		if( !$args ) $args = $this->get_args();
		
		extract( $options );
		
		echo $args['before_widget'];
		echo '<div id="section-listing-control-'.self::$index.'" class="wscontrol section-listing-control">';
		echo '<h2>Events</h2>';
		//<a href="<?php echo $nhs_section->get_section_link(); " title="<?php echo $nhs_section->title; Archives"><?php echo $nhs_section->title; </a>
	
		$posts = get_posts(array('post_type'=>'event','numberposts'=>$items));
		
		foreach($posts as $post){
			echo '<h3>'.vtt_get_anchor(get_permalink($post), $post->post_title, null, $post->post_title).'</h3>';
			echo '<div class="contents">';
			$event_info = '<div class="event-info">';
			if($post->datetime){
				$event_info .= '<div class="datetime">'.date('F j, Y - g:i A', strtotime($post->datetime)).'</div>';
			}
			if($post->location){
				$event_info .= '<div class="location">'.$post->location.'</div>';
			}
			$event_info .= '</div>';
			echo $event_info;
			echo '</div>';
		}

		echo '</div>';
		echo '<div class="more">';
		echo vtt_get_anchor( '/event', 'More Events', null, 'More <em>Events</em> &raquo;' );
		echo '</div>';
	echo $args['after_widget'];		
	}
}
endif;


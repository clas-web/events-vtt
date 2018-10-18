<?php //vtt_print('default:content:listing-events'); ?>
<?php
global  $wp_query;
//$nhs_section = nhs_get_wpquery_section();
?>


<div class="page-title">
	
	<?php if( is_category() ): ?>
	<div class="breadcrumbs">
		<?php echo vtt_get_taxonomy_breadcrumbs( get_queried_object_id() ); ?>
	</div>	
	<?php endif; ?>
	
	<?php
	if( vtt_has_page_listing_name() )
		echo '<div class="listing-name">'.vtt_get_page_listing_name().'</div>';
	?>

	<?php
	if( !is_home() )
		echo '<h1>'.vtt_get_page_title().'</h1>';
	?>

</div>


<?php
if( vtt_has_page_description() )
	echo '<div class="description">'.vtt_get_page_description().'</div>';
?>


<?php

list( $month, $year, $start_datetime, $end_datetime ) = array_values( UNCC_CustomEventPostType::get_events_datetime() );
$end_datetime->sub( new DateInterval('PT1S') );

?>
<div class="date-range">
	<?php echo $start_datetime->format('F d, Y') . ' to ' . $end_datetime->format('F d, Y'); ?>
</div>
<?php

$events_url = get_site_url().'/event';
$prev_year = new DateTime( $start_datetime->format('Y-m-d') ); 
$prev_year->sub( new DateInterval('P1Y') );
$next_year = new DateTime( $start_datetime->format('Y-m-d') ); 
$next_year->add( new DateInterval('P1Y') );

?>
<div class="date-controls">
	<div class="year">
		<a href="<?php echo $events_url; ?>?event-date=12-<?php echo $prev_year->format("Y"); ?>">
			<
			<?php /* TOOD: replace with image. */ ?>
		</a>
		<?php echo $start_datetime->format('Y'); ?>
		<a href="<?php echo $events_url; ?>?event-date=12-<?php echo $next_year->format("Y"); ?>">
			>
			<?php /* TODO: replace with image. */ ?>
		</a>
	</div>
	<div class="months">
		<?php
		$months = array(
			1 => 'JAN',
			2 => 'FEB',
			3 => 'MAR',
			4 => 'APR',
			5 => 'MAY',
			6 => 'JUN',
			7 => 'JUL',
			8 => 'AUG',
			9 => 'SEP',
			10 => 'OCT',
			11 => 'NOV',
			12 => 'DEC',
		);
		?>
		<?php foreach( $months as $m => $name ): ?>
			<?php if( $month == $m ): ?>
			<span>
				<?php echo $name; ?>
			</span>
			<?php else: ?>
			<a href="<?php echo $events_url; ?>?event-date=<?php echo sprintf("%02s", $m); ?>-<?php echo $start_datetime->format('Y'); ?>">
				<?php echo $name; ?>
			</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>


<?php
//------------------------------------------------------------------------------------
// Print of the events for this archive listing.
//------------------------------------------------------------------------------------
$posts = $wp_query->get_posts();

if( count($posts) == 0 ):

	echo '<p>No events found.</p>';

else:

	global $post;
	$current_date = new DateTime( $start_datetime->format('Y-m-d') );
	$current_date->sub( new DateInterval('P1D') ); 
	$close_previous_day = false;
	

	foreach( $posts as $post ):
		$datetime_meta = $post->datetime;
		if( !empty($datetime_meta) ){
			$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_meta);
		}
		
		$same_day = true;
		
		if( $datetime->format('y-d-M') != $current_date->format('y-d-M') )
		{
			
			$same_day = false;
			$current_date = $datetime;
			$month = $current_date->format('F');
			$day = $current_date->format('j');
			$weekday = $current_date->format('l');

			if( $close_previous_day )
			{
				?>
				</div><!-- .day-events -->
				</div><!-- .agenda-day -->
				<?php
			}

			?>
			<div class="agenda-day">
				<div class="date-label">
					<div class="weekday"><?php echo $weekday; ?></div>
					<div class="month"><?php echo $month; ?></div>
					<div class="day"><?php echo $day; ?></div>
				</div><!-- .date-label -->
				<div class="day-events">
			<?php
			
			$close_previous_day = true;
		}
		?>
		
		<div <?php post_class( 'story events-section listing' ); ?>>
		<?php echo vtt_get_anchor( get_permalink($post), $post->post_title ); ?>

			<div class="description">
			
				<h3><?php echo $post->post_title; ?></h3>
				
				<?php //if( count($post->$description) > 0 ): ?>

					<div class="contents">
					
					<?php
					$excerpt = '<div class="excerpt">';
					$excerpt .= UNCC_CustomEventPostType::get_excerpt($post);
					$excerpt .= '</div>';
					$event_info = '<div class="event-info">';
					if($post->datetime){
						$event_info .= '<div class="datetime">'.date('F j, Y - g:i A', strtotime($post->datetime)).'</div>';
					}
					if($post->location){
						$event_info .= '<div class="location">'.$post->location.'</div>';
					}
					$event_info .= '</div>';

					echo $excerpt;
					echo $event_info;
					?>

					</div><!-- .contents -->
					
				<?php //endif; ?>
			
			</div><!-- .description -->
			
		</a>
		</div><!-- .story -->
		<?php

	endforeach;

	if( count($posts) > 0 ):
		?>
		</div><!-- .day-events -->
		</div><!-- .agenda-day -->
		<?php
	endif;

	//vtt_get_template_part( 'pagination', 'other', $nhs_section->key );


endif;
?>

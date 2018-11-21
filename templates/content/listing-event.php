<?php //vtt_print('default:content:listing-events');    ?>
<?php
global $wp_query;
//$nhs_section = nhs_get_wpquery_section();
?>


<div class="page-title">

    <?php if (is_category()): ?>
        <div class="breadcrumbs">
            <?php echo vtt_get_taxonomy_breadcrumbs(get_queried_object_id()); ?>
        </div>	
    <?php endif; ?>

    <?php
    if (vtt_has_page_listing_name())
        echo '<div class="listing-name">' . vtt_get_page_listing_name() . '</div>';
    ?>

    <?php
    if (!is_home())
        echo '<h1>' . vtt_get_page_title() . '</h1>';
    ?>

</div>


<?php
if (vtt_has_page_description())
    echo '<div class="description">' . vtt_get_page_description() . '</div>';
?>


<?php
list( $month, $year, $start_datetime, $end_datetime ) = array_values(UNCC_CustomEventPostType::get_events_datetime());
$end_datetime->sub(new DateInterval('PT1S'));
?>
<div class="date-range">
    <?php echo $start_datetime->format('F d, Y') . ' to ' . $end_datetime->format('F d, Y'); ?>
</div>
<?php
$events_url = get_site_url() . '/event';
$prev_year = new DateTime($start_datetime->format('Y-m-d'));
$prev_year->sub(new DateInterval('P1Y'));
$next_year = new DateTime($start_datetime->format('Y-m-d'));
$next_year->add(new DateInterval('P1Y'));
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
        <?php foreach ($months as $m => $name): ?>
            <?php if ($month == $m): ?>
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
/**
* Print of the events for this archive listing.
*/
$posts = $wp_query->get_posts();

if (count($posts) == 0):

    echo '<p>No events found.</p>';

else:

    global $post;
    $current_date = new DateTime($start_datetime->format('Y-m-d'));
    $current_date->sub(new DateInterval('P1D'));
    $close_previous_day = false;

    //Grab each post for the Event listing at /exchange/event/
    foreach ($posts as $post):
    
//         $datetime_meta = $post->datetime;
//         $enddatetime_meta = $post->enddatetime;
			$same_day = true;
        
//        print_r ($datetime_meta);
		
		// datetime_meta might only be Y-m-d
//         if (!empty($datetime_meta)) {
//             $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_meta);
//         }
//         if (!empty($enddatetime_meta)) {
//             $enddatetime = DateTime::createFromFormat('Y-m-d H:i:s', $enddatetime_meta);
//         }
        
//		    $datetime = $post->datetime;
			$event_date = new DateTime(date('Y-m-d', strtotime($post->datetime)));
			//$enddate =  $post->enddatetime;
//			print_r ($post->datetime);

			//check to see which date/time format the datetime is
// 			$datetimeformat1 = DateTime::createFromFormat('m/d/Y H:i A', $datetime);
// 			$datetimeformat2 = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
// 			$dateformat1 = DateTime::createFromFormat('m/d/Y', $datetime);
// 			$dateformat2 = DateTime::createFromFormat('Y-m-d', $datetime);
// 
// 			//format the start/end date values properly
// 			if (is_a($datetimeformat1, "DateTime")) {
// 				$datetime = $datetimeformat1;
// 				$date = $datetime->format('Y-m-d');
// 			//	$time = $datetime->format('h:i A');
// 			} else if (is_a($datetimeformat2, "DateTime")) {
// 				$datetime = $datetimeformat2;
// 				$date = $datetime->format('Y-m-d');
// 			//	$time = $datetime->format('h:i A');
// 			} else if (is_a($dateformat1, "DateTime")) {
// 				$datetime = $dateformat1;
// 				$date = $datetime->format('Y-m-d');
// 			} else if (is_a($dateformat2, "DateTime")) {
// 				$datetime = $dateformat2;
// 				$date = $datetime->format('Y-m-d');
//  			}
       
			// print out day, month year in left aligned div if NOT the current day
			// (not need to repeat this box for every event on a given day)
			if ($event_date->format('y-d-M') != $current_date->format('y-d-M')) {

				$same_day = false;
				$current_date = $event_date;
				$month = $current_date->format('F');
				$day = $current_date->format('j');
				$weekday = $current_date->format('l');

				if ($close_previous_day) {
					print "</div><!-- .day-events -->";
					print "</div><!-- .agenda-day -->";
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

                <div <?php post_class('story events-section listing'); ?>>
                    <?php echo vtt_get_anchor(get_permalink($post), $post->post_title); ?>

                    <div class="description">

                        <h3><?php echo $post->post_title; ?></h3>

                        <?php //if( count($post->$description) > 0 ):  ?>

                        <div class="contents">

                            <?php
                            $excerpt = '<div class="excerpt">';
                            $excerpt .= UNCC_CustomEventPostType::get_excerpt($post);
                            $excerpt .= '</div>';
                            $event_info = '<div class="event-info">';

                            //display the start date of the selected event
                            if ($post->datetime) {                               
                                 //If the start date is just a date w/ no time, it will default to 12:00 AM
                                //Thus, only display the date if the selected start time is 12:00 AM (hopefully no midnight events)
                                print date('g:i A', strtotime($post->datetime)); print "<br>";
                                print date('g:i A', strtotime($post->date));print "<br>";
                                if (date('g:i A', strtotime($post->date)) == '12:00 AM') {
                                    $event_info .= '<div class="datetime">' . date('F j, Y', strtotime($post->datetime)) . '</div>';
                                    //$event_info .= '<div class="datetime">'. date('F j, Y', strtotime($date)).'</div>';
                                    //Otherwise, post the whole start date and time
                                } else {
                                    $event_info .= '<div class="datetime">' . date('F j, Y - g:i A', strtotime($date)). '</div>';
                                }
                            }

                            //display the end date of the selected event
                            if ($post->enddatetime) {
                                //If the end date is just a date w/ no time, it will default to 12:00 AM
                                //Thus, only display the date if the selected end time is 12:00 AM (hopefully no midnight events)
                                if (date('g:i A', strtotime($post->enddatetime)) == '12:00 AM') {
                                    $event_info .= '<div class="enddatetime">' . date('F j, Y', strtotime($post->enddatetime)) . '</div>';
                                    //If there's just an end time with no end date, add just the time (considered same day)
                                } else if (date('F j, Y') == date('F j, Y', strtotime($post->enddatetime))) {
                                    $event_info .= '<div class="enddatetime">to ' . date('g:i A', strtotime($post->enddatetime)) . '</div>';
                                    //Otherwise, post the whole end date and time                                                         
                                } else {
                                    $event_info .= '<div class="enddatetime">' . date('F j, Y - g:i A', strtotime($post->enddatetime)) . '</div>';
                                }
                            }

                            //display the location
                            if ($post->location) {
                                $event_info .= '<div class="location">' . $post->location . '</div>';
                            }

                            $event_info .= '</div>';

                            echo $excerpt;
                            echo $event_info;
                            ?>

                        </div><!-- .contents -->

        <?php //endif;  ?>

                    </div><!-- .description -->

                    </a>
                </div><!-- .story -->
        <?php
    endforeach;

    if (count($posts) > 0):
        ?>
            </div><!-- .day-events -->
        </div><!-- .agenda-day -->
        <?php
    endif;

//vtt_get_template_part( 'pagination', 'other', $nhs_section->key );


endif;
?>


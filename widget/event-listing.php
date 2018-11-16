<?php
global $wp_query, $post;
?>

<div <?php post_class('story events-section listing'); ?>>
    <?php echo vtt_get_anchor(get_permalink($post), $post->post_title); ?>

    <div class="description">

        <h3><?php echo $post->post_title ?></h3>



        <div class="contents">

            <?php
            $excerpt = '<div class="excerpt">';
            $excerpt .= UNCC_CustomEventPostType::get_excerpt($post);
            $excerpt .= '</div>';
            $event_info = '<div class="event-info">';
            //Display the start date
            if ($post->datetime) {
                $event_info .= '<div class="datetime">' . date('F j, Y - g:i A', strtotime($post->datetime)) . '</div>';
            }
            //Display the end date
            if ($post->enddatetime) {
                $event_info .= '<div class="enddatetime">' . date('F j, Y - g:i A', strtotime($post->enddatetime)) . '</div>';
            }
            //Display the location
            if ($post->location) {
                $event_info .= '<div class="location">' . $post->location . '</div>';
            }
            $event_info .= '</div>';

            echo $excerpt;
            echo $event_info;
            ?>

        </div><!-- .contents -->



    </div><!-- .description -->

</a>
</div><!-- .story -->
<?php


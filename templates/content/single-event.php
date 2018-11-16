<?php //vtt_print('default:content:single');  ?>
<?php
global $post;
?>


<div <?php post_class('story events-section single'); ?>>

    <div class="page-title">
        <div class="listing-name">
            <?php
            echo vtt_get_anchor(
                    get_permalink($post), $post->post_title, null, 'EVENTS');
            ?>
        </div>
        <?php echo '<h1>' . vtt_get_page_title() . '</h1>'; ?>
    </div>
    <?php
    $date = date('F d, Y', strtotime($post->post_modified));

    $author = get_the_author_meta('display_name', $post->post_author);
    $url = get_author_posts_url($post->post_author);

    $byline = $date . ' by <a href="' . $url . '" title="Posts by ' . $author . '">' . $author . '</a>';
    ?>	
    <div class="byline"><?php echo $byline; ?></div>

    <?php
    //		if( $image ):
    //			
    ?><div class="image" title="Featured Image"><img src="<?php if ($image) echo $image; ?>" /></div><!-- .image --><?php
    //		endif;
    ?>

    <div class="details">

        <div class="description">

            <?php echo $post->post_content; ?>

        </div><!-- .description -->
    </div><!-- .details -->


</div><!-- .post -->

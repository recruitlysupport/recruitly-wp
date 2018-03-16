<?php
add_shortcode('recruitly_jobs','recruitly_wordpress_job_listing_shortcode');

/**
 * Lists all jobs with pagination support
 */
function recruitly_wordpress_job_listing_shortcode()
{

    recruitly_wordpress_insert_post_type();

    global $wp_query;

    $temp=$wp_query;

    if ( get_query_var('paged') ) {

        $paged = get_query_var('paged');

    } elseif ( get_query_var('page') ) {

        $paged = get_query_var('page');

    } else {

        $paged = 1;

    }
    $wp_query=null;
	$wp_query = new WP_Query(array(
		'post_type' => 'recruitlyjobs',
		'posts_per_page' => 25,
		'meta_key' => 'jobStatus',
		'meta_value' => 'OPEN',
		'paged' => $paged
	));
    ?>
    <div class="recruitly_jobsearch">
        <form method="get" role="search" class="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">

            <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
            <input type="hidden" name="post_type" value="recruitlyjobs">

            <label for="s" class=""><?php _e( 'Keywords: ', 'textdomain' ); ?></label><br>
            <input type="text" value="" placeholder="<?php _e( 'Type keywords', 'textdomain' ); ?>" name="s" id="name" />

            <input type="submit" value="Search" />

        </form>
    </div>
    <div class="recruitly_jobs">
        <?php
        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) : $wp_query->the_post();?>
                <div class="cool-jobrow" style="background-color: #fff;display: block;min-height: 100px;border: 1px solid #e6e6e6;border-radius: 4px;position: relative;padding: 15px;margin-bottom: 20px;">
                    <div class="cool-job-header">
                        <div class="cool-job-title" style="font-weight:bold;font-size:16px;"><?php the_title(); ?></div>
                        <div class="cool-job-loc"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo get_post_custom_values( 'cityOrRegion' )[0];?></div>
                        <div class="cool-job-type"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo get_post_custom_values( 'jobType' )[0];?></div>
                        <div class="cool-job-pay"><?php echo get_post_custom_values( 'payLabel' )[0];?></div>
                        <div class="cool-job-date"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo get_post_custom_values( 'postedOn' )[0];?></div>
                    </div>
                    <div class="cool-job-excerpt">
                        <div class="cool-job-desc"> <?php echo get_post_custom_values( 'shortDesc' )[0];?></div>
                    </div>
                    <div class="cool-job-footer" style="text-align: right;">
                        <a class="cool-job-view-btn" title="View" href="<?php the_permalink() ?>">View</a>&nbsp;
                        <a class="cool-job-apply-btn" title="Apply" href="<?php echo get_post_custom_values( 'applyUrl' )[0];?>" target="_blank" style="font-weight:bold;">Apply</a>
                    </div>
                </div>
            <?php
            endwhile;
            ?>
            <nav>
                <?php echo previous_posts_link('&laquo; Newer') ?>
                <?php echo next_posts_link('Older &raquo;') ?>
            </nav>
        <?php
        }?>

        <?php
        wp_reset_query();  // Restore global post data stomped by the_post().
        $wp_query=$temp;
        ?>
    </div>
<?php
}
?>

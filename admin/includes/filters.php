<?php
add_filter('the_content', 'recruitly_wordpress_job_application_form');

/**
 * Injects job application button
 * @param $content
 * @return string
 */
function recruitly_wordpress_job_application_form($content) {
	global $post;
	if ($post->post_type == 'recruitlyjobs')
	{
		$applyUrl=get_post_custom_values('applyUrl')[0];
		$content .= '<div class="cool-jobview-footer" style="padding-bottom:25px;font-weight: bold;"><a href="'.$applyUrl.'" target="_blank" class="cool-apply-btn btn">Apply</a></div>';
	}
	return $content;
}
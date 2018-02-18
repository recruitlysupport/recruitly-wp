<?php
/**
 * Deletes all jobs from wordpress.
 *
 * This function is called after deactivating recruit.cool plugin and
 * after making configuration changes.
 */
function recruitly_wordpress_truncate_post_type()
{
    global $wpdb;

    $query = "SELECT ID FROM $wpdb->posts WHERE post_type ='recruitlyjobs'";

    $results = $wpdb->get_results($query);

    if (count($results)) {
        foreach ($results as $post) {
            $purge = wp_delete_post($post->ID);
        }
    }

}

/**
 * Insert all jobs into wordpress custom post type.
 *
 * @see https://api.recruit.cool/swagger-ui.html
 * @see function recruitly_plugin_setup_post_type()
 *
 */
function recruitly_wordpress_insert_post_type()
{
    if (!get_option('recruitly_apikey') || !get_option('recruitly_apiserver')
        || get_option('recruitly_apikey') == '' || get_option('recruitly_apiserver') == '') {
        echo '<h2>Please enter API Key to load jobs</h2>';
        return;
    }

	//Check if this job exists in the custom post type.
	global $wpdb;

	$queryRjobs = "SELECT ID FROM $wpdb->posts WHERE post_type ='recruitlyjobs'";

	$queryResults = $wpdb->get_results($queryRjobs);

	$postIds= array();
	$jobIdList = array();

	if (count($queryResults)) {
		foreach ($queryResults as $post) {
			$coolJobId=get_post_meta($post->ID,'jobId',true);;
			$jobIdList[] =$coolJobId;
			$postIds[$coolJobId]=$post->ID;
		}
	}

	$apiUrl= get_option('recruitly_apiserver'). '/api/job?apiKey='. get_option('recruitly_apikey') . '&start=0&limit=250';

	$jsonData = file_get_contents($apiUrl);

    $restResponse = json_decode($jsonData);

    foreach ($restResponse->data as $job) {

	    //If job does not exist then create one.
	    if(in_array($job->id,$jobIdList,FALSE)==0){

		    $post_id = wp_insert_post(array(
	            'post_type' => 'recruitlyjobs',
	            'post_title' => $job->title,
	            'post_content' => $job->description,
	            'post_status' => 'publish',
	            'comment_status' => 'closed',
	            'ping_status' => 'closed',
	            'post_date' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $job->postedOn)))
            ));

		    $sector_id = recruitly_get_taxonomy_id($job->sector, 'jobsector');
		    $county_id = recruitly_get_taxonomy_id($job->regionName, 'jobcounty');
		    $city_id = recruitly_get_taxonomy_id($job->cityName, 'jobcity');

		    add_post_meta($post_id, 'jobId', $job->id);
		    add_post_meta($post_id, 'jobStatus', $job->status);
		    add_post_meta($post_id, 'reference', $job->reference);
		    add_post_meta($post_id, 'jobType', $job->jobType);
		    add_post_meta($post_id, 'jobTitle', $job->title);
		    add_post_meta($post_id, 'postedOn', $job->postedOn);
		    add_post_meta($post_id, 'shortDesc', $job->shortDescription);
		    add_post_meta($post_id, 'payLabel', $job->pay->label);
		    add_post_meta($post_id, 'minSalaryRange', $job->pay->minPay);
		    add_post_meta($post_id, 'maxSalaryRange', $job->pay->maxPay);
		    add_post_meta($post_id, 'salaryPackage', $job->packageOverview);
		    add_post_meta($post_id, 'jobType', $job->jobType);
		    add_post_meta($post_id, 'experience', $job->experience);
		    add_post_meta($post_id, 'sector', $job->sector);
		    add_post_meta($post_id, 'industry', $job->industry);
		    add_post_meta($post_id, 'hot', $job->hot);
		    add_post_meta($post_id, 'applyUrl', $job->applyUrl);
		    add_post_meta($post_id, 'countryCode', $job->location->countryCode);
		    add_post_meta($post_id, 'countyName', $job->location->regionName);
		    add_post_meta($post_id, 'postCode', $job->location->postCode);
		    add_post_meta($post_id, 'town', $job->location->cityName);
		    add_post_meta($post_id, 'cityOrRegion', $job->location->cityRegion);

		    wp_set_post_terms($post_id, array($sector_id), 'jobsector', false);
		    wp_set_post_terms($post_id, array($county_id), 'jobcounty', false);
		    wp_set_post_terms($post_id, array($city_id), 'jobcity', false);


		//If job exists then update existing job.
	    }else{

		    $post_id = wp_update_post(array(
		    	'ID' => $postIds[$job->id],
			    'post_type' => 'recruitlyjobs',
			    'post_title' => $job->title,
			    'post_content' => $job->description,
			    'post_status' => 'publish',
			    'comment_status' => 'closed',
			    'ping_status' => 'closed',
			    'post_date' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $job->postedOn)))
		    ));

		    $sector_id = recruitly_get_taxonomy_id($job->sector, 'jobsector');
		    $county_id = recruitly_get_taxonomy_id($job->regionName, 'jobcounty');
		    $city_id   = recruitly_get_taxonomy_id($job->cityName, 'jobcity');

		    update_post_meta($post_id, 'jobId', $job->id);
		    update_post_meta($post_id, 'jobStatus', $job->status);
		    update_post_meta($post_id, 'reference', $job->reference);
		    update_post_meta($post_id, 'jobType', $job->jobType);
		    update_post_meta($post_id, 'jobTitle', $job->title);
		    update_post_meta($post_id, 'postedOn', $job->postedOn);
		    update_post_meta($post_id, 'shortDesc', $job->shortDescription);
		    update_post_meta($post_id, 'payLabel', $job->pay->label);
		    update_post_meta($post_id, 'minSalaryRange', $job->pay->minPay);
		    update_post_meta($post_id, 'maxSalaryRange', $job->pay->maxPay);
		    update_post_meta($post_id, 'salaryPackage', $job->packageOverview);
		    update_post_meta($post_id, 'jobType', $job->jobType);
		    update_post_meta($post_id, 'experience', $job->experience);
		    update_post_meta($post_id, 'sector', $job->sector);
		    update_post_meta($post_id, 'industry', $job->industry);
		    update_post_meta($post_id, 'hot', $job->hot);
		    update_post_meta($post_id, 'applyUrl', $job->applyUrl);
		    update_post_meta($post_id, 'applyEmail', $job->applyEmail);
		    update_post_meta($post_id, 'countryCode', $job->location->countryCode);
		    update_post_meta($post_id, 'countyName', $job->location->regionName);
		    update_post_meta($post_id, 'postCode', $job->location->postCode);
		    update_post_meta($post_id, 'town', $job->location->cityName);
		    update_post_meta($post_id, 'cityOrRegion', $job->location->cityRegion);

		    wp_set_post_terms($post_id, array($sector_id), 'jobsector', false);
		    wp_set_post_terms($post_id, array($county_id), 'jobcounty', false);
		    wp_set_post_terms($post_id, array($city_id), 'jobcity', false);

	    }

    }
}

function recruitly_get_taxonomy_id($value, $taxonomy)
{

    $term_id = 0;

    $term_flag = get_term_by('name', $value, $taxonomy);

    if ($term_flag) {
        $term_id = $term_flag->term_id;
    } else {
        $t = wp_insert_term($value, $taxonomy);
        $term_id = $t->term_id;
    }

    return $term_id;

}

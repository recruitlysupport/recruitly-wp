<?php
add_action( 'init', 'recruitly_wordpress_setup_post_type', 0 );

/**
 * Register a Custom Post Type "recruitlyjobs".
 *
 * We post all jobs to this custom post type.
 */
function recruitly_wordpress_setup_post_type() {

// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => __( 'Jobs', 'Jobs', 'twentythirteen' ),
        'singular_name'       => __( 'Job', 'Job', 'twentythirteen' ),
        'menu_name'           => __( 'Recruitly', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Job', 'twentythirteen' ),
        'all_items'           => __( 'All Jobs', 'twentythirteen' ),
        'view_item'           => __( 'View Job', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Job', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Job', 'twentythirteen' ),
        'update_item'         => __( 'Update Job', 'twentythirteen' ),
        'search_items'        => __( 'Search Job', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );

    $args = array(
        'label'               => __( 'job', 'twentythirteen' ),
        'description'         => __( 'Job description', 'twentythirteen' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type' => 'post',
        'capabilities' => array(
	        'create_posts' => 'do_not_allow'
        ),
        'map_meta_cap' => false
    );

    // Registering your Custom Post Type
    register_post_type( 'recruitlyjobs', $args );

}
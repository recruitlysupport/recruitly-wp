<?php
add_action('admin_menu', 'recruitly_wordpress_setup_menu');

function recruitly_wordpress_setup_menu()
{
    add_submenu_page('edit.php?post_type=recruitlyjobs', 'Recruitly Jobs Admin', 'Settings', 'edit_posts', basename(__FILE__), 'recruitly_wordpress_settings');
}
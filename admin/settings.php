<?php
/**
 * Renders Plugin Settings:
 * Users can enter their company name and API Key using settings page.
 */
function recruitly_wordpress_settings()
{
    if( isset($_POST['recruitly_apiserver']) &&   isset($_POST['recruitly_apikey']))
    {
        update_option('recruitly_apiserver',$_POST['recruitly_apiserver']);
        update_option('recruitly_apikey',$_POST['recruitly_apikey']);
        recruitly_wordpress_insert_post_type();
    }
    ?>
    <div class="wrap">
        <?php    echo "<h2>" . __( 'Enter Recruitly API Key', 'Recruitly' ) . "</h2>"; ?>
        <p>If you don't know the API Key or API Server please talk to one of the members at Recruitly to get one.</p>
        <form name="recruitly_configuration_form" method="post" action="">
            <table class="widefat">
                <thead>
                <tr><th>Parameter</th><th>Value</th></tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php _e("API Key: " ); ?></td>
                    <td><input type="text" name="recruitly_apikey" value="<?php echo get_option('recruitly_apikey'); ?>" size="40"/></td>
                </tr>
                <tr>
                    <td><?php _e("API Server: " ); ?></td>
                    <td><input type="text" name="recruitly_apiserver" value="<?php echo get_option('recruitly_apiserver'); ?>" size="100"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="recruitly_api_details" value="1">
                        <input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Configuration', 'Recruitly' ) ?>" />
                    </td>
                </tr>
            </table>
        </form>

    </div>
<?php
}?>
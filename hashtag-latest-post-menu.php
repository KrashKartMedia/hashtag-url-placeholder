<?php
/*
    * Plugin Name: Hashtag URL Placeholder
    * Plugin URI:  https://wordpress.org/plugins/hashtag-url-placeholder
    * Description: Define your own Hashtag in a custom menu item that will link to the latest post in a custom post type of your choice. 
    * Version:     1.0
    * Author:      Russell Aaron
    * Author URI:  http://russellenvy.com
    * Text Domain: query_all_the_post_types
    * License: GPL2
    * GitHub Plugin URI: https://github.com/KrashKartMedia/hashtag-url-placeholder
*/
  // If this file is called directly, abort.
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }

 //here is where we the post types thanks to https://codex.wordpress.org/Function_Reference/get_post_types
  function get_all_the_post_types_function() {
    $all_post_types = get_post_types();
    foreach ( $all_post_types as $post_type ) {
        // revision/nav_bar_item post types
        if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'acf', 'attachment' ) ) ) {
            // Custom stuff here
        } else {
          //start the accordion div here
          echo '<div style="display:inline-block; background-color:#e5e5e5; width:15%; padding:1%; margin-right:1%;">';
          echo 'Post Type Name: ' . esc_attr__( $post_type ) . '<p><a href="' . admin_url( 'edit.php?post_type=' . $post_type ) . '"> Click here to see all the posts in ' . esc_attr__( $post_type ) . '&rsquo;s</a>';
          //stop the accordion div here
         echo '</div>';
        }
    }
  }

//lets create a settings page shall we?
add_action( 'admin_menu', 'hashtag_latest_post_admin_menu' );
//function that defines the settings menu items & stuff
function hashtag_latest_post_admin_menu() {
    add_options_page(
        'Add #LatestPosts To Menu',
        'Hashtag Latest Post',
        'manage_options',
        'hashtag-latest-post',
        'hashtag_latest_post_settings_page'
    );
}

//add in a couple of settings. Allow user to change the # and query any cpt that they would like.
//call register settings function
add_action( 'admin_init', 'register_hashtag_latest_post_plugin_settings' );
//lets do some stuff with this, like register settings
function register_hashtag_latest_post_plugin_settings() {
    //register our settings
    register_setting( 'hashtag-latest-posts-settings-group', 'hashtag_name_one' );
    register_setting( 'hashtag-latest-posts-settings-group', 'hashtag_post_type_query_one' );
    register_setting( 'hashtag-latest-posts-settings-group', 'hashtag_name_two' );
    register_setting( 'hashtag-latest-posts-settings-group', 'hashtag_post_type_query_two' );
}
          
//create the settings page and put some stuff on it, man.
function hashtag_latest_post_settings_page() {
    ?>
    <div class="wrap">
        <h2>Add #LatestPosts To Menu</h2>
        <p>This is a simple plugin that allows you to use a #Hashtag for the url, inside of a menu item.</p>
        <ol>
        <li>Create your own hashtag (#awesomeness).</li>
        <li>Copy the name of a post type and paste it into the Post Type Name field. (post).</li>
        <li>Add a new "custom link" to your menu.</li>
        <li>In the menu item's url, paste in your hashtag.</li>
        <li>The menu item will now link to the last published post, in that specific post type.</li>
        </ol>
        <hr />
        <p>
            <form method="post" action="options.php">
                <?php settings_fields( 'hashtag-latest-posts-settings-group' ); ?>
                <?php do_settings_sections( 'hashtag-latest-posts-settings-group' ); ?>
                <p style="background-color:#e5e5e5; padding:1%;">Create Your Hashtag: <input type="text" name="hashtag_name_one" value="<?php echo esc_attr( get_option('hashtag_name_one') ); ?>" /> Choose & Paste Your Post Type Name:  <input type="text" name="hashtag_post_type_query_one" value="<?php echo esc_attr( get_option('hashtag_post_type_query_one') ); ?>" /> </p>        
                <p style="background-color:#e5e5e5; padding:1%;">Create Your Hashtag: <input type="text" name="hashtag_name_two" value="<?php echo esc_attr( get_option('hashtag_name_two') ); ?>" /> Choose & Paste Your Post Type Name: <input type="text" name="hashtag_post_type_query_two" value="<?php echo esc_attr( get_option('hashtag_post_type_query_two') ); ?>" /></p>
                <?php submit_button(); ?>
            </form>
        </p>
        <p>
            <strong>Remember</strong>: If you change the #Hashtag name on this page, you need to change the name of the #Hashtag inside of your custom menu item.
        </p>
        <hr />
        <!-- Show All The POst Types Here -->
        <p>
        <h4>Available Post Types</h4>
        <?php echo get_all_the_post_types_function('', ''); ?>
        </p>

    </div>
    <?php
}

// Front end only, don't hack on the settings page
if ( ! is_admin() ) {
    // Hook in early to modify the menu
    // This is before the CSS "selected" classes are calculated
    add_filter( 'wp_get_nav_menu_items', 'replace_placeholder_nav_menu_item_with_latest_post', 10, 3 );
}
 
// Replaces a custom URL placeholder with the URL to the latest post
function replace_placeholder_nav_menu_item_with_latest_post( $items, $menu, $args ) {
 
    // Loop through the menu items looking for placeholder(s)
    foreach ( $items as $item ) {
           
        //drop these in here so we can use them in the query
        $hashtag_name_one = esc_attr( get_option('hashtag_name_one') );
        $hashtag_post_type_one = esc_attr( get_option('hashtag_post_type_query_one') );
 
        // Is this the placeholder we're looking for?
        if ( $hashtag_name_one != $item->url )
            continue;
 
        // Get the latest post
        $args = array(
        'post_type' => $hashtag_post_type_one,
        'post_status'      => 'publish',
        'numberposts' => 1,
        );


        $latestpost = get_posts( $args );
 
        if ( empty( $latestpost ) )
            continue;
 
        // Replace the placeholder with the real URL
        $item->url = get_permalink( $latestpost[0]->ID );
    }
 
    // Return the modified (or maybe unmodified) menu items array
    return $items;
}

// Front end only, don't hack on the settings page
if ( ! is_admin() ) {
    // Hook in early to modify the menu
    // This is before the CSS "selected" classes are calculated
    add_filter( 'wp_get_nav_menu_items', 'replace_again_placeholder_nav_menu_item_with_latest_post', 10, 3 );
}
 
// Replaces a custom URL placeholder with the URL to the latest post
function replace_again_placeholder_nav_menu_item_with_latest_post( $itemss, $menu, $args ) {
 
    // Loop through the menu items looking for placeholder(s)
    foreach ( $itemss as $item ) {

        //drop these in here so we can use them in the query
        $hashtag_name_two = esc_attr( get_option('hashtag_name_two') );
        $hashtag_post_type_two = esc_attr( get_option('hashtag_post_type_query_two') );
 
        // Is this the placeholder we're looking for?
       if ( $hashtag_name_two != $item->url )
            continue;
 
        // Get the latest post
        $args = array(
        'post_type' => $hashtag_post_type_two,
        'post_status'      => 'publish',
        'numberposts' => 1,
        );

        $lastmonthpost = get_posts( $args );
 
        if ( empty( $lastmonthpost ) )
            continue;
 
        // Replace the placeholder with the real URL
        $item->url = get_permalink( $lastmonthpost[0]->ID );
    }
 
    // Return the modified (or maybe unmodified) menu items array
    return $itemss;
}
?>
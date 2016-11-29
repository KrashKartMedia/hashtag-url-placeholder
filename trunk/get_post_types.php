<?php
//here is where we the post types thanks to https://codex.wordpress.org/Function_Reference/get_post_types
  function get_all_the_post_types_function() {
    $all_post_types = get_post_types();
    foreach ( $all_post_types as $post_type ) {
        // revision/nav_bar_item post types
        if ( in_array( $post_type, array( 'revision', 'nav_menu_item', 'acf', 'attachment' ) ) ) {
            // Custom stuff here
        } else {
          //start the accordion div here
          echo '<div style="margin-bottom:16px;vertical-align:top;display:inline-block; background-color:#e5e5e5; width:25%; padding:1%; margin-right:1%;">';
          echo 'Post Type Name: ' . esc_attr__( $post_type ) . '<p><a href="' . admin_url( 'edit.php?post_type=' . $post_type ) . '"> Click here to see all the posts in ' . esc_attr__( $post_type ) . '&rsquo;s</a>';
          //stop the accordion div here
         echo '</div>';
        }
    }
  }
?>
<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
$option_name = 'hashtag_name_one';
$option_name_one = 'hashtag_post_type_query_one';
$option_name_two = 'hashtag_name_two';
$option_name_three = 'hashtag_post_type_query_two';
delete_option($option_name);
delete_option($option_name_one);
delete_option($option_name_two);
delete_option($option_name_three);
// for site options in Multisite
delete_site_option($option_name);
delete_site_option($option_name_one);
delete_site_option($option_name_two);
delete_site_option($option_name_three);
?>
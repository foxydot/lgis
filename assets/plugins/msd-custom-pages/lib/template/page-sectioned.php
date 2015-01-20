<?php
/*
Template Name: Sectioned Page
*/
if(function_exists('genesis')){
    //this is a genesis themed site
    remove_all_actions('genesis_loop');
    add_action('genesis_loop',array('MSDSectionedPage','sectioned_page_output'));
    add_action('wp_print_footer_scripts',array('MSDSectionedPage','sectioned_page_floating_nav'));
    genesis();
} else {
    //not genesis. Do things kind of the old fashioend way.
}

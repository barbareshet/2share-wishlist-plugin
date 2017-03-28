<?php
/*
Plugin Name: 2share wishlist plugin
Plugin URI: http://barbareshet.co.il
Description: wishlist widget
Author: Ido Barnea
Author URI: http://www.barbareshet.co.il
Version: 1.0
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 2share
*/




//register widget
add_action('widgets_init', 'toshare_widget_init');

//load external files
add_action('wp', 'toshare_init');

//add wishlist ajax
add_action('wp_ajax_toshare_add_wishlist','toshare_add_wishlist_process');
add_action('wp_ajax_nopriv_toshare_add_wishlist','toshare_add_wishlist_process');


/**
 * Ajax wishlist
 */
function toshare_add_wishlist_process(){

    $post_id = (int)$_POST['postId'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
        if( !thshare_has_wishlisted($post_id) ){
            //save metadata
            add_post_meta($post->ID, 'fav_posts', $post_id);
        }
    exit();
}
function thshare_has_wishlisted($post_id){
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $values = get_post_meta($post->ID, 'fav_posts');

    foreach ($values as $value){
        if($value == $post_id){
            return true;
        }
    }
    return false;
}
/**
 * load external files
 */
function toshare_init(){
    wp_register_script('toshare-wishlist-js', plugins_url('/assets/js/toshare-wishlist-js.js', __FILE__), array('jquery'));
    wp_enqueue_script('jquery');
    wp_enqueue_script('toshare-wishlist-js');

    global $post;
    wp_localize_script('toshare-wishlist-js','toshareWishlistAjax',array(
        'action'    => 'toshare_add_wishlist',
        'postId'    => $post->ID
    ) );
}
//add plugin admin settings
function toshare_widget_init(){
    register_widget(ToShare_Widget);
}
class ToShare_Widget extends WP_Widget{
    function ToShare_Widget(){
        $widget_options = array(
            'classname' => 'toshare_class',
            //'description'   => _e('Add items to wishlist')
        );
        $this->WP_Widget('toshate_id', 'Wishlist', $widget_options);
    }
    /**
     * show widget form in Widgets
     */
    function form($instance){
        $defaults = array(
            'title' =>  _e('Wishlist')
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        echo '<p>Title <input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
    }

    /**
     * Save widget form
     */
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    /**
     * Show
     */
    function widget($args, $instance){
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        //show only in single posts / articles
        if(is_single()){
            echo $before_widget;
            echo $before_title.$title.$after_title;
                //widget content
            echo '<span id="toshare_add_to_wishlist_wrap">
                    <a href="" id="toshare_add_to_wishlist"><i class="fa fa-heart-o"></i></a>
                </span>';

            echo $after_widget;
        }
    }
}


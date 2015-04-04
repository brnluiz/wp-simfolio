<?php
/**
 * Plugin Name: Image Portfolio
 * Plugin URI: #
 * Version: 1.0
 * Author: Bruno Luiz
 * Author URI: http://brunoluiz.net
 * Description: A simple portfolio system for designers
 * License: GPL2
 */

class SimplePortfolio {
	function __construct() {
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
	}

  function register_custom_post_type() {
    register_post_type( 'portfolio-project', array(
        'labels' => array(
            'name'               => _x( 'Portfolio', 'post type general name', 'simple-portfolio' ),
            'singular_name'      => _x( 'Project', 'post type singular name', 'simple-portfolio' ),
            'menu_name'          => _x( 'Portfolio', 'admin menu', 'simple-portfolio' ),
            'name_admin_bar'     => _x( 'Portfolio Project', 'add new on admin bar', 'simple-portfolio' ),
            'add_new'            => _x( 'Add New Project', 'portfolio-project', 'simple-portfolio' ),
            'add_new_item'       => __( 'Add New Project', 'simple-portfolio' ),
            'new_item'           => __( 'New Project', 'simple-portfolio' ),
            'edit_item'          => __( 'Edit Project', 'simple-portfolio' ),
            'view_item'          => __( 'View Project', 'simple-portfolio' ),
            'all_items'          => __( 'All Projects', 'simple-portfolio' ),
            'search_items'       => __( 'Search Projects', 'simple-portfolio' ),
            'parent_item_colon'  => __( 'Parent Projects:', 'simple-portfolio' ),
            'not_found'          => __( 'No project found.', 'simple-portfolio' ),
            'not_found_in_trash' => __( 'No project found in Trash.', 'simple-portfolio' ),
        ),

        // Frontend
        'has_archive'        => true,
        'public'             => true,
        'publicly_queryable' => true,

        // Admin
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-businessman',
        'menu_position'      => 2,
        'query_var'          => true,
        'show_in_menu'       => true,
        'show_ui'            => true,
        'supports'           => array(
          'title',
          'author'
        ),
    ) );
  }
}


$simplePortfolio = new SimplePortfolio();
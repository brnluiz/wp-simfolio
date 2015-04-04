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
    add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
	}

  public function register_custom_post_type() {
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
        'menu_position'      => 20,
        'query_var'          => true,
        'show_in_menu'       => true,
        'show_ui'            => true,
        'supports'           => array(
          'title'
        ),
    ) );
  }

  /**
  * Registers a Meta Box on our Contact Custom Post Type, called 'Contact Details'
  */
  function register_meta_boxes() {
    add_meta_box( 'project-photos',      // HTML ID
      'Project Photos',                  // Title
      array( $this, 'photos_meta_box' ),  // Callback
      'portfolio-project',                // Allowed screens
      'normal',                           // Context to appear: normal, advanced, side
      'high'                              // Priority that it should be shown
    );

    add_meta_box( 'project-description',      // HTML ID
      'Project Details',                  // Title
      array( $this, 'description_meta_box' ),  // Callback
      'portfolio-project',                // Allowed screens
      'normal',                           // Context to appear: normal, advanced, side
      'high'                              // Priority that it should be shown
    );
  }

  /**
  * Output a Contact Details meta box
  *
  * @param WP_Post $post WordPress Post object
  */
  function description_meta_box($post) {
    $description = get_post_meta( $post->ID, '_project_description', true );
    require_once 'views/description.php';
  }

  function photos_meta_box($post) {
    // Load the media-upload scripts and styles
    wp_enqueue_media();

    // Load the scripts for upload images
    wp_enqueue_script( 'photojs', plugins_url( 'js/photos.js', __FILE__ ), array('jquery','media-upload','thickbox') );

    // Load meta box plugin
    require_once 'views/photos.php';
  }

  /**
  * Saves the meta box field data
  *
  * @param int $post_id Post ID
  */
  function save_meta_boxes( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['projects_nonce'] ) ) {
        return $post_id;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['projects_nonce'], 'save_project' ) ) {
        return $post_id;
    }

    // Check this is the Contact Custom Post Type
    if ( 'portfolio-project' != $_POST['post_type'] ) {
        return $post_id;
    }

    // Check the logged in user has permission to edit this post
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    // OK to save meta data
    $description = sanitize_text_field( $_POST['project_description'] );
    update_post_meta( $post_id, '_project_description', $description );

}
}


$simplePortfolio = new SimplePortfolio();
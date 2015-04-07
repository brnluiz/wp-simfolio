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
    add_action( 'init', array( $this, 'add_custom_taxonomies' ) );
		add_action( 'init', array( $this, 'register_custom_post_type' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
    add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'save_meta_boxes' ) );

    add_filter( 'manage_edit-portfolio-project_columns', array( $this, 'add_table_columns' ) );
    add_action( 'manage_portfolio-project_posts_custom_column', array( $this, 'output_table_columns_data'), 10, 2 );
	}

  public function load_assets() {
    // Your resources
    $js = array(
      'sortable' => 'bower_components/ng-sortable/dist/ng-sortable.js',
      'app' => 'js/app.js',
    );
    $css = array(
      'main' => 'css/main.css',
      'ng-sortable' => 'bower_components/ng-sortable/dist/ng-sortable.min.css',
      'ng-sortable-default' => 'bower_components/ng-sortable/dist/ng-sortable.style.min.css'
    );

    /***********************
    * DEFAULT RESOURCES LOAD
    ***********************/
    // Load the media-upload scripts and styles
    wp_enqueue_media();
    // Load Angular
    wp_enqueue_script( 'angular', plugins_url( 'bower_components/angularjs/angular.js', __FILE__ ), array( 'jquery' ) );

    /************************
    * PERSONAL RESOURCES LOAD
    ************************/
    // Load each js script from $js
    foreach($js as $handle => $path) {
      wp_enqueue_script('wpamin-js-' . $handle, plugin_dir_url(__FILE__) . $path, array('jquery', 'angular', 'media-upload','thickbox'));
    }
    // Load each css from $css
    foreach($css as $handle => $path) {
      wp_enqueue_style( 'wpamin-style-' . $handle, plugins_url( $path, __FILE__ ) );
    }
  }

  public function add_custom_taxonomies() {
    register_taxonomy('skill', 'portfolio-project', array(
      // Hierarchical taxonomy (like categories)
      'hierarchical' => true,
      // This array of options controls the labels displayed in the WordPress Admin UI
      'labels' => array(
        'name' => _x( 'Skills', 'taxonomy general name' ),
        'singular_name' => _x( 'Skill', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Skills' ),
        'all_items' => __( 'All Skills' ),
        'parent_item' => __( 'Parent Skill' ),
        'parent_item_colon' => __( 'Parent Skill:' ),
        'edit_item' => __( 'Edit Skill' ),
        'update_item' => __( 'Update Skill' ),
        'add_new_item' => __( 'Add New Skill' ),
        'new_item_name' => __( 'New Skill Name' ),
        'menu_name' => __( 'Skills' ),
      ),
      // Control the slugs used for this taxonomy
      'rewrite' => array(
        'slug' => 'skills', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
      ),
    ));
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
        'taxonomies' => array('skill')
    ) );

    wp_insert_term(
    'Portfolio Categories',    // the term
    'category', // the taxonomy
    array(
      'description'=> 'Portfolio Categories',
      'slug' => 'portfolio'
    ));
  }

  /**
  * Registers a Meta Box on our Contact Custom Post Type, called 'Contact Description'
  */
  function register_meta_boxes() {
    /**
    * Add each meta box config below, as the follow
    *  'metabox' => array(
    *    'id' => 'HTML ID', // HTML ID
    *    'title' => 'META BOX TITLE', // Meta box title
    *    'callback' => array( $this, 'CALLBACK FUNCTION NAME' ),
    *    'screens' => 'ENABLED POST TYPES',
    *    'context' => 'CONTEXT TO SHOW',
    *    'priority' => 'PRIORITY COMPARED TO OTHER BOXES'
    *  )
    */
    $meta_boxes = array(
      'photos' => array(
        'id' => 'project-photos', // HTML ID
        'title' => 'Project Photos', // Meta box title
        'callback' => array( $this, 'photos_meta_box' ), // Function callback
        'screens' => 'portfolio-project', // Enabled post types
        'context' => 'normal', // Context to show
        'priority' => 'high' // Priority compared to other meta boxes
      ),
      'description' => array(
        'id' => 'project-description',
        'title' => 'Project Description',
        'callback' => array( $this, 'description_meta_box' ),
        'screens' => 'portfolio-project',
        'context' => 'normal',
        'priority' => 'high'
      )
    );

    // Adds all meta boxes configured at $meta_boxes
    foreach ($meta_boxes as $box) {
      add_meta_box( $box['id'], $box['title'], $box['callback'],
        $box['screens'], $box['context'], $box['priority'] );
    }
  }

  /**
  * Output a Contact Description meta box
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
    // wp_enqueue_script( 'photojs', plugins_url( 'js/photos.js', __FILE__ ), array('jquery','media-upload','thickbox') );

    $photos     = get_post_meta( $post->ID, '_project_photos', true );
    $main_photo = get_post_meta( $post->ID, '_project_main_photo', true );

    // Load meta box plugin
    require_once "views/photos.php";
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
    $photos      = sanitize_text_field( $_POST['project_photos'] );
    $main_photo  = sanitize_text_field( $_POST['project_main_photo'] );

    update_post_meta( $post_id, '_project_description', $description );
    update_post_meta( $post_id, '_project_photos', $photos );
    update_post_meta( $post_id, '_project_main_photo', $main_photo );
  }

  /**
  * Adds table columns to the Contacts WP_List_Table
  *
  * @param array $columns Existing Columns
  * @return array New Columns
  */
  function add_table_columns( $columns ) {

      $columns['main_photo'] = __( 'Main Photo', 'simple-portfolio' );

      return $columns;
  }

  /**
  * Outputs our Contact custom field data, based on the column requested
  *
  * @param string $columnName Column Key Name
  * @param int $post_id Post ID
  */
  function output_table_columns_data( $columnName, $post_id ) {
    $fieldJson = get_post_meta( $post_id, '_project_main_photo', true );
    $field = json_decode($fieldJson);

    if ( 'main_photo' == $columnName ) {
        echo '<img src="' . $field->sizes->thumbnail->url .'" width="'. $field->sizes->thumbnail->width . '" height="' . $field->sizes->thumbnail->height . '" />';
    } else {
        // Output field
        echo $field;
    }
  }
}


$simplePortfolio = new SimplePortfolio();
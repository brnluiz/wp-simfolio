<?php
// Add a nonce field so we can check for it later.
wp_nonce_field( 'save_project', 'projects_nonce' );

wp_editor( $description, 'project_description', $settings = array(
  'media_buttons' => false,
  'quicktags' => false,
  'textarea_rows' => 4
  )
);
?>
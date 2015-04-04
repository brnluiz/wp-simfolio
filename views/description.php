<?php
// Add a nonce field so we can check for it later.
wp_nonce_field( 'save_project', 'projects_nonce' );

// echo ( '<textarea name="project_description" class="widefat" id="project_description">' . esc_attr( $description ) . '</textarea>'  );
wp_editor( $description, 'project_description', $settings = array(
  'media_buttons' => false,
  'quicktags' => false,
  'textarea_rows' => 4
  )
);
?>
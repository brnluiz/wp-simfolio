jQuery(document).ready(function($){
  // Media Frame for Uploading files
  var file_frame;

  jQuery('#upload_image_button').click(function( event ){
    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame, if it doesn't exists
    file_frame = wp.media.frames.file_frame = wp.media({
      title: 'Project Photos',
      button: {
        text: 'Use photos',
      },
      multiple: true,  // Set to true to allow multiple files to be selected
      library: {
        type: 'image'
      }
    });

    // When an image is selected, run a callback.
    file_frame.on('select', function() {
      var selection = file_frame.state().get('selection');
      var save_data = JSON.stringify(selection.toJSON());
      console.log(selection.toJSON());
      $('#upload_image_object').val(save_data);

      // selection.map( function( attachment ) {
      //   attachment = attachment.toJSON();
      // });
    });

    // Finally, open the modal
    file_frame.open();

    // jQuery("#upload_image_object").live('change', function( event ) {
    // });

  });
});
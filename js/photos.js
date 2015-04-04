// Uploading files
var file_frame;

jQuery('#upload_image_button').live('click', function( event ){
  event.preventDefault();

  // If the media frame already exists, reopen it.
  if ( file_frame ) {
    file_frame.open();
    return;
  }

  // Create the media frame
  file_frame = wp.media.frames.file_frame = wp.media({
    title: 'Project Photos',
    button: {
      text: 'Use photos',
    },
    multiple: true  // Set to true to allow multiple files to be selected
  });

  // When an image is selected, run a callback.
  file_frame.on('select', function() {
    var selection = file_frame.state().get('selection');

    selection.map( function( attachment ) {
      attachment = attachment.toJSON();
      console.log(attachment);
    });
  });

  // Finally, open the modal
  file_frame.open();

});
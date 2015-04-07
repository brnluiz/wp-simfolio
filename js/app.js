var app = angular.module('app', ['ui.sortable']);

app.controller('PortfolioAdminController', ['$scope', function($scope) {

  $scope.postId     = jQuery('#post_ID').val();
  $scope.oldPostId  = 0;
  $scope.photos     = getPhotosObj();
  $scope.mainPhoto  = getMainPhotoObj();
  $scope.file_frame = null; // Will handle wp.media frame

  // As we can't use ng-submit, put the required data at hidden inputs for posterior saving
  $scope.$watch('photos', function() {
    var json = JSON.stringify($scope.photos);
    jQuery('#project_photos').val(json);
  }, true);
  $scope.$watch('mainPhoto', function() {
    var json = JSON.stringify($scope.mainPhoto);
    jQuery('#project_main_photo').val(json);
  }, true);

  // Enables sortable items
  $scope.dragControlListeners = {
      itemMoved: function (event) {
        console.log('itemMoved');
      },
      orderChanged: function(event) {
        console.log('orderChanged');
      },
      containment: '#sortable-container'//optional param.
  };

  $scope.setMainPhoto = function($event, photo) {
    $event.preventDefault(); // Prevent default behavior of button
    $scope.mainPhoto = photo;
  }

  $scope.removePhoto = function($event, photo) {
    $event.preventDefault(); // Prevent default behavior of button

    // Search and remove deleted photo by photo.id
    for(var i = 0; i < $scope.photos.length; i++) {
        if($scope.photos[i].id == photo.id) {
            if($scope.mainPhoto != null && $scope.mainPhoto.id == photo.id)
              $scope.mainPhoto = null;

            $scope.photos.splice(i, 1);
            $scope.photosJson = JSON.stringify($scope.photos);
            break;
        }
    }
  }

  // wp.media handler
  $scope.managePhotos = function() {
    if ( $scope.file_frame ) {
      $scope.file_frame.uploader.uploader.param( 'post_id', $scope.postId );
      $scope.file_frame.open();
      return ;
    } else {
      $scope.oldPostId = wp.media.model.settings.post.id;
      wp.media.model.settings.post.id = $scope.postId;
    }

    // Create the media frame, if it doesn't exists
    $scope.file_frame = wp.media.frames.file_frame = wp.media({
      title: 'Project Photos',
      button: {
        text: 'Use photos',
        reset: false
      },
      multiple: true,  // Set to true to allow multiple files to be selected
      library: {
        type: 'image',
        uploadedTo: $scope.postId
      }
    });

    console.log($scope.file_frame);

    // When an image is selected, run a callback.
    $scope.file_frame.on('select', function() {
      // Get the selected data
      var selection = $scope.file_frame.state().get('selection');

      // Save data at the controller $scope
      $scope.photos = selection.toJSON();
      $scope.mainPhoto = selection.toJSON()[0]; // Set the first photo as the mainPhoto

      // Force $scope update
      $scope.$digest();
      wp.media.model.settings.post.id = $scope.oldPostId;
    });

    // Finally, open the modal
    $scope.file_frame.open();
  }
}]);
var app = angular.module('app', []);

app.controller('PortfolioAdminController', ['$scope', function($scope) {

  $scope.mainPhoto  = null;
  $scope.photos     = null;
  $scope.photosJson = null;
  $scope.file_frame = null;

  $scope.$watch('photosJson', function() {
    jQuery('#project_photos').val($scope.photosJson);
  });
  $scope.$watch('mainPhoto', function() {
    jQuery('#project_main_photo').val($scope.mainPhoto);
  });

  $scope.initParams = function() {
    $scope.photos     = getPhotosJson();
    $scope.photosJson = JSON.stringify($scope.photos);
    $scope.mainPhoto  = getMainPhotoId();
  }
  $scope.initParams();

  $scope.setMainPhoto = function($event, photo) {
    $scope.mainPhoto = photo.id;
    $event.preventDefault();
  }

  $scope.removePhoto = function($event, photo) {
    $event.preventDefault();

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

  $scope.managePhotos = function() {
    if ( $scope.file_frame ) {
      $scope.file_frame.open();
      return ;
    }

    // Create the media frame, if it doesn't exists
    $scope.file_frame = wp.media.frames.file_frame = wp.media({
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
    $scope.file_frame.on('select', function() {
      var selection = $scope.file_frame.state().get('selection');
      var save_data = JSON.stringify(selection.toJSON());
      $scope.photos = selection.toJSON();
      $scope.photosJson = save_data;

      $scope.$digest();
    });

    // Finally, open the modal
    $scope.file_frame.open();
  }
}]);
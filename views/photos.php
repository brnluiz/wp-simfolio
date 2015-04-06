<script>
function getPhotosJson() { return <?=$photos?>; }
function getMainPhoto() { return <?=$main_photo?>; }
</script>

<div ng-app='app' ng-controller="PortfolioAdminController">
  <input id="project_photos" type="hidden" name="project_photos" ng-model='photosJson' />
  <input id="project_main_photo" type="hidden" name="project_main_photo" ng-model='mainPhoto' />

  <ul class="project-photos container">
    <li ng-repeat='photo in photos'
        class='project-photo {{mainPhoto.id === photo.id ? "project-main-photo" : ""}}'>
      <img src='{{ photo.url }}' />
      <input type="text" value='' ng-model='photo.title' />
      <button class="button button-small" ng-click='setMainPhoto($event, photo)'>Set as main photo</button>
      <button class="button button-small" ng-click='removePhoto($event, photo)'>Remove Photo</button>
    </li>

    <div id="no-photo" ng-show="!photos.length">
      <div class="wp-menu-image dashicons-before dashicons-admin-media"></div> No photos
    </div>
  </ul>

  <div id="major-publishing-actions">
    <div id="publishing-action">
      <span class="spinner"></span>
      <input type="button" id="upload_image_button" class="button button-primary button-large" value="Upload or Choose Images" ng-click='managePhotos()'>
    </div>
    <div class="clear"></div>
  </div>
</div>

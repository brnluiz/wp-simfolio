<script>
function getPhotosObj() {
  var photos = '<?=$photos?>';
  if (photos == '') return [];
  else return <?=$photos?>;
}
function getMainPhotoObj() {
  var mainPhoto = '<?=$main_photo?>';
  if (mainPhoto == '') return [];
  else return <?=$main_photo?>;
}
</script>

<div ng-app='app' ng-controller="PortfolioAdminController">
  <input id="project_photos" type="hidden" name="project_photos" />
  <input id="project_main_photo" type="hidden" name="project_main_photo" ng-model='mainPhoto' />

  <div id="sortable-container">
    <ul class="project-photos container" as-sortable="dragControlListeners" ng-model="photos" is-enabled="true">
      <li ng-repeat='photo in photos'
          class='project-photo {{mainPhoto.id === photo.id ? "project-main-photo" : ""}}'
          as-sortable-item >
        <div as-sortable-item-handle>
          <img src='{{ photo.url }}' />
          <input type="text" class='title'   value='' ng-model='photo.title'   placeholder='Title'/>
          <input type="text" class='caption' value='' ng-model='photo.caption' placeholder='Caption'/>
          <button class="button button-small" ng-click='setMainPhoto($event, photo)'>Set as main photo</button>
          <button class="button button-small" ng-click='removePhoto($event, photo)'>Remove Photo</button>
        </div>
      </li>

      <div id="no-photo" ng-show="!photos.length">
        <div class="wp-menu-image dashicons-before dashicons-admin-media"></div> No photos
      </div>
    </ul>
  </div>

  <div id="major-publishing-actions">
    <div id="publishing-action">
      <span class="spinner"></span>
      <input type="button" id="upload_image_button" class="button button-primary button-large" value="Upload or Choose Images" ng-click='managePhotos()'>
    </div>
    <div class="clear"></div>
  </div>
</div>

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
  <input id="photos" type="hidden" name="photos" />
  <input id="main_photo" type="hidden" name="main_photo" ng-model='mainPhoto' />

  <div id="sortable-container">
    <ul class="project-photos container" as-sortable="dragControlListeners" ng-model="photos" is-enabled="true">
      <li ng-repeat='photo in photos'
          class='project-photo {{mainPhoto.id === photo.id ? "project-main-photo" : ""}}'
          as-sortable-item >
        <div as-sortable-item-handle>
          <div class='thumb'>
            <img src='{{ photo.url }}' />
          </div>
          <div class='details'>
            <input type="text" class='title'   value='' ng-model='photo.title'   placeholder='Title'/>
            <input type="text" class='caption' value='' ng-model='photo.caption' placeholder='Caption'/>
            <div class='buttons'>
              <button class="button" ng-click='setMainPhoto($event, photo)'>Set as main photo</button>
              <button class="button" ng-click='removePhoto($event, photo)'>Remove Photo</button>
            </div>
          </div>
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

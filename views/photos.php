<div ng-app='app' ng-controller="PortfolioAdminController">
  <input id="upload_image_button" type="button" class="button button button-primary" value="Upload or Choose Images" ng-click='managePhotos()'/>
  <input id="upload_image_object" type="hidden" name="photos" value="<?=$photos?>" ng-model='photosJson' />

  <ul class="project-photos container">
    <li ng-repeat='photo in photos'
        class='project-photo {{mainPhoto.id === photo.id ? "project-main-photo" : ""}}'>
      <img src='{{ photo.url }}' />
      <button class="button button-small" ng-click='setMainPhoto($event, photo)'>Set as main photo</button>
      <button class="button button-small" ng-click='removePhoto($event, photo)'>Remove Photo</button>
    </li>
  </ul>
</div>

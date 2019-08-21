<?php

// Check if image file is a actual image or fake image
if(isset($_FILES["image_upload"])) {
  $output = "{}";
  FileUtil::makeDirs($imageUploadPath);
  $target_file = $imageUploadPath . basename($_FILES["image_upload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["image_upload"]["tmp_name"]);
  if($check !== false) {
    $output = "{\"success\": \"File is an image\"}";
    // echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    $output = "{\"fail\": \"File is not an image\"}";
    $uploadOk = 0;
  }
  // Check if file already exists
  if (file_exists($target_file)) {
    $output = "{\"fail\": \"Sorry, file already exists.\"}";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["image_upload"]["size"] > 10000000) {
    $output = "{\"fail\": \"Sorry, your file is too large.\"}";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    $output = "{\"fail\": \"Sorry, only JPG, JPEG, PNG & GIF files are allowed.\"}";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // $output = "{\"fail\": \"Sorry, your file was not uploaded.\"}";
  } else {
    // if everything is ok, try to upload file
    if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
      $output = "{\"success\": \"The file '" . basename( $_FILES["image_upload"]["name"]) . "' has been uploaded.\"}";
    } else {
      $output = "{\"fail\": \"Sorry, there was an error uploading your file.\"}";
    }
  }

  // output success
  JsonUtil::setJsonOutput();
  echo $output;

} else {
  echo $request->getPath();
?>
<div data-view-type="ImageUploadView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1><?php echo $pageTitle; ?></h1>
  <!-- <h1>Upload New Image</h1> -->
  <form id="image-upload-form" action="<?php echo $request->path(); ?>" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="two columns">&nbsp;</div>
      <div class="four columns upload-image-input">
        <input type="file" name="image_upload" >
        <img id="img-preview" width="100%">
      </div>
      <input class="four columns" type="submit" value="Upload" name="submit">
      <div class="two columns">&nbsp;</div>
    </div>
  </form>
  <p id="form-result"></p>
  <h1>Uploads</h1>
  <div id="uploads-container" class="grid-container thirds"></div>
</div>
<?php
}
?>

<?php

// form config - add this above include
// $appId = "boulder-1";
// $appName = "Boulder 1";
// $uploadsBasePath = 'apps/_uploads/'. $appId . '/';

// upload form paths
$formAction = $request->path() . "/upload";
$uploadsList = $request->path() . "/uploads";
// uploaded files dir
FileUtil::makeDirs($uploadsBasePath);

// PROCESS UPLOAD: ---------------------------------------------------------------------------
if($request->lastPathComponent() == "upload") {
  // upload config
  $allowedExts = array("jpg", "png", "mp4");
  $allowedTypes = array("image/jpeg", "image/png", "video/mp4");
  $maxUploadSize = 512 * 1000 * 1024; // 512mb

  $successMsg = null;
  $failMsg = null;

  // get uploaded file properties
  $fileUpload = (isset($_FILES['file_upload'])) ? $_FILES['file_upload'] : null;
  if($fileUpload != null) {
    $fileUploadName = str_replace(' ', '_', $fileUpload['name']);
    $fileUploadTempName = $fileUpload['tmp_name'];
    $fileUploadType = $fileUpload['type'];
    $fileUploadSize = $fileUpload['size'];
    $fileUploadError = $fileUpload['error'];
    $fileUploadExtension = FileUtil::fileExtension($fileUploadName);

    // validate upload
    $uploadSizeOkay = $fileUploadSize < $maxUploadSize;
    $uploadExtensionAllowed = in_array($fileUploadExtension, $allowedExts);
    $uploadTypeValid = in_array($fileUploadType, $allowedTypes);

    if($uploadTypeValid && $uploadSizeOkay && $uploadExtensionAllowed) {
      if ($fileUploadError > 0) {
        $failMsg = "[FAIL] Error return Code: $fileUploadError<br>";
      } else {
        $destFilePath = $uploadsBasePath . basename($fileUploadName);

        $successMsg .= "Upload size: " . FileUtil::fileSizeFromBytes($fileUploadSize) . "<br/>";
        $successMsg .= "Upload Success!<br/>";

        if(file_exists($destFilePath)) {
          $failMsg = "[FAIL] $fileUploadName already exists.<br/>";
        } else {
          chmod($fileUploadTempName, 0750);
          move_uploaded_file($fileUploadTempName, $destFilePath);
          $successMsg .= "Stored in: $destFilePath<br/>";
        }
      }
    } else {
      $failMsg = "[FAIL] Invalid file:<br/>";
      if(!$uploadSizeOkay) $failMsg .= "-- Upload too large<br/>";
      if(!$uploadTypeValid) $failMsg .= "-- Upload file type not allowed<br/>";
      if(!$uploadExtensionAllowed) $failMsg .= "-- File extension not allowed<br/>";
    }

    // add upload info to either message
    $msg = ($successMsg != null) ? $successMsg : $failMsg;
    $msg .= "fileUploadName: $fileUploadName<br>";
    $msg .= "fileUploadType: $fileUploadType<br>";
    $msg .= "fileUploadExtension: $fileUploadExtension<br>";
  } else {
    // or if the post variable doesn't exist
    $failMsg = '[FAIL] No $_FILES[file_upload]<br/>';
  }

  // return json response
  JsonUtil::setJsonOutput();
  if($failMsg != null) {
    JsonUtil::printFailMessage($failMsg);
  } else {
    JsonUtil::printSuccessMessage($successMsg);
  }


} else if($request->lastPathComponent() == "delete") {
// DELETE UPLOAD: --------------------------------------------------------------------------
  JsonUtil::setJsonOutput();
  // get data from post
  $formSubmitJson = $request->postedJson();
  if(isset($formSubmitJson['filepath'])) {

    // ensure the file file exists in the upload path
    $fileToDelete = $formSubmitJson['filepath'];
    if(strpos($fileToDelete, $uploadsBasePath) == 0) {
      FileUtil::deleteFile($fileToDelete);

      // return json response
      JsonUtil::printSuccessMessage("Deleted file: $fileToDelete");
    } else {
      JsonUtil::printFailMessage("Invalid upload delete request");
    }
  } else {
    JsonUtil::printFailMessage("No file specified to delete");
  }


} else if($request->lastPathComponent() == "uploads") {
// LIST UPLOADS AJAX RESPONSE HTML CHUNK FOR UPLOADS PAGE: ----------------------------------------------
  $html = "";
  if(file_exists($uploadsBasePath)) {
    // list files in upload path
    $uploadedFiles = FileUtil::get_files_sorted($uploadsBasePath);
    foreach($uploadedFiles as $file) {
      $filePath = $uploadsBasePath . $file;
      $fileType = FileUtil::fileExtension($file);
      $fileSize = FileUtil::fileSizeFromPath($filePath);
      $fileCreatedDate = FileUtil::fileCreatedTime($filePath);
      $html .= "<div class='upload-card'>";
      $html .= "  <div class='upload-container'>";
      if($fileType == "mp4") {
        $html .= "   <video src='/$filePath' width='100%' controls></video>";
      }  else {
        $html .= "   <img class='transparent-bg' src='/$filePath' width='100%'>"; //  data-zoomable data-zoom-src='/$filePath'
      }
      $html .= "  </div>";
      $html .= "  <div class='upload-info'><u>File</u>: $file<br><u>Size</u>: $fileSize<br><u>Type</u>: $fileType<br><u>Created</u>: $fileCreatedDate</div>";
      $html .= "  <div class='upload-actions grid-container thirds'>";
      $html .= "    <a class='button' href='/$filePath' target='_blank'>View</a>";
      $html .= "    <a class='button' href='/$filePath' download>Save &darr;</a>";
      $html .= "    <button class='btn-delete' data-action='delete' data-upload-path='$filePath'>Delete</button>";
      $html .= "  </div>";
      $html .= "</div>";
    }
    if(count($uploadedFiles) == 0) {
      $html = "<p>No uploads yet.</p>";
    }
  } else {
    $html = "<p>No uploads yet.</p>";
  }
  print($html);

} else if($request->lastPathComponent() == "json") {
// LIST UPLOADS AJAX RESPONSE JSON FOR EDITOR INTERFACES: ----------------------------------------------
  $jsonObj = array();
  if(file_exists($uploadsBasePath)) {
    // list files in upload path
    $uploadedFiles = FileUtil::get_files_sorted($uploadsBasePath);
    foreach($uploadedFiles as $file) {
      $filePath = $uploadsBasePath . $file;
      $fileType = FileUtil::fileExtension($file);
      $fileSize = FileUtil::fileSizeFromPath($filePath);
      $fileCreatedDate = FileUtil::fileCreatedTime($filePath);
      $fileInfo = new stdClass();
      $fileInfo->filePath = $filePath;
      $fileInfo->fileType = $fileType;
      $fileInfo->fileSize = $fileSize;
      $jsonObj[] = $fileInfo;
    }
  }
  JsonUtil::setJsonOutput();
  JSONUtil::printJsonObj($jsonObj);

} else {
// HTML FORM: ---------------------------------------------------------------------------
?>

<div data-view-type="UploadView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1><?php echo $appName; ?> Uploads</h1>
  <p class="grid-container quarters">
    <a class="button button-primary" href="javascript:window.history.back();">&larr; Back</a>
  </p>
  <!-- Upload form -->
  <h4>Upload new images and videos</h4>
  <p>You can also drag & drop files onto this form:</p>
  <form id="upload-form" action="<?php echo $request->path(); ?>/upload" data-uploads="<?php echo $uploadsList; ?>" data-delete="<?php echo $request->path(); ?>/delete" method="post" enctype="multipart/form-data">
    <div class="grid-container halves">
      <div class="upload-file-input">
        <input type="file" name="file_upload"  accept="image/png,image/jpeg,.mp4" />
        <img data-zoomable id="img-preview" width="100%">
        <video id="video-preview" width="100%" controls playsinline loops></video>
      </div>
      <input class="button-primary" type="submit" value="Upload" name="submit">
    </div>
  </form>
  <p id="upload-form-result"></p>
  <h1>Uploads</h1>
  <div id="uploads-container"></div>
</div>

<?php
}
?>

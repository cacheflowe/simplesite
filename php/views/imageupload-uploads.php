<?php

// load schedule json
$scheduleData = getScheduleJsonData();

// $imageUploadPath set in `mentors-uploads.php` and `takeovers-uploads.php`
if(file_exists($imageUploadPath)) {
  $uploaded_files = FileUtil::get_files_sorted($imageUploadPath);
  $imageKey = $request->pathComponents()[0];
  $curActiveImage = $scheduleData[$imageKey];

  foreach($uploaded_files as $file) {
    $filePath = $imageUploadPath . $file;
    $active = ($filePath == $curActiveImage) ? "active" : "";
    print("<div class='image-container'>");
    print("<img src='/$imageUploadPath$file'>");
    print("<button class='$active btn-check' data-action='activate' data-image-path='$filePath' data-image-key='$imageKey'></button>");
    print("<button class='btn-delete' data-action='delete' data-image-path='$filePath'></button>");
    print("</div>");
  }
  if(count($uploaded_files) == 0) {
    print("<p>No uploads yet.</p>");
  }
} else {
  print("<p>No uploads yet.</p>");
}

?>

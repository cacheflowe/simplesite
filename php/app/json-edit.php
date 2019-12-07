<?php

class JsonEdit {

  const MODE_APP = "app";           // has publish/reset, app preview links, asset uploads
  const MODE_CONFIG = "config";     // builds form for us
  const MODE_DEFAULT = "default";   // basic json editor
  const ACTION_UPDATE = "update";
  const ACTION_RESET = "reset";
  const ACTION_PUBLISH = "publish";

  function __construct($name, $es6Class, $dataPath, $mode=JsonEdit::MODE_DEFAULT, $dataPublishPath=null, $appId=null, $appNumber=0, $assetsListPath=null, $previewLinkPath=null) {
    // get request/app globals
    global $request;
    global $metadata;
    $this->request = $request;
    $this->action = $this->request->lastPathComponent();
    $this->pageTitle = $metadata->get_pageTitle();
    // store essentials
    $this->appName = $name;
    $this->es6Class = $es6Class;
    $this->dataPath = $dataPath;
    $this->dataFromDisk = JsonUtil::getJsonFromFile($this->dataPath);
    $this->lastModified = filemtime($this->dataPath);
    $this->mode = $mode;
    // app-specific vars
    $this->appId = $appId;
    $this->appNumber = $appNumber;
    $this->assetsListPath = $assetsListPath;
    $this->previewLinkPath = $previewLinkPath;
    // publishing props
    $this->dataPublishPath = $dataPublishPath;
    $this->dataIsSameAsPublished = FileUtil::filesAreIdentical($this->dataPath, $this->dataPublishPath);
    $this->lastPublished = filemtime($this->dataPublishPath);
    // run it!
    $this->createResponse();
  }

  // RESPONSES

  function createResponse() {
    switch ($this->action) {
      case JsonEdit::ACTION_UPDATE:
        $this->saveData();  break;
      case JsonEdit::ACTION_RESET:
        $this->resetData();   break;
      case JsonEdit::ACTION_PUBLISH:
        $this->publishData(); break;
      default:
        $this->buildForm();   break;
    }
  }

  function saveData() {
    // get posted json  & set api output
    $formPostJson = $this->request->postedJson();
    JsonUtil::setJsonOutput();

    // loop through top-level posted json and copy into json data on disk
    if(JsonUtil::isValidJSON(JsonUtil::jsonDataObjToString($formPostJson))) {
      // loop through top-level json objects and copy to json file
      foreach ($formPostJson as $key => $value) {
        $this->dataFromDisk[$key] = $value;
      }
      // back up if MODE_DEFAULT. otherwise, we'd do this on publish
      if($this->mode == JsonEdit::MODE_DEFAULT) $backupSuccess = JsonUtil::backupJsonFile($this->dataPath);
      // write to file & success message
      JsonUtil::writeJsonToFile($this->dataPath, $this->dataFromDisk);
      JsonUtil::printSuccessMessage("Updated $this->appName app data");
    } else {
      // output fail
      JsonUtil::printFailMessage("Invalid JSON posted");
    }
  }

  function publishData() {
    // copy CMS config to app static config
    JsonUtil::setJsonOutput();
    // copy old to backup, and new to static
    $backupSuccess = JsonUtil::backupJsonFile($this->dataPath);
    $publishSuccess = copy($this->dataPath, $this->dataPublishPath);
    if($backupSuccess && $publishSuccess) {
      JsonUtil::printSuccessMessage("Published $this->appName data");
    } else {
      JsonUtil::printFailMessage("Couldn't publish $this->appName data");
    }
  }

  function resetData() {
    JsonUtil::setJsonOutput();
    $resetSuccess = copy($this->dataPublishPath, $this->dataPath);
    if($resetSuccess) {
      JsonUtil::printSuccessMessage("Reset $this->appName data from live data");
    } else {
      JsonUtil::printFailMessage("Couldn't reset $this->appName data");
    }
  }

  function getAppHeaderNav() {
    if ($this->mode != JsonEdit::MODE_APP) return "";  // only show nav for app editors
    if ($this->appNumber == 0) return "";   // only show nav for app editors
    return <<<EOD
      <p class="grid-container quarters">
        <a class="button button-primary" href="javascript:window.history.back();">&larr; Back</a>
        <a class="button button-primary" href="/$this->appId/uploads/$this->appNumber">Uploads</a>
        <a class="button button-primary" href="/$this->appId/uploads/$this->appNumber/uploads/json">Assets JSON</a>
      </p>
EOD;
  }

  function modifiedDateMessage() {
    $dateFormat = "F d, Y g:i:s A";
    $modifiedText = "Last modified: <code>" . date($dateFormat, $this->lastModified) . "</code>";
    $publishedText = ($this->dataPublishPath != null) ? "Last published: <code>" . date($dateFormat, $this->lastPublished) . "</code>" : "";
    return <<<EOD
      <p class="">
        $modifiedText <br>
        $publishedText
      </p>
EOD;
  }

  function actionButtonsPublish() {
    $publishButtons = ($this->dataIsSameAsPublished == true || !file_exists($this->dataPublishPath)) ? "" : <<<EOD
    <button class="button-primary" data-form-reset-from-publish="true">Reset</button>
    <button class="button-primary" data-form-publish="true">Publish</button>
EOD;
    $previewButton = ($this->mode != JsonEdit::MODE_APP) ? "" : <<<EOD
      <a href="$this->previewLinkPath" class="button button-primary">Preview</a>
EOD;
    return <<<EOD
    <div class="grid-container quarters">
      <button type="submit" class="button-primary" data-form-submit="true">Save Draft</button>
      $publishButtons
      $previewButton
    </div>
EOD;
  }

  function actionButtonsBasic() {
    return <<<EOD
      <div class="grid-container quarters">
        <button type="submit" class="button-primary" data-form-submit="true">Save Data</button>
      </div>
EOD;
  }

  function buildForm() {
    $path = $this->request->path();
    $appHeaderNav = $this->getAppHeaderNav();
    $draftMessage = ($this->dataIsSameAsPublished == false && file_exists($this->dataPublishPath)) ? Templates::draftDataMessage() : "";
    $modifiedDateMessage = $this->modifiedDateMessage();
    $actionButtons = ($this->dataPublishPath != null) ? $this->actionButtonsPublish() : $this->actionButtonsBasic();
    $assetsListPath = ($this->assetsListPath != null) ? 'data-assets-list-path="' . $this->assetsListPath . '"' : "";
    $str = <<<EOD
    <div data-view-type="$this->es6Class" data-page-title="$this->pageTitle">
      <h1>$this->appName Editor</h1>
      $appHeaderNav
      <form id="json-editor-form" action="$path/update" data-path="/$this->dataPath" $assetsListPath>
        <div id="json-editor-form-content"><!-- Filled by app form's es6 object --></div>
        $draftMessage
        $modifiedDateMessage
        $actionButtons
      </form>
    </div>
EOD;
    echo $str;
  }
}

?>

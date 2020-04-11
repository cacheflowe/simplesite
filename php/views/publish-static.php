<?php
    // NOTE:
    // create array of pages to generate static copies of
    $dirs = array();
    $dirs[] = 'css';
    $dirs[] = 'data';
    $dirs[] = 'fonts';
    $dirs[] = 'images';
    $dirs[] = 'js';
    $pages = array();
    $pages[] = '/home';
    $pages[] = '/about';
    $pages[] = '/collection/one';
?>
<div data-view-type="BaseSiteView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Publish</h1>
  <p><b>Deleting previous version...</b></p>
  <?php
    FileUtil::deleteDir('_static/');
  ?>
  <p><b>Publishing static content...</b></p>
  <p><b>Copying directories...</b></p>
  <ul>
    <?php
      foreach ($dirs as $dir) {
        // create/copy directories
        FileUtil::copyRecursive($dir, '_static/' . $dir);
        // log links to static pages
        print('<li>Dir: '. $dir . ' -&gt; ' . '_static/' . $dir);
      }
    ?>
  </ul>
  <p><b>Copying Pages...</b></p>
  <ul>
    <?php
      foreach ($pages as $page) {
        // get original page html
        $originalPage = $request->host() . $page . '?notDev';
        $pageContents = file_get_contents($originalPage);
        // transform path to friendly static html filename
        $filename = substr($page, 1);
        // create dir
        $newPageDir = '_static/' . $filename;
        FileUtil::makeDirs($newPageDir);
        // write to file
        $pagePublishPath = $newPageDir . '/index.html';
        FileUtil::writeTextToFile($pagePublishPath, $pageContents);
        // log links to static pages
        print('<li>'. $originalPage .'');
        print('<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="'. $newPageDir .'">'. $newPageDir .'</a></li>');
      }
    ?>
  </ul>
</div>

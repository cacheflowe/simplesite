<?php
    // TODO: 
    // - Copy js/css/assets directories recusively
    // NOTE:
    // - Static sites should be built as such up front with relative pathing. there's not a reasonable way to turn cacheflowe.com into a statuc site right now
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
    $pages[] = '/count';
?>
<div data-view-type="BaseView" data-page-title="<?php global $metadata; echo $metadata->get_pageTitle(); ?>">
  <h1>Publish</h1>
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
        // write to file
        $pagePublishPath = '_static/' . $filename . '.html';
        FileUtil::writeTextToFile($pagePublishPath, $pageContents);
        // log links to static pages
        print('<li>'. $originalPage .'');
        print('<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="'. $pagePublishPath .'">'. $pagePublishPath .'</a></li>');
      }
    ?>
  </ul>
</div>

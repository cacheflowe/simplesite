<?php

class FileUtil {

    // create dir
    public static function makeDirs($dirpath, $mode=0777) {
      return is_dir($dirpath) || mkdir($dirpath, $mode, true);
    }

    // rm -rf
    public static function rrmdir($dir) {
      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (is_dir($dir."/".$object))
              rrmdir($dir."/".$object);
            else
              unlink($dir."/".$object);
          }
        }
        rmdir($dir);
      }
    }

    public static function deleteFile($filePath) {
      if(file_exists($filePath)) {
        unlink($filePath);
      }
    }

    // convert base64 image to file
    public static function base64_to_png($base64_string, $output_file) {
      // open the output file for writing
      $ifp = fopen( $output_file, 'wb' );
      fwrite( $ifp, base64_decode( $base64_string ) );
      // clean up the file resource
      fclose( $ifp );
      // return $output_file;
    }

    public static function get_files_sorted($path, $reverse=true) {
      $files = array();
      $dir = opendir($path); // open the cwd..also do an err check.
      while(false != ($file = readdir($dir))) {
        if(($file != ".") and ($file != "..") and ($file != ".DS_Store")) {
          $files[] = $file; // put in array.
        }
      }
      natsort($files); // sort.
      if($reverse == true) $files = array_reverse($files, true);
      closedir($dir);
      return $files;
    }

}

?>

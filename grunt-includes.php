<h1>.js</h1>
<?php
$handle = fopen("php/includes/js.php", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        // echo($line.'<br>');
        //
        $subject = $line;
        $pattern = '/(\/.+)"/';
        preg_match($pattern, $subject, $matches);
        if(empty($matches) == false) {
          $fileName = $matches[1];
          // include non-es6 files without modification, or add temporary babel /min folder if transpiling
          if(strpos($fileName, ".es6") === false) {
            echo('"'.substr($fileName, 1).'",<br>');
          } else {
            $fileName = str_replace(".es6" , "", $fileName);
            $fileName =  '"js/min' . $fileName;
            $fileName = str_replace("js/min/js" , "js/min", $fileName);
            echo($fileName.'",<br>');
          }
        }

    }
    fclose($handle);
} else {
    echo("ERROR");
}
?>
<h1>.css</h1>

<?php
$handle = fopen("php/includes/css.php", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        // echo($line.'<br>');
        //
        $subject = $line;
        $pattern = '/(\/.+)"/';
        preg_match($pattern, $subject, $matches);
        if(empty($matches) == false) {
          $fileName = $matches[1];
          // include non-es6 files without modification, or add temporary babel /min folder if transpiling
          echo('"'.substr($fileName, 1).'",<br>');
        }

    }
    fclose($handle);
} else {
    echo("ERROR");
}

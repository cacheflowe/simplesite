<?php
// don't return html fragment
global $request;
$request->setAPI(true);

// convert timestamp to string
// https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function cleanUsername($name) {
  $name = strtoupper($name);
  $name = str_replace('FUK', 'YOU', $name);
  $name = str_replace('GAY', 'YOU', $name);
  $name = str_replace('DIK', 'YOU', $name);
  $name = str_replace('DIC', 'YOU', $name);
  $name = str_replace('SUX', 'YOU', $name);
  $name = str_replace('FUX', 'YOU', $name);
  $name = str_replace('FAG', 'YOU', $name);
  $name = str_replace('ASS', 'YOU', $name);
  $name = str_replace('HOE', 'YOU', $name);
  $name = str_replace('SEX', 'YOU', $name);
  return $name;
}


// config
$maxScores = 20;
$scoresFile = "./data/json/scores.json";
$oneDay = 24 * 60 * 60;
$now = time();

// load leaderboard file
$scoresJSON = file_get_contents($scoresFile);
$jsonObj = json_decode($scoresJSON, true);

// scrub any old "new" properties
$dirty = false;
foreach ($jsonObj['scores'] as $index => $el) {
  if($el['new'] == 1) {
    unset($jsonObj['scores'][$index]['new']);
    $dirty = true;
  }
}

// set old flag on older scores, to remove comparison with new score
foreach ($jsonObj['scores'] as $index => $el) {
  if($el["date"] < $now - $oneDay) {
    $jsonObj['scores'][$index]['old'] = true;
  }
}


//////////////////////////////////////////////////
// add new score if one was submitted
// 'new' property is removed on next view
//////////////////////////////////////////////////
if(strlen($request->postBody()) > 0) {
  // store in session so people can't just duplicate the POST
  session_start();

  // get post body and decode to JSON
  $postStr = base64_decode($request->postBody());
  $postJSON = json_decode($postStr);

  // check to see if this is the same as the last POST. if not, add the score and store to session
  if($postJSON != $_SESSION["submit_json"]) {
    $_SESSION["submit_json"] = $postJSON;

    // if we have legit values, add it to the array
    if(isset($postJSON->score) && isset($postJSON->user)) {
      $newScore = array(
        "score" => $postJSON->score,
        "date" => time(),
        "user" => substr($postJSON->user, 0, 3),
        "new" => 1
      );
      $jsonObj['scores'][] = $newScore;
      $dirty = true;
      // increment count
      $jsonObj['count']++;
    }
  }
}

// if we have too many scores,
// sort on date & remove any older than 1 day. the oldest get removed first
$numScores = count($jsonObj['scores']);
if($numScores > $maxScores) {
  // how many to remove?
  $numToRemove = $numScores - $maxScores;
  $numRemoved = 0;
  // sort by date
  usort($jsonObj['scores'], function($a, $b) {
    return $a['date'] - $b['date'];
  });
  // loop through an remove any that are a day old or older
  // but stop once we've reached our correct number of scores
  foreach ($jsonObj['scores'] as $index => $el) {
    if($el["date"] + $oneDay < $now) {
      if($numRemoved < $numToRemove) {
        $numRemoved++;
        unset($jsonObj['scores'][$index]);
      }
    }
  }
}

// sort on score for descending display
usort($jsonObj['scores'], function($a, $b) {
    return $b['score'] - $a['score'];
});

// write scores file and write to response
if($dirty == true) {
  $resultJSON = json_encode($jsonObj, JSON_PRETTY_PRINT);
  file_put_contents($scoresFile, $resultJSON);
}

// change timestamps to human-readable and print to response
foreach ($jsonObj['scores'] as $index => $el) {
  $jsonObj['scores'][$index]["user"] = cleanUsername($el["user"]);
  $jsonObj['scores'][$index]["timestamp"] = $el["date"];
  $jsonObj['scores'][$index]["date"] = time_elapsed_string('@'.$el["date"]);
  if($index >= $maxScores) unset($jsonObj['scores'][$index]);  // only show max scores on final data render
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($jsonObj, JSON_PRETTY_PRINT);

?>

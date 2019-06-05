<?php

class Templates {

  public static function monthSelect($index, $curMonth) {
    $months = array(
      new MonthData(5, "May"),
      new MonthData(6, "Jun"),
      new MonthData(7, "Jul")
    );

    $html = "";
    $html .= '<select class="two columns" data-type="month" name="month-' . $index . '" id="month-' . $index . '">';
      foreach ($months as $month) {
        $selected = "";
        if($month->number == $curMonth) $selected = "selected";
        $html .= '<option value="'. $month->number .'" '. $selected .'>'. $month->title .'</option>';
      }
    $html .= '</select>';
    return $html;
  }

  public static function daySelect($index, $curDay) {
    $html = "";
    $html .= '<select style="" class="one columns" data-type="day" name="day-' . $index . '" id="day-' . $index . '">';
      foreach (range(1, 31) as $day) {
        $selected = "";
        if($day == $curDay) $selected = "selected";
        $html .= '<option value="'. $day .'" '. $selected .'>'. $day .'</option>';
      }
    $html .= '</select>';
    return $html;
  }

  public static function timeSelect($index, $curTime, $dataType) {
    $html = "";
    $html .= '<select class="two columns" data-type="' . $dataType . '" name="time-' . $index . '" id="time-' . $index . '">';
      $html .= '<option value="-1">None</option>';
      foreach (range(0, 24 * 4 - 1) as $time) {
        $quarterHour = $time / 4.0;
        $hours = floor($quarterHour);
        $minutes = fmod($quarterHour, 1) * 60;
        $timeStamp = mktime($hours, $minutes);
        $hourStr = date("h", $timeStamp);
        $minuteStr = date("i", $timeStamp);
        $amPm = ($hours >= 12) ? "pm" : "am";

        $selected = "";
        if($quarterHour == $curTime) $selected = "selected";
        $html .= '<option value="'. $quarterHour .'" '. $selected .'>'. $hourStr . ":" . $minuteStr . $amPm .'</option>';
      }
    $html .= '</select>';
    return $html;
  }

}

?>

<?php

class DateUtil {

  public static function getYear() {
    return date("Y");
  }

  public static function getSimpleDateFromJsDate($dateStr) {
    return substr($dateStr, 0, 10);
  }

  public static function getReadableDateFromJsDate($jsDate) {
    $date = getDateTimeFromYearMonthDay(getSimpleDateFromJsDate($jsDate));
    return $date->format('M j, Y');
  }

  public static function getDateTimeFromYearMonthDay($dateTimeStamp) {
    // $format = 'Y-m-d-H-i-s';
    $format = 'Y-m-d';
    return DateTime::createFromFormat($format, $dateTimeStamp); // 2009-02-15 15:16:17
  }

  public static function createTimestamp() {
    return date('Y-m-d-H-i-s');
  }

  public static function getCurrentYearMonthDay() {
    $now = new DateTime();
    return $now->format('Y-m-d');
  }

  public static function dateStampOffsetFromNow($dateStamp) {
    $otherDate = getDateTimeFromYearMonthDay($dateStamp);
    $now = getDateTimeFromYearMonthDay(getCurrentYearMonthDay());
    $dateDiff = $otherDate->diff($now);
    return intval( $dateDiff->format('%R%a') );
  }

  public static function jsDateDaysAgo($jsDate) {
    return dateStampOffsetFromNow(getSimpleDateFromJsDate($jsDate));
  }

  public static function sortByDate($a, $b) {
    return strcmp($b["date"], $a["date"]);
  }

}

?>

#!/usr/bin/php

<?php
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

date_default_timezone_set('Asia/Tokyo');
$script_ts_file = "/Users/xxxxxxx/Documents/Timeline/qloogwiki/ts.txt";
$rss_string = file_get_contents("http://xxxxxxxxxxx/wiki/index.php?cmd=rss&ver=2.0");
$rss_object = simplexml_load_string($rss_string);
$rss_array = objectsIntoArray($rss_object);
foreach( $rss_array['channel']['item'] as $item ){
	$script_ts = file_get_contents($script_ts_file);
	$date = substr($item['pubDate'],5);
	$date = date_parse($date);
	if ( $date['month'] < 10 ){ $date['month'] = "0".$date['month']; }
	if ( $date['day'] < 10 ){ $date['day'] = "0".$date['day']; }
	if ( $date['hour'] < 10 ){ $date['hour'] = "0".$date['hour']; }
	if ( $date['minute'] < 10 ){ $date['minute'] = "0".$date['minute']; }
	if ( $date['second'] < 10 ){ $date['second'] = "0".$date['second']; }
	$rss_ts = $date['year']."-".$date['month']."-".$date['day']." ".$date['hour'].":".$date['minute'].":".$date['second'];
	if ( $rss_ts > $script_ts ) {
		echo $item['title']." ".$item['pubDate']."\n";
		exec('osascript /Users/xxxxx/Documents/Timeline/qloogwiki/qloog_rss.scpt "'.$item['title'].'" "'.$item['pubDate'].'"');
	}
	file_put_contents($script_ts_file, date("Y-m-d H:i:s"));
}
?>
		

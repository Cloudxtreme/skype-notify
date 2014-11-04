#!/usr/bin/php
<?php
date_default_timezone_set("GMT");
$script_ts_file = "/Users/xxxxxxxxx/Documents/Timeline/sugar/ts.txt";
$script_ts = file_get_contents($script_ts_file);
$script_ts = "2013-06-21 07:55:00";

mysql_connect('xxxxxxxxx','xxxxxxxx','xxxxxxxxx') or die(mysql_error());
mysql_query("use sugarcrm");
mysql_query("set names utf8");
$query = "select ";
$query .= "(select first_name from users where id = sugarfeed.created_by) as first_name,";
$query .= "(select last_name from users where id = sugarfeed.created_by) as last_name,";
$query .= "name as description,";
$query .= "date_modified from sugarfeed";
$res = mysql_query($query);
while( $row = mysql_fetch_assoc($res) ){
	// parse string for event type
	$user = $row['first_name']." ".$row['last_name'];
	$date = $row['date_modified'];
	$string = substr($row['description'],36);
	
	if ( $date > $script_ts ){
		echo $user." ".$date."\n".$row['description']."\n";
		
		$marker1 = strpos($string,"}");
		$marker2 = strpos($string,":",$marker1);
		if( $string[63] = ":" ){
			$marker3 = strpos($string,":",$marker2+1);
			$marker4 = strpos($string,"]",$marker3);
		
			$event = substr($string,0,$marker1);
			$event = str_replace("_"," ",$event);
			$event = strtolower($event);
			$detail = substr($string,$marker3,$marker4-$marker3)."\n";
			$detail = str_replace(":","",$detail);
			$arg = $user." ".$event.": ".$detail."\n";
		}
		else{
			$detail = $string;
		}
				
		exec("osascript /Users/xxxxxxxx/Documents/Timeline/sugar/sugar.scpt \"".$arg."\"");		
	}
}

file_put_contents($script_ts_file,date("Y-m-d H:i:s"));
?>
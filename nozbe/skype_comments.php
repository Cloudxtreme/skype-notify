#!/usr/bin/php -q
<?php
/*
 This script does the following:
 1. get task and comment information from mysql.
 2. loop through the information and run the applescript to send information to skype.
*/

require 'formatTS.php';
date_default_timezone_set('Asia/Tokyo');
define ('ts_file', '/Users/xxxxxxx/Documents/Timeline/nozbe/ts_comments.txt');

// Set time stamps
$old_ts = $new_ts = file_get_contents(ts_file);
if ( $conn = mysql_connect('xxxxxxxxxxxx','xxxxxxxxxx','') ){
	$new_ts = date('Y\-m\-d H:i:s');
}
mysql_query("use timeline") or die(mysql_error()."\n");
mysql_query("set names utf8") or die("Could not set the character set");
$query = "select (select name from nozbe_users where hash = nozbe_comments.user_hash) as username, (select name from nozbe_tasks where hash = nozbe_comments.task_hash limit 1) as task_name, body from nozbe_comments where date_posted > '".$old_ts."'";
$res = mysql_query($query) or die(mysql_error());
while( $row = mysql_fetch_assoc($res) ){
	exec('osascript /Users/xxxxxxxx/Documents/Timeline/nozbe/nozbe_comments.scpt "'.$row['username'].'" "'.$row['task_name'].'" "'.$row['body'].'"');
	print_r($row);
}
echo "testing";
file_put_contents(ts_file,$new_ts) or die("There was a problem writing to the ts file.");

?>

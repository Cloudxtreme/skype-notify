#!/usr/bin/php -q
<?php

require 'formatTS.php';
require 'users.php';
date_default_timezone_set('Asia/Tokyo');
define('ts_file','/Users/xxxxxx/Documents/Timeline/nozbe/ts_tasks.txt');

// Set time stamps
$old_ts = $new_ts = file_get_contents(ts_file);
if ( $conn = mysql_connect('xxxxxxxx','xxxxxxxxx','') ){
	$new_ts = date('Y\-m\-d H:i:s');
	echo "Connected to mysql.\n";
}

mysql_query('set names utf8');
mysql_query('use timeline');
$query = "select ";
$query .= "(select name from nozbe_users where hash = nozbe_tasks.by_user limit 1) as username, ";
$query .= "(select name from nozbe_users where hash = nozbe_tasks.for_user limit 1) as for_user, ";
$query .= "name from nozbe_tasks where date_added > '".$old_ts."'";
$result = mysql_query($query);
while ( $task = mysql_fetch_assoc($result) ){
	if ( $task['for_user'] == "" ){
		$username = $task['username'];
	}
	else {
		$username = $task['for_user'];
	}
	
	exec('osascript /Users/xxxxxxxxx/Documents/Timeline/nozbe/nozbe_tasks.scpt "'.$username.'" "'.$task['name'].'"');
}
mysql_close();
echo "done\n";
file_put_contents(ts_file,$new_ts);

?>

#!/usr/bin/php
<?php
echo extension_loaded('pgsql') ? 'yes':'no';
///Library/Server/Web/Config/php
date_default_timezone_set('Asia/Tokyo');
$file = "/Users/xxxxxxx/Documents/Timeline/aipo/ts_aipo.txt";

$last_check = file_get_contents($file);
echo "The last check was ".$last_check."\n";

$conn_string = "host=xxxxxxxxx port=xxxx dbname=xxxx user=xxxxxxxx password=xxxxxxxxxxx";
$dbconn = pg_connect($conn_string);
echo pg_last_error($dbconn);
$query = "select (select first_name from turbine_user where user_id = eip_t_timeline.owner_id) as username,note,create_date,update_date from eip_t_timeline where update_date > '".$last_check."' and note != '' order by update_date";
$res = pg_query($dbconn,$query);
while ( $row = pg_fetch_row($res) ){
    exec('osascript /Users/xxxxxxx/Documents/Timeline/aipo/skype_aipo.scpt "'.$row[0].'" "'.$row[1].'" "'.$row[2].'"');
}

file_put_contents($file,date('Y-m-d H:i:s'));
?>

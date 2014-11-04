#!/usr/bin/php -q
<?php

/* This script is currently automatically updating the `users` table in mysql.*/

// api stuff
$api_address = "http://webapp.nozbe.com/sync2/getdata/key-xxxxxxxxxx/what-team/app_key-xxxxxxxxxx";
$team = file_get_contents($api_address);
$team_json = json_decode($team);

// mysql stuff
mysql_connect('xxxxxxxxxx','xxxxxxxxx','') or die("Could not connect to mysql\n.");
mysql_query('set names utf8') or die("Could not change the character set.\n");
mysql_query('use timeline') or die("Could not change the database.\n");

for ( $i = 0; $team_json->team->{$i}; $i++ ){
	$query = "select hash from nozbe_users where hash = '".$team_json->{team}->{$i}->{hash}."'";
	if ( mysql_fetch_assoc(mysql_query($query)) == ""){
		$query = 'insert into nozbe_users values ("';
		$query .= $team_json->team->{$i}->hash.'","';
		$query .= $team_json->team->{$i}->name.'","';
		$query .= $team_json->team->{$i}->user_name.'","';
		$query .= $team_json->team->{$i}->pin.'")';
		mysql_query($query) or die($query);
	}
}

mysql_close() or die("Could not close the mysql connection.\n");
?>
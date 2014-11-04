#!/usr/bin/php -q
<?php
/* This script gets the comments from Nozbe and adds them to mysql on 192.168.25.204. */

require 'formatTS.php';
date_default_timezone_set('Asia/Tokyo');

$html = file_get_contents('http://webapp.nozbe.com/sync2/getdata/key-xxxxxxxxxx/what-task/app_key-xxxxxxxxxxxxxx');
$info = json_decode($html);

mysql_connect('xxxxxxxxxx','xxxxxxx','');
$query  = 'use timeline';
mysql_query($query);
mysql_query("set names utf8") or die("Couldn't change the character set.");

// loop through each task
for ( $i = 0 ; $i < count($info->task) ; $i++ ){
	// if there are no comments for the current task, continue
	if ( !$info->task[$i]->comments ){ continue; }
	
	// loop through each comment of the current task 
	for( $j = 0; $j < count($info->task[$i]->comments); $j++){	
		if ( $info->task[$i]->comments[$j]->body == "This comment was removed" ){ continue; }
		$info->task[$i]->comments[$j]->body = str_replace("\n","*NL*",$info->task[$i]->comments[$j]->body);
		$info->task[$i]->comments[$j]->body = str_replace("'","\"",$info->task[$i]->comments[$j]->body);
		$query = "insert into nozbe_comments values('";
		$query .= $info->task[$i]->hash."','";
		$query .= $info->task[$i]->comments[$j]->user_hash."','";
		$query .= $info->task[$i]->comments[$j]->body."','";
		$query .= date('Y\-m\-d H:i:s')."')";
		
		//send the query if the record doesn't already exist
		$query2 = "select task_hash,user_hash,body,date_posted from nozbe_comments where ";
		$query2 .= "task_hash = '".$info->task[$i]->hash."' and ";
		$query2 .= "user_hash = '".$info->task[$i]->comments[$j]->user_hash."' and ";
		$query2 .= "body = '".$info->task[$i]->comments[$j]->body."'";
		if ( mysql_fetch_row(mysql_query($query2)) == "" ){
			mysql_query($query);
		}
	}
}

mysql_close();
?>

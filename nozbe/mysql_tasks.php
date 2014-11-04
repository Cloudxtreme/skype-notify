#!/usr/bin/php -q
<?php
/* get finished and unfinished tasks.  */

require 'formatTS.php';
date_default_timezone_set('Asia/Tokyo');

$html = file_get_contents('http://webapp.nozbe.com/sync2/getdata/key-xxxxxxxxxxx/what-task/app_key-xxxxxxxxxxxx');
$html2 = file_get_contents('http://webapp.nozbe.com/sync2/getdata/key-xxxxxxx/what-task/showdone-1/app_key-xxxxxxxxxxxxxxx');
$info = json_decode($html);
$info2 = json_decode($html2);

mysql_connect('xxxxxxxx','xxxxxxxx','') or die("There was an error connecting to mysql.\n");
$query  = 'use timeline';
mysql_query($query);
mysql_query("set names utf8") or die("Couldn't change the character set.\n");

// loop through the unfinished tasks
for ( $i = 0 ; $i < count($info->task) ; $i++ ){
	// if the current task doesn't already exist insert it
	if ( mysql_fetch_row(mysql_query("select hash from nozbe_tasks where hash = '".$info->task[$i]->hash."'")) == "" ){
		$query = 'insert into nozbe_tasks values("';
		$query .= $info->task[$i]->hash.'","';
		$query .= $info->task[$i]->name.'","';
		$query .= formatTS($info->task[$i]->date,9).'","';
		$query .= formatTS($info->task[$i]->datetime,9).'","';
		$query .= $info->task[$i]->time.'","';
		$query .= $info->task[$i]->project_hash.'","';
		$query .= $info->task[$i]->by_user.'","';
		$query .= $info->task[$i]->re_user.'","';
		$query .= formatTS($info->task[$i]->ts,9).'","';
		$query .= date('Y\-m\-d H:i:s').'")';
		mysql_query($query) or die(mysql_error()."\n");
	}
	// otherwise update the current task with new information
	else{
		$query = "update nozbe_tasks set ";
		$query .= "name = '".$info->task[$i]->name."',";
		$query .= "date_completed = '".formatTS($info->task[$i]->date,9)."',";
		$query .= "due_date = '".formatTS($info->task[$i]->datetime,9)."',";
		$query .= "time = '".$info->task[$i]->time."',";
		$query .= "by_user = '".$info->task[$i]->by_user."',";
		$query .= "for_user = '".$info->task[$i]->re_user."',";
		$query .= "ts = '".formatTS($info->task[$i]->ts,9)."'";
		$query .= " where hash = '".$info->task[$i]->hash."'";
		mysql_query($query) or die("There was an error updating the tasks.\n");
	}
}

// loop through the finished tasks
for ( $i = 0 ; $i < count($info2->task) ; $i++ ){
	// just in case the current finished task hasn't been inserted before, do a check
	if ( mysql_fetch_row(mysql_query("select hash from nozbe_tasks where hash = '".$info2->task[$i]->hash."'")) == "" ){
		$query = 'insert into nozbe_tasks values("';
		$query .= $info2->task[$i]->hash.'","';
		$query .= $info2->task[$i]->name.'","';
		$query .= formatTS($info2->task[$i]->date,9).'","';
		$query .= formatTS($info2->task[$i]->datetime,9).'","';
		$query .= $info2->task[$i]->time.'","';
		$query .= $info2->task[$i]->project_hash.'","';
		$query .= $info2->task[$i]->by_user.'","';
		$query .= $info2->task[$i]->re_user.'","';
		$query .= formatTS($info2->task[$i]->ts,9).'","';
		$query .= date('Y\-m\-d H:i:s').'")';
		mysql_query($query) or die("There was an error adding the tasks to the database for completed tasks.\nThe query is: ".$query);
	}
	// update the current finished task
	else{
		$query = "update nozbe_tasks set ";
		$query .= "name = '".$info2->task[$i]->name."',";
		$query .= "date_completed = '".formatTS($info2->task[$i]->date,9)."',";
		$query .= "due_date = '".formatTS($info2->task[$i]->datetime,9)."',";
		$query .= "time = '".$info2->task[$i]->time."',";
		$query .= "by_user = '".$info2->task[$i]->by_user."',";
		$query .= "for_user = '".$info2->task[$i]->re_user."',";
		$query .= "ts = '".formatTS($info2->task[$i]->ts,9)."'";
		$query .= " where hash = '".$info2->task[$i]->hash."'";
		mysql_query($query) or die("There was an error updating the completed tasks.\n");
	}
}

mysql_close();
?>
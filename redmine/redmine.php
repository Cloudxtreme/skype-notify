#!/usr/bin/php
<?php
require_once ('/Users/xxxxxxxxxx/Documents/Timeline/redmine/ActiveResource.php');
date_default_timezone_set("GMT");

class Issue extends ActiveResource {
    var $site = 'http://xxxxxxxxxxxxxxx/';
    var $request_format = 'xml'; // REQUIRED!
}

// find issues
$script_ts = file_get_contents("/Users/xxxxxxxxxxxx/Documents/Timeline/redmine/ts_redmine.txt");
echo $script_ts."\n";
$issue = new Issue (array ('subject' => 'XML REST API', 'project_id' => '2'));
$issues = $issue->find ('all');
for ( $i=0; $i < count($issues); $i++ ){
	if ( $issues[$i]->updated_on > $script_ts ){
		exec("osascript /Users/xxxxxxxxx/Documents/Timeline/redmine/redmine.scpt \"".$issues[$i]->project['name']."\" \"".$issues[$i]->subject."\" \"".$issues[$i]->status['name']."\" \"".$issues[$i]->done_ratio."\"");
	}
}
file_put_contents("/Users/xxxxxxxxxx/Documents/Timeline/redmine/ts_redmine.txt",date("Y-m-d\TH:i:s\Z"));
?>
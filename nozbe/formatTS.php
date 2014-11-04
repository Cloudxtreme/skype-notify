<?php
function formatTS($ts,$amount){
	$ts = str_split($ts);
	for ( $i = 19; $i < 26; $i++ ){ unset($ts[$i]); }
	if ( $ts[0] == "" ){ return "1000-01-01 00:00:00"; }
	if ( !array_key_exists(16,$ts)){
		$ts[16] = ":";
		$ts[17] = "0";
		$ts[18] = "0";
	}
	$ts = implode('',$ts);
	$year = $ts[0].$ts[1].$ts[2].$ts[3];
	$month = $ts[5].$ts[6];
	$day = $ts[8].$ts[9];
	$hour = $ts[11].$ts[12];
	
	$newyear = $year;
	$newmonth = $month;
	$newday = $day;
	$newhour = $hour;
	
	$newhour = $hour+$amount;
	
	if ( $newhour >= 24 ){
		$newhour -= 24;
		
		$newday = $day + 1;
		if ( $newday < 10 ){ $newday = "0".$newday; }
		
		if ( ($month == '01' || $month == '03' || $month == '05' || $month == '07' || $month == '08' || $month == '10' || $month == '12') && $newday >= "32" ){
			$newmonth = $month + 1;
			$newday = "01";
		}
		elseif ( ($month == '04' || $month == '06' || $month == '09' || $month == '11') && $newday >= "31" ){
			$newmonth = $month + 1;
			$newday = "01";
		}
		elseif ( $month == '02' && $newday >= '30' && ( ($year%4 == 0 && $year%100 != 0	 ) || ( $year%100 == 0 && $year%400 == 0 ) ) ) {
			$newmonth = $month + 1;
			$newday = "01";
		}
		elseif ( $month == '02' && $newday >= '29' ) {
			$newmonth = $month + 1;
			$newday = "01";
		}
		
		if ( $newmonth > 12 ){
			$newmonth -= 12;
			$newyear = $year + 1;
		}
		if ( $newmonth < 10 && $newmonth[0] != "0" ){$newmonth = "0".$newmonth;}
	}
	
	if ( $newhour < 10 ){ $newhour = "0".$newhour; }
	$ts = str_replace($year."-".$month."-".$day." ".$hour,$newyear."-".$newmonth."-".$newday." ".$newhour,$ts);
	return $ts;
}
?>
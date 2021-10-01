<?php

 include 'fortune.php';

 global $fort, $quote;
 $fort = new Fortune();
 $quote= @$fort->QuoteFromDir("/usr/share/games/fortunes");

 global $BRIEF_MODE;
 $BRIEF_MODE=false;

 if ( isset($argv[1]) && stripos($argv[1],"brief")===0 ) $BRIEF_MODE = TRUE;
 echo 'BRIEF MODE'.PHP_EOL;

 function aloud( $output, $speed=120 ) {
  echo $output;
  shell_exec('espeak -s '.$speed.' "'.str_replace('"','\\"',$output).'"');
 }

 function array_by_keys( $arr, $keys ) {
  $a=[];
  foreach ( $keys as $k ) $a[]=$arr[$k];
  return $a;
 }

 // For some reason when you trigger this from the web it doesn't work.
 function fortune() {
  global $BRIEF_MODE;
  if ( $BRIEF_MODE ) return "";
//  shell_exec( "bash -c 'fortune' 2>&1 /var/www/html/messaging/for.txt");
//  $res= file_get_contents("/var/www/html/messaging/for.txt");
//  unlink("/var/www/html/messaging/for.txt");
  global $quote;
  $res = $quote;
  if ( strpos($res,"Q:") !== FALSE ) $res.=' , Ha ha ha, ha. ';
  $res=str_replace("#"," number ",$res);
  return str_replace("Q:","Question: ",str_replace("A:","answer: ",$res));
 }

 function simple_temp($t) {
  return str_replace("+","",$t);
 }

 function simple_tim($t) { return $t; }

 function simple_time($t) {
  $parts=explode(":",$t);
  if ( intval($parts[0]) >= 12 ) { $ampm="pm"; $parts[0]=intval($parts[0])-12; }
  else if ( intval($parts[0]) == 0 ) { $parts[0]=12; $ampm="am"; }
  else $ampm="am";
  $minutes = intval($parts[1]);
  if ( $minutes == 0 ) $minutes="";
  else
  if ( $minutes < 10 ) $minutes="oh ".$minutes;
  return intval($parts[0]).' '.$minutes.' '.$ampm;
 }

 function simple_wind($w) {
  $a=$w{0};
  $w=substr($w,1);
  echo PHP_EOL.ord($a).PHP_EOL;
  switch ( ord($a) ) {
   case 231: case 223: $prex="west "; break;
   case 232: case 224: $prex="east "; break;
   case 233: case 225: $prex="north "; break;
   case 234: case 226: $prex="south "; break;
   case 235: case 227: $prex="northwest "; break;
   case 236: case 228: $prex="northeast "; break;
   case 237: case 229: $prex="southwest "; break;
   case 238: case 230: $prex="southeast "; break;
   default: $prex="";
  }
  $strength=intval(str_replace("mph","",$w));
  if ( $strength == 0 ) return "none";
  if ( $strength < 15 ) $strength="calm ";
  else if ( $strength >= 25 && $strength < 40 ) $strength="strong ";
  else if ( $strength >= 40 ) $strength="dangerous ";
  else $strength="";
  return $strength.$prex.str_replace("mph", " miles per hour", $w);
 }

 $output = shell_exec( "curl \"wttr.in?format=%C|%h|%t|%f|%w|%l|%m|%M|%p|%P|%D|%S|%z|%s|%d|%T|%Z|\" -o /var/www/html/messaging/test.txt" );

 $output = file_get_contents("/var/www/html/messaging/test.txt");

 echo $output;

 unlink("/var/www/html/messaging/test.txt");

 $parts = explode("|",$output);

 $lunarity=intval($parts[7]);
 $lunarmo=floatval($parts[7])/27.3;
 $waxwane=$lunarmo > 0.5 ? "waning" : "waxing";
 $percfull=$lunarmo > 0.5 ? intval(100*(1.0-(($lunarmo-0.5)/0.5))) : intval(100*($lunarmo/0.5));
 $percfull.= " percent";

 $time=explode(":",$parts[15]);
 $hour = intval($time[0]);

 $hours=intval(date("h"));
 $ampm=date('a');

 $mins=intval(date('i'));

 if ( $hours < 8 && $ampm == "am" ) $BRIEF_MODE=TRUE;

 if ( $hours < 7 && $mins != 0 ) $BRIEF_MODE=TRUE;

 $premins="";
 if ( $mins == 0 ) $mins=" o'clock on the hour ";
 else if ( $mins < 10 ) $mins="oh $mins";
 else if ( $mins < 15 ) { $premins=$mins.' past'; $mins=0; }
 else if ( $mins == 15 ) { $mins=""; $premins="quarter past"; }
 else if ( $mins == 30 ) { $mins=""; $premins="half past"; }
 else if ( $mins == 45 ) { $mins="";
  $hours++; if ( $hours == 13 ) {$hours=1; $ampm=($ampm=="am"?"pm":"am");} $premins="quarter to";
 }
 else if ( $mins >= 50 ) {
  $hours++; if ( $hours == 13 ) {$hours=1; $ampm=($ampm=="am"?"pm":"am");} $premins=(60-$mins)." minutes to";
 $mins="";
 }

 if ( $mins == 0
   || $mins == 11
   || $mins == 12
   || $mins == 13
   || $mins == 14
   || $mins == 15
   || $mins == 30
   || $mins == 45
   || $mins >=50 ) {
  if ( $hours == 12 && $ampm == "am" ) $hours="midnight";
  else if ( $hours == 1 && $ampm == "pm" ) $hours="noon";
 }

 $realtime=$premins.' '.$hours.' '.$mins.' '.$ampm;

 $greeting="Allo mate. ";
 switch ( intval(date('H')) ) {
  case 0:
  case 1:
  case 2:
  case 3:
  case 4:
  case 5:
  case 6:
  case 7:
  case 8:
  case 9:
  case 10:
  case 11:$greeting="Good morning. "; break;
  case 12:
  case 13:
  case 14:
  case 15:
  case 16: $greeting="Good afternoon. "; break;
  case 17:
  case 18:
  case 19: $greeting="Good evening. "; break;
  case 20:
  case 21:
  case 22:
  case 23: $greeting="Good night. "; break;
  default: break;
 }

 $output =
 $greeting
 .', It is '.$realtime
 .fortune();

 if ( $BRIEF_MODE == false ) {
 $output .=', Weather, report, '
          .'As of '.simple_time($parts[15]).', ';
 }
 $output.=$parts[0].' '
 .(strpos($parts[3],$parts[2])===0
   ?simple_temp($parts[2])
   :str_replace("F","",simple_temp($parts[2])).' feels like '.str_replace("F","",simple_temp($parts[3])))
 ;

 if ( $BRIEF_MODE == false ) {
 $output.=
  ', wind '.simple_wind($parts[4])
 .', humidity '.$parts[1]
 . (intval(str_replace("mm",'',$parts[8]))!=0?', '.str_replace("mm"," millimeters per 3 hours of precipitation ",$parts[8]):' no recent precipitation')
 .', pressure '.str_replace("hPa","",$parts[9]).' hectopascals, '
 .', local dawn '.simple_time($parts[10])
 .', sunrise '.simple_time($parts[11])
 .', solar zenith '.simple_time($parts[12])
 .', sunset '.simple_time($parts[13])
 .', dusk is at '.simple_time($parts[14])
 .', moon is '.$waxwane.' @ '.$percfull.' full'
 ;
 $output.=', at '.$parts[5]
// .' time zone '.$parts[15]
 ;
}

 aloud($output);

 if ( $BRIEF_MODE ) die;

 echo PHP_EOL;

 shell_exec( 'curl -s https://www.nasa.gov/rss/dyn/breaking_news.rss | grep "<title>" > /var/www/html/messaging/nasa.txt');

 $nasa=explode("\n",file_get_contents("/var/www/html/messaging/nasa.txt"));

 unset($nasa[0]);
 var_dump($nasa);

 foreach ( $nasa as &$n ) {
  $p=explode("<title>",$n);
  $n=strip_tags(str_replace("</title>",", ... ",str_replace(["<item>","<title>"],["",""],$n)));
 }

 $keys=array_rand($nasa,3);

 $output="Recent headlines from NASA., ".implode(", ... ",array_by_keys($nasa,$keys));

 unlink("/var/www/html/messaging/nasa.txt");

 aloud($output);


 shell_exec( 'curl -s http://feeds.bbci.co.uk/news/rss.xml | grep "<title>" | '
  .'sed "s/            <title><\!\[CDATA\[//g;s/\]\]><\/title>//;" | grep -v "BBC News" > /var/www/html/messaging/BBC.txt');

 $bbc=explode("\n",file_get_contents("/var/www/html/messaging/BBC.txt"));


 $keys=array_rand($bbc,3);

 $output="Recent headlines from the BBC., ".implode(", ... ",array_by_keys($bbc,$keys));

 aloud($output);

 unlink("/var/www/html/messaging/BBC.txt");

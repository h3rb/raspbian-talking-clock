<html>
<head>
<title>Talking clock</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
crossorigin="anonymous"></script>
</head>
<body>
 <button id="hush">Hush</button>
 <button id="play">Play</button>
 <button id="brief">Brief</button>
<script type="text/javascript">
 function Get(id) { return document.getElementById(id); }
 $(document).ready(function(e){
  $(Get("hush")).click( function(e){ window.location="/?hush=1"; });
  $(Get("play")).click( function(e){ window.location="/?play=1"; });
  $(Get("brief")).click( function(e){ window.location="/?brief=1"; });
 });
</script>
 <div id="state">
<?php

 function getpost() {
  $a=array();
  foreach ( $_GET  as $k=>$v ) $a[$k]=$v;
  foreach ( $_POST as $k=>$v ) $a[$k]=$v;
  return $a;
 }

 $d=getpost();

 if ( isset($d['json']) ) {

  $j=json_decode($d['json']);
 }


 if ( isset($d['hush']) && intval($d['hush'])==1 ) {
  echo 'Hush...';
  file_put_contents('/var/www/html/messaging/kill.txt','stop');
  die;
 }

 if ( isset($d['play']) && intval($d['play'])==1 ) {
  echo 'Playing...';
  file_put_contents('/var/www/html/messaging/now.txt','play');
  die;
 }

 if ( isset($d['brief']) && intval($d['brief'])==1 ) {
  echo 'Playing brief...';
  file_put_contents('/var/www/html/messaging/now.txt','brief');
  die;
 }
?>
 </div>
</body>
</html>

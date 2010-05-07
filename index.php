<?php
/**
* YaCy Search API 
* for PHP5 with cURL
* 
* v0.4
*
* Copyright (c) 2010 yacy.net / dulcedo
* URL: http://yacymin.walamt.de

* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:

* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.

* EXPERIMENTAL OAI-Search

*/ 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
<title>OAI-Search</title> 
<meta http-equiv="content-type" content="text/html;charset=iso-8859-1"> 
<meta name="Content-Language" content="German, Deutsch"> 
<meta name="keywords" content="Suchmaschine search engine open free kostenlos frei opensource"> 
<meta name="description" content="Dezentrale Suche mit YaCy">
<meta name="description" content="Open Archive Search"> 
<meta name="copyright" content="dulcedo/yacy.net"> 
</head> 
<?php

// Include the API PHP Library
require 'YaCyAPI4.php';
#include 'JSON.php';


$s=$_POST['s'];
if (!$s) {$s=$_GET['s'];}
if (!$s) {$s=$_GET['q'];}
if (!$s) {$s=$_GET['query'];}
if (!$s) {$s=$_GET['search'];}

$start=$_POST['start'];
if (!$start) {$start=$_GET['start'];}
if (!$start) {$start=1;}
echo '<div style="margin:0 10px;margin-bottom:0px;-moz-border-radius:5px;-webkit-border-radius:5px;background:#ffffff none;border-radius:5px;border:1px solid #417394;font: normal 13px Tahoma,Calibri,Verdana,Geneva,sans-serif;position:relative;top:0">';
echo '<table border=0><tr>';
echo '<td><img src=images/YaCy_OAIBookSearch.small80.png height=64 width=64</td>';
echo '<td><form action=?action=search method=post>';
echo '&nbsp;&nbsp;&nbsp;Search: <input type=text size=35 name="s" id="s" value="'.$s.'" >';
echo '<input type=hidden name="action" id="action" value="search" >';
echo '</form></td>';
echo '</tr></table>';
echo '</div>'; 

if (!$s) {
 exit; 
}

//-----------------------------------------------------
// start the class 
$search = new YaCyAPI();
#$peer=$this_YaCyPeer[$peerno][0];
#$port=$this_YaCyPeer[$peerno][1];
#$appid=$this_YaCyPeer[$peerno][2];
#$name=$this_YaCyPeer[$peerno][3];
#$peername="http://".$peer.":".$port."/";
#$peername="http://freeworld.walamt.de:8080/";
#$peer="freeworld.walamt.de";
#$port="8080";
#$appid="admin:yacy";

#--- find first responding  beginning from # ---
$res=$search->findFirstPeer('0');
$peer=$res['peer'];
$port=$res['port'];
$appid=$res['appid'];


$res=$search->setProperties($peer.":".$port,$appid,$name);
$info=$search->ping();
#echo "inf:"; print_r($info);
#exit;
if (!$info['host'])     #peer defined?
{
 exit;
}
    $search->setSources('Web');
    $search->setFormat('xml');
    $search->setStartRecord($start);
    $search->setMaximumRecords('10');
$querytime=microtime(true);                       #for measuerement
    $res = $search->search($s);
$querytime=microtime(true)-$querytime;                       #for measuerement


echo "<center>";
echo "<font face=arial size=3>Showing ".$start." to ".($start+9)." from ".$search->totalresults." total results</font><br>";
$nstart=$start+10;
$vstart=$start-10;
if ($vstart<1) {$vstart=1;}

echo "<a href=?s=".$s."&start=".$vstart."><img src=images/navdl.gif></a>&nbsp;&nbsp;";
echo "<a href=?s=".$s."&start=".$nstart."><img src=images/navdr.gif></a>";
echo "</center>";



echo '<div style="margin:0 10px;margin-bottom:0px;-moz-border-radius:5px;-webkit-border-radius:5px;background:#ffffff none;border-radius:5px;border:1px solid #417394;font: normal 13px Tahoma,Calibri,Verdana,Geneva,sans-serif;position:relative;top:0">';
echo '<table border=0 width=100% align=center>';

$lfd=count($res);
for ($i=1;$i<=$lfd;$i++)
{
    echo '<tr>';
    echo '<td width=24 valign=top align=right>';
    #fopen($res[$i]['favicon'],"r");
    #i#f (@fopen($res[$i]['favicon'],"r"))
    #{
        echo "<img src=".$res[$i]['favicon'].">&nbsp;";
    #}
     echo '</td><td>';
    echo "<a href=".$res[$i]['link'].">";
    echo $res[$i]['title']."</a></h2>";
 
      echo "<p>";
      echo $res[$i]['description'];
      echo "<br><i>".$res[$i]['host']."</i>";
      #echo " - ".$res[$i]['size'];
      echo "<br>".substr($res[$i]['date'],0,16);
      echo "</p><hr>";      
      echo '</td></tr>';
}

echo '</table>';

echo "<center>";
echo "<a href=?s=".$s."&start=".$vstart."><img src=images/navdl.gif></a>&nbsp;&nbsp;";
echo "<a href=?s=".$s."&start=".$nstart."><img src=images/navdr.gif></a>";

#echo "<hr>".print_r($search->navigation);

?> 
<div style="height: 80px;
	left: 0;
	position: relative;
	top: 0;
   margin: 0 10px;
background-image: url(hg2pxu-80.gif)">

	<font color=grey size=2 face=arial>
	<br>Dieses PHP-Script durchsucht mit Hilfe des YaCy-API verschiedene Open Archive Quellen. 
    <br>This php-script is using the YaCy-API to search several Open Archive sources.
    <br>2010 by dulcedo / yacy.net - CC BySaNc. - http://github.com/dulcedo/YaCyOAI
    <br><font color=black>[Query <?php echo $peer.":".$port; ?> | in:<?php echo substr($querytime,0,5); ?> sec.]</font>

</div>
</center>

</div>
 </body>
</html>
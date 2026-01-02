<?php
$code=preg_replace("/\D/","",$_GET['code']??"");
$dir="temp/$code";
$out=[];
if(is_dir($dir)){
  foreach(scandir($dir) as $f){
    if($f!="."&&$f!="..") $out[]=$f;
  }
}
echo json_encode($out);

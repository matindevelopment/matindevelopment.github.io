<?php
$code=preg_replace("/\D/","",$_POST['code']??"");
$dir="temp/$code";

function del($d){
  if(!is_dir($d)) return;
  foreach(scandir($d) as $f){
    if($f!="."&&$f!=".."){
      $p="$d/$f";
      is_dir($p)?del($p):unlink($p);
    }
  }
  rmdir($d);
}
del($dir);

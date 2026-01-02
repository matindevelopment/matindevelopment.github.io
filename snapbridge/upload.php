<?php
$code=preg_replace("/\D/","",$_POST['code']??"");
if(strlen($code)!=6) exit;

$dir="temp/$code";
if(!is_dir($dir)) mkdir($dir,0777,true);

$type=mime_content_type($_FILES['img']['tmp_name']);
if(!in_array($type,["image/jpeg","image/png"])) exit;

move_uploaded_file($_FILES['img']['tmp_name'],$dir."/".uniqid().".jpg");

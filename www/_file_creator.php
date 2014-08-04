<?php

function filesize_in_mb($name)
 {
  clearstatcache();
  $size = filesize($name);
  if ($size < 0)
   {
    $size = 4294967295 + $size;
   }
  return floor($size/1048576);
 }

function get_random_line($length)
 {
  $line = '';  
  for ($i=0;$i<$length;$i++)
   {
    if (mt_rand(0, 1)==0)
     {
      $char = chr(mt_rand(65, 90));
     }
      else
     {
      $char = chr(mt_rand(97, 122));
     }
    $line .= $char;
   }  

  return $line;
 }
$argv = array(_file_creator.php,'randomtext.txt', 10);
$output_file = $argv[1];
$output_file_size = $argv[2];

do
 {
  for ($i=0;$i<1000;$i++)
   {
    file_put_contents($output_file, get_random_line(10)."\n", FILE_APPEND);
   }
  $current_size = filesize_in_mb($output_file);
  echo $current_size.' of '.$output_file_size."\n";
 }
while ($current_size<$output_file_size);

?>
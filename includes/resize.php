<?php
 function thumb_vertical($file_name_src, $file_name_dest, $height, $quality=80)
 {
  if (file_exists($file_name_src)  && isset($file_name_dest))
  {
   $est_src = pathinfo(strtolower($file_name_src));
   $est_dest = pathinfo(strtolower($file_name_dest));
   $size = getimgsize($file_name_src);
   $h = number_format($height, 0, ',', '');
   $w = number_format(($size[0]/$size[1])*$height,0,',','');
   // IMPOSTAZIONE STREAM DESTINAZIONE
   if ($est_dest['extension'] == "gif" || $est_dest['extension'] == "jpg")
   {
    $file_name_dest = substr_replace($file_name_dest, 'jpg', -3);
    $dest = imagecreatetruecolor($w, $h);
   }
   elseif ($est_dest['extension'] == "png") { $dest = imagecreatetruecolor($w, $h); }
   else { return FALSE; }
   // IMPOSTAZIONE STREAM SORGENTE
   switch($size[2])
   {
    case 1:       //GIF
     $src = imagecreatefromgif($file_name_src);
    break;
    case 2:       //JPEG
     $src = imagecreatefromjpeg($file_name_src);
    break;
    case 3:       //PNG
     $src = imagecreatefrompng($file_name_src);
    break;
    default:
     return FALSE;
    break;
   }
   imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
   switch($size[2])
   {
    case 1:
    case 2:
     imagejpeg($dest,$file_name_dest, $quality);
    break;
    case 3:
     imagepng($dest,$file_name_dest);
   }
   return TRUE;
  }
  return FALSE;
 }
 function thumb_horiz($file_name_src, $file_name_dest, $weight,$quality=80)
 {
  if (file_exists($file_name_src)  && isset($file_name_dest))
  {
   $est_src = pathinfo(strtolower($file_name_src));
   $est_dest = pathinfo(strtolower($file_name_dest));
   $size = getimgsize($file_name_src);
   $w = number_format($weight, 0, ',', '');
   $h = number_format(($size[1]/$size[0])*$weight,0,',','');
   // IMPOSTAZIONE STREAM DESTINAZIONE
   if ($est_dest['extension'] == "gif" || $est_dest['extension'] == "jpg")
   {
    $file_name_dest = substr_replace($file_name_dest, 'jpg', -3);
    $dest = imagecreatetruecolor($w, $h);
   }
   elseif ($est_dest['extension'] == "png") { $dest = imagecreatetruecolor($w, $h); }
   else { return FALSE; }
   // IMPOSTAZIONE STREAM SORGENTE
   switch($size[2])
   {
    case 1:       //GIF
     $src = imagecreatefromgif($file_name_src);
    break;
    case 2:       //JPEG
     $src = imagecreatefromjpeg($file_name_src);
    break;
    case 3:       //PNG
     $src = imagecreatefrompng($file_name_src);
    break;
    default:
     return FALSE;
    break;
   }
   imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);
   switch($size[2])
   {
    case 1:
    case 2:
     imagejpeg($dest,$file_name_dest, $quality);
    break;
    case 3:
     imagepng($dest,$file_name_dest);
   }
   return TRUE;
  }
  return FALSE;
 }
?> 

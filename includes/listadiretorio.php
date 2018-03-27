<?php
 function filelist ($currentdir, $startdir, $files=array())
 {
  if (is_dir($currentdir)) chdir ($currentdir);
  // remember where we started from
  if (!$startdir) $startdir = $currentdir;
  $d = opendir (".");
  //list the files in the dir
  while ($file = readdir ($d))
  {
   if ($file != ".." && $file != ".")
   {
    if (is_dir ($file))
    {
     // Se $file for um diret�rio vamos dar uma olhada dentro
     $files = filelist (getcwd().'/'.$file, getcwd(), $files);
    }
    else
    {
     // Se $file n�o � um diret�rio ent�o vamos adicionar � lista
     $files[] = $file;
    }
   }
  }
  closedir ($d);
  chdir ($startdir);
  return $files;
 }
?>
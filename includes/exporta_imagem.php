<?php
  $barcodeBase64 =  strip_tags($_POST['barcodeBase64']);
  list($type, $image) = explode(',', $barcodeBase64);
  file_put_contents('barcode.png', base64_decode($image));
  echo 'Imagem salva em disco!';
?>
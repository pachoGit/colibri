<?php

session_start();

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => base_url()."/index.php/alumnos/delete/".$id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "DELETE",
  CURLOPT_HTTPHEADER => array(
      $_SESSION["auth"],
  ),
));

/*
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://colibri.informaticapp.com/index.php/alumnos/show/".$id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
  $_SESSION["auth"]
  ),
));
*/

$response = curl_exec($curl);

curl_close($curl);


?>


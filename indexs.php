<?php
// From URL to get webpage contents.
$url2 = "https://me.cybermeteors.com/blog/markForget";

// Initialize a CURL session.
$ch = curl_init();

// Return Page contents.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//grab URL and pass it to the variable.
curl_setopt($ch, CURLOPT_URL, $url2);

$result = curl_exec($ch);

echo $result;
// From URL to get webpage contents. 
$url = "https://me.cybermeteors.com/blog/markAbsence";
  
// Initialize a CURL session. 
$ch = curl_init();  
  
// Return Page contents. 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  
//grab URL and pass it to the variable. 
curl_setopt($ch, CURLOPT_URL, $url); 
  
$result = curl_exec($ch); 
  
echo $result;  
  
?> 

<?php
include 'cache.php';
class tinyurl
{

public $cache;

  public function __construct()
  {
  $this->cache   = new cache();
  }
  
  public function get($url)  
  {  
    
    $ch = curl_init();  
    $timeout = 5;  
    curl_setopt($ch,CURLOPT_URL,
    'http://tinyurl.com/api-create.php?url='.$url);  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
    $data = curl_exec($ch);
    curl_close($ch);  
    return $data;
  }

  public function __destruct()
  {

  }
}

?> 

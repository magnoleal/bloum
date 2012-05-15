<?php
namespace Bloum;

class Util {
  public static function isRequestAjax() {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strcmp(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']), 'xmlhttprequest') == 0);
  }

  public static function isRequestFlash() {
    return (!empty($_SERVER['HTTP_USER_AGENT']) &&
      (stripos($_SERVER['HTTP_USER_AGENT'], 'shockwave') > 0 || stripos($_SERVER['HTTP_USER_AGENT'], 'flash') > 0));
  }

  public static function captalize($string, $size = 1){
    return strtoupper(substr($string, 0, $size)) . substr($string, $size);
  }
  
}
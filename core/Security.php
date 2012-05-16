<?php
namespace Bloum;

if (!defined('DIR_BLOUM')) exit('No direct script access allowed');

class Security {

  const SEP = '$!';

  static function randomKey($size) {
      
    $chars = "A,b,C,d,e,f,G,H,I,j,l,m,n,X,Z,w,Y,o,p,Q,r,S,t,Y,1,2,3,4,5,6,7,8,9,0";        
    $arrayChars = explode(",", $chars);        
    shuffle($arrayChars);        
    $key = implode($arrayChars, "");
    
    return substr($key, 0, $size);
  }    
      
  static function encode($value) {
    $value = strrev($value);
    
    $chars1 = Security::randomKey(8);
    $chars2 = Security::randomKey(8);
    $chars3 = Security::randomKey(8);
    $chars4 = Security::randomKey(8);

    $encode = base64_encode($value);

    if(strlen($encode) > 4){

      $div = intval(strlen($encode) / 4);

      $ind = 0;

      $key1 = substr($encode, $ind, $div);
      $key2 = substr($encode, $ind+=$div, $div);
      $key3 = substr($encode, $ind+=$div, $div);
      $key4 = substr($encode, $ind+=$div);

      return Security::randomKey(4) . Security::SEP . $key1 . Security::SEP . 
             Security::randomKey(4) . Security::SEP . $key2 . Security::SEP . 
             Security::randomKey(4) . Security::SEP . $key3 . Security::SEP . 
             Security::randomKey(4) . Security::SEP . $key4;

    }
    
    return Security::randomKey(8) . Security::SEP . $encode . Security::SEP . Security::randomKey(8);
  }

  static function decode($value) {
    $encode = explode(Security::SEP, $value);
    
    $size = count($encode);

    switch ($size) {
      case 3:
        return strrev(base64_decode($encode[1]));
      case 8:        
        return strrev(base64_decode($encode[1].$encode[3].$encode[5].$encode[7]));      
      default:
        return '';
    }
      
  }

}
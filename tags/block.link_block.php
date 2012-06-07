<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.translate.php
 * Type:     block
 * Name:     translate
 * Purpose:  translate a block of text
 * -------------------------------------------------------------
 */
function smarty_block_link_block($params, $content, Smarty_Internal_Template $template, &$repeat)
{

  if($repeat){
    return '<a href="#">';
  }else{
    return "$content</a>";
  }
    
}
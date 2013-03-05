<?php

namespace Gen;
if (!defined('DIR_BLOUM')) exit('No direct script access allowed');
require_once 'Base.php';

/**
 * Description of FormGen
 *
 * @author Magno
 */
class Controller extends Base {
  
  
  function __construct($model, $namespace = '', $isCRUD = true) {    
    $this->isCRUD = $isCRUD;
    parent::__construct($model, $namespace);
  }

  public function generate() {
    
    $tpl = "controller.php";    
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->content = str_replace("#NaMe", ucfirst($this->nameModel), $this->content);
    $this->replaceSep();
    
    if ($this->isCRUD) {
      $this->content = str_replace("#isCrudBegin", "", $this->content);
      $this->content = str_replace("#isCrudEnd", "", $this->content);
    }else{
      $posBegin = strpos($this->content, "#isCrudBegin");
      $posEnd = strpos($this->content, "#isCrudEnd")+strlen("#isCrudEnd");
      
      $part1 = substr($this->content, 0, $posBegin);
      $part2 = substr($this->content, $posEnd);
      
      $this->content = $part1.$part2;
    }
    
    $this->saveController();
  }
    
}
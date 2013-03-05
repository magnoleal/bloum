<?php

namespace Gen;
if (!defined('DIR_BLOUM')) exit('No direct script access allowed');
require_once 'Base.php';

/**
 * Description of FormGen
 *
 * @author Magno
 */
class Form extends Base {
  
  private $isCRUD;
  
  function __construct($model, $namespace = '', $isCRUD = true) {
    $this->isCRUD = $isCRUD;
    parent::__construct($model, $namespace);
  }

  public function generate() {
    
    if ($this->isCRUD) {
      $this->genForm();
      $this->genCadastro();
    }
    $this->genCombo();
    
  }
  
  private function genCadastro(){
    
    $tpl = "cadastro.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->replaceSep();
    $this->saveView($tpl);
    
  }
  
  private function genCombo(){
    
    $tpl = "combo.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->replaceSep();
    $this->content = str_replace("#under_name_id", ucfirst($this->camelize($this->nameModel)), $this->content);
    $this->saveView($tpl);
    
  }
  
  private function genForm(){
    
    $tpl = "form.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    
    $fields = $this->model->attributes(); 
    
    $pks = $this->model->primary_key;
    $modelName = $this->nameModel;
    $validationsPresence = isset($modelName::$validates_presence_of) ? $modelName::$validates_presence_of : array();    
    $validations = array();
    
    foreach ($validationsPresence as $key => $value) {
      foreach ($value as $fd) {
        $validations[] = $fd;
      }
    }
    
    $formFields = '';
    
    foreach ($fields as $field => $config) {
      
      if(array_search($field, $pks) !== FALSE)
        $formFields .= $this->genFieldPKForm($field)."\n";
      else
        $formFields .= $this->genFieldForm($field, $validations)."\n";
      
    }
    
    $this->content = str_replace("#fields", $formFields, $this->content);    
    $this->saveView($tpl);
    
  }
  
  
  
  private function genFieldPKForm($field){    
    return '{form_input type="hidden" name="'.$field.'"}';    
  }
  
  private function genFieldForm($field, $validations){
    $req = array_search($field, $validations) !== FALSE ? 'true' : 'false';
    
    $pos = strpos($field, "_id");
    
    if($pos === FALSE)    
      return '{form_input name="'.$field.'" required='.$req.'}';
    
    $relation = substr($field, 0, $pos);
    return '{include file="admin/'.$relation.'/combo.tpl" required='.$req.'}';
    
  }
  
}
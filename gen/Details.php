<?php

namespace Gen;
if (!defined('DIR_BLOUM')) exit('No direct script access allowed');
require_once 'Base.php';

/**
 * Description of FormGen
 *
 * @author Magno
 */
class Details extends Base {
  
  
  function __construct($model, $namespace = '') {
    parent::__construct($model, $namespace);
  }

  public function generate() {
    $this->genInclude();
    $this->genDetalhes();    
  }
  
  private function genDetalhes(){
    
    $tpl = "detalhes.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->replaceSep();
    $this->genRelationFields();
    $this->saveView($tpl);
    
  }
  
  private function genRelationFields(){
    $fields = $this->model->attributes();     
    $pks = $this->model->primary_key;
    
    $relationsFields = '';
    
    foreach ($fields as $field => $config) {
      
      if(array_search($field, $pks) !== FALSE)
        continue;
    
      $pos = strpos($field, "_id");
      if($pos !== FALSE){
        $relation = substr($field, 0, $pos);
        $relationsFields = $this->genFieldRelation($relation)."\n\t";
      }
      
    }
    
    $this->content = str_replace("#fieldsRelation", $relationsFields, $this->content);   
  }
    
  private function genInclude(){
    $tpl = "detalhesInclude.tpl";    
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->replaceSep();
    $fields = $this->model->attributes();     
    $pks = $this->model->primary_key;
    
    $stringFields = '';
    
    foreach ($fields as $field => $config) {
      
      if(array_search($field, $pks) !== FALSE)
        continue;
    
      $pos = strpos($field, "_id");
      if($pos === FALSE){
        $stringFields .= $this->genField($field)."\n\t";
      }
        
    }
    $this->content = str_replace("#fields", $stringFields, $this->content);    
    $this->saveView($tpl);
  }
  
  
  private function genField($field){
    return '<div class="row-fluid"><div class="span2"><strong>'.ucfirst($field).':</strong></div><div class="span8">{$model->'.$field.'}</div></div>';    
  }
   
  private function genFieldRelation($field){
    return '{include file="admin/'.$field.'/detalhesInclude.tpl" model=$model->'.$field.'}';
  }
  
}
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
  
  
  function __construct($model) {
    parent::__construct($model);
  }

  public function generate() {
    $this->genInclude();
    $this->genDetalhes();
  }
  
  private function genDetalhes(){
    
    $tpl = "detalhes.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $this->saveView($tpl);
    
  }
    
  private function genInclude(){
    $tpl = "detalhesInclude.tpl";    
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $fields = $this->model->attributes();     
    $pks = $this->model->primary_key;
    
    $stringFields = '';
    $relationsFields = '';
    
    foreach ($fields as $field => $config) {
      
      if(array_search($field, $pks) !== FALSE)
        continue;
    
      $pos = strpos($field, "_id");
      if($pos === FALSE){
        $stringFields .= $this->genField($field, $pks)."\n\t";
      }else{
        $relation = substr($field, 0, $pos);
        $relationsFields = $this->genFieldRelation($relation)."\n\t";;
      }
        
    }
    
    $this->content = str_replace("#fieldsRelation", $relationsFields, $this->content);    
    $this->content = str_replace("#fields", $stringFields, $this->content);    
    $this->saveView($tpl);
  }
  
  
  private function genField($field, $pks){
    return '<tr><td class="chave-detalhe">'.ucfirst($field).':</td><td>{$model->'.$field.'}</td></tr>';
  }
   
  private function genFieldRelation($field){
    return '{include file="'.$field.'/detalhesInclude.tpl"}';
  }
  
}
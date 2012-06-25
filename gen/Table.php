<?php

namespace Gen;
if (!defined('DIR_BLOUM')) exit('No direct script access allowed');
require_once 'Base.php';

/**
 * Description of FormGen
 *
 * @author Magno
 */
class Table extends Base {
  
  private $isCRUD;
  
  function __construct($model, $isCRUD = true) {
    $this->isCRUD = $isCRUD;
    parent::__construct($model);
  }

  public function generate() {
    
    $this->genTabela();
    $this->genBusca();
    $this->genListar();
    
  }
  
  private function genListar(){
    
    $tpl = "listar.tpl";
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    
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
    
    $this->saveView($tpl);
    
  }
  
  private function genBusca(){
    $this->genFields(true);
  }
  
  private function genTabela(){
    $this->genFields(false);
  }
  
  private function genFields($isSearch){
    $tpl = $isSearch ? "busca.tpl" : "tabela.tpl";    
    $this->content = file_get_contents(DIR_TEMPLATES.$tpl);
    $this->replaceName();
    $fields = $this->model->attributes();     
    
    
    $stringFields = '';
    $headFields = '';
    
    foreach ($fields as $field => $config) {
      if(!$isSearch)
        $headFields .= $this->genFieldHead ($field)."\n\t\t";
      $stringFields .= $this->genField($field, $isSearch)."\n\t\t";
    }
    
    if(!$isSearch){
      $this->content = str_replace("#fieldsHead", $headFields, $this->content);    
      $this->content = str_replace("#count", count($fields)+1, $this->content);    
    }
    
    $this->content = str_replace("#fields", $stringFields, $this->content);    
    $this->saveView($tpl);
  }
  
  private function genFieldHead($field){    
    $pos = strpos($field, "_id");
    
    if($pos !== FALSE)
      $field = substr($field, 0, $pos);
    
    return '<th>'.ucfirst($field).'</th>';
    
  }
  
  private function genField($field, $isSearch){
    
    $pks = $this->model->primary_key;
    if($isSearch && array_search($field, $pks) !== FALSE)
      return '';
    
    $pos = strpos($field, "_id");
    
    if($pos === FALSE)
      return $isSearch ? '{form_input name="'.$field.'" busca=true}' : '<td>{$model->'.$field.'}</td>';
    
    $relation = substr($field, 0, $pos);
    return $isSearch ? '{include file="'.$this->camelize($relation).'/combo.tpl" busca=true}' : '<td>{$model->'.$relation.'->nome}</td>';
    
  }
  
}
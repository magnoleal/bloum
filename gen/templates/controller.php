<?php
/**
 * Description of #NameController
 *
 * @author Magno
 */
class #NameController extends ApplicationController{ 
  #isCrudBegin
  public function cadastro(){
  }
  
  public function salvar($#name){
    
    if($#name->save()){ 
      $this->messages['success'] = ConstantesConfig::MSG_SUCCESS;
      parent::chain('#Name.detalhes?id='.$#name->id);
    }
    else{
      $this->messages['error'] = $#name->errors->to_array();
      parent::back();
    }
      
  }
  
  public function editar($id) {
    $this->output->addValue('model', #Name::find($id));
    parent::show('cadastro');
  }
  
  public function excluir($id) {
    $model = #Name::find($id);
    $model->delete();
    $this->messages['success'] = ConstantesConfig::MSG_SUCCESS;
    parent::chain('#Name.listar');
  }
  #isCrudEnd
  public function listar(){
  }
  
  public function tabela($pg = 0 /*fields*/){
    
    parent::$layout = '';
    
    $where = "1 = 1";
    /*whereTests*/
    
    $conditions = array('conditions' => array($where));    
    $this->output->addValue('list', #Name::paginate($pg, ConstantesConfig::NUM_PAG, $conditions));
    
  }
  
  public function detalhes($id) {
    $this->output->addValue('model', #Name::find($id));
  }
  
  

}
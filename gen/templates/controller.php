<?php
/**
 * Description of #NameController
 *
 * @author Magno
 */
class #NaMeController extends ApplicationController{ 
  static $layout = 'admin';	
  #isCrudBegin
  public function cadastro(){
  }
  
  public function salvar($#name){
    
    if($#name->save()){ 
      $this->messages['success'] = ConstantesConfig::MSG_SUCCESS;
      parent::chain('admin#sep#name#sepdetalhes?id='.$#name->id);
    }
    else{
      $this->messages['error'] = $#name->errors->to_array();
      parent::back();
    }
      
  }
  
  public function editar($id) {
    $this->output->addValue('model', #NaMe::find($id));
    parent::show('cadastro');
  }
  
  public function excluir($id) {
	if(is_array($id)){
      #NaMe::table()->delete( array('id' => $id) );
    }else{
      $model = #NaMe::find($id);
      $model->delete();
    }
    
    $this->messages['success'] = ConstantesConfig::MSG_SUCCESS;
    parent::chain('admin#sep#name#seplistar');  
  }
  
  public function copiar($id){
    if(is_array($id)){
      
      foreach ($id as $pk) {
        $model = #NaMe::find($pk);
        $model->copy();
      }
      
    }else{
      $model = #NaMe::find($id);
      $model->copy();
    }
    
    $this->messages['success'] = ConstantesConfig::MSG_SUCCESS;
    parent::chain('admin#sep#name#seplistar');
  }
  
  #isCrudEnd
  public function listar(){
  }
  
  public function tabela($pg = 0, $order = '' /*fields*/){
    
    $this->setLayout('');
    
    $where = "1 = 1";
    /*whereTests*/
    
    $conditions = array('conditions' => array($where));    
    $this->output->addValue('list', #NaMe::simplePaginate($pg, ConstantesConfig::NUM_PAG, $conditions, $order));
    
  }
  
  public function detalhes($id) {
    $this->output->addValue('model', #NaMe::find($id));
  }
  
  

}
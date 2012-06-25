{block name=title}Cadastro de #Name{/block}

{block name=body}
<div class="mws-panel-header">
    <span class="mws-i-24 i-list">Cadastro de #Name</span>
  </div>
  <div class="mws-panel-body">
    <form id="mws-validate" action="#Name.salvar" class="mws-form" method="post">
    <div id="mws-validate-error" class="mws-form-message error" style="display:none;"></div>
    <div class="mws-form-inline">
      {include file="#name/form.tpl"} 
    </div>
    <div class="mws-button-row">      
      {button style="reset"}
      {button style="save"}    
    </div>    
  </form>
</div>      
{/block}
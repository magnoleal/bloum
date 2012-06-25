{block name=title}Detalhes de #Name{/block}

{block name=body}
<div class="mws-panel-header">
  <span class="mws-i-24 i-list">Detalhes de #Name</span>
</div>
<div class="mws-panel-body">
  <div class="mws-panel-content">
    {include file="#name/detalhesInclude.tpl"}
  </div>
  <div class="mws-button-row">
    {button style="back"}
    {button style="edit" action="#Name.editar?id={$model->id}"}
    {button style="list" action="#Name.listar"}    
  </div>

</div>  

{/block}
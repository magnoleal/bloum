{block name=bcontent}
<div class="row-fluid">
  <div class="span8"><h3>Detalhes da #Name</h3></div>
  <div class="span4">
      {button style="new" action="admin#sep#name#sepcadastro"}
      {button style="edit" action="admin#sep#name#sepeditar?id={$model->id}"}
      {button style="list" action="admin#sep#name#seplistar"} 
  </div>
</div>
<ul class="nav nav-tabs" id="tabs">
  <li class="active"><a href="#geral" data-toggle="tab">Geral</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active" id="geral">
    {include file="admin/#name/detalhesInclude.tpl"}
    #fieldsRelation
  </div>      
</div>
{/block}
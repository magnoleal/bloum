{block name=bjs}
  {ApplicationHelper::datatable('id','asc')}
{/block}
{block name=bcontent}
<div class="row-fluid">
  <div class="span8"><h3>Listagem de #Names</h3></div>
  <div class="span4">
      {button style="new" action="admin#sep#name#sepcadastro"}
      {button style="copy" action="admin#sep#name#sepcopiar"}
      {button style="delete" action="admin#sep#name#sepexcluir"} 
  </div>
</div>
{include file="admin/#name/busca.tpl"} 
<form method="post" action="javascript:void(0);" id="form-table">    
  <div id="div-table"></div>  
</form>
{/block}
{block name=title}Listagem de #Name{/block}

{block name=body}

{ApplicationHelper::datatable()}

<div class="mws-panel-header">
  <span class="mws-i-24 i-table-1">Listagem de #Name</span>
</div>
<div class="mws-panel-body">
  {include file="#name/busca.tpl"} 
  <div id="div-table"></div>  
  <div class="mws-button-row">
    {button style="back"}
    #isCrudBegin
    {button style="new" action="#Name.cadastro"}      
    #isCrudEnd
  </div>
</div>
{/block}
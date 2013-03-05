{block name=bcontent}
<h3>Cadastro de #Names</h3>
<form action="{url_to link='admin#sep#name#sepsalvar'}" method="post" class="form-horizontal">      
  <ul class="nav nav-tabs" id="tabs">
    <li class="active"><a href="#geral" data-toggle="tab">Geral</a></li>
  </ul>  
  <div class="tab-content" style="margin-top:9px">
    <div class="tab-pane active" id="geral">    
    {include file="admin/#name/form.tpl"}     
    </div>      
  </div>
  <span class="obrigatorio"><sup>*</sup>Campo obrigatório</span>
  <div class="form-actions">      
    {button style="reset"}
    {button style="save"}    
  </div> 
</form> 
{/block}
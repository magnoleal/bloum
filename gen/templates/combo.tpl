{if !isset($busca) || !$busca}
<div class="control-group">
  <label for="#name_id" class="control-label">#Name{if isset($required) && $required}<sup>*</sup>{/if}:
    <span class="help-block">{InputHelper::getInfo('#name')}</span>
  </label>
  <div class="controls">       
{/if}

{select_tag model="#Name" key="id" value="nome" required=$required|default:false}    

{if !isset($busca) || !$busca}
  </div>  
</div>
{/if}
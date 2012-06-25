<div class="{if isset($busca) && $busca}mws-form-col-2-8{else}mws-form-row{/if}">
  <label for="#under_name_id" >#Name{if isset($required) && $required}*{/if}:</label>
  <div class="mws-form-item {if !isset($busca) || !$busca}small{/if}">
    {select_tag model="#Name" key="id" value="nome" required=$required|default:false}    
  </div>  
</div>
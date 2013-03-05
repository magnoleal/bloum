<table class="table table-striped" cellspacing="0" id="tb-#name">
  <thead>
    <tr>
      <th id="check"><input type="checkbox" onclick="selectAll('tb-#name');"></th>
      #fieldsHead
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>

    {foreach $list as $model}
      <tr>                        
        <td class="colCheck"><input type="checkbox" name="id[]" value="{$model->id}"></td>
        #fields
        <td>          
          {button style="detail" action="admin#sep#name#sepdetalhes?id={$model->id}" size="mini"}
          {button style="edit" action="admin#sep#name#sepeditar?id={$model->id}" size="mini"}
        </td>    
      </tr>
    {foreachelse}  
      <tr><td colspan="#count">Nenhum Dado Encontrado...</td></tr>
    {/foreach}

  </tbody>
</table>
{simple_paginate total=$total atual=$pg ini=$row_ini fim=$row_fim count=$row_count}
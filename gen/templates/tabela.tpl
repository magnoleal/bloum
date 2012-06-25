<table class="mws-table">
  <thead>
    <tr>
      #fieldsHead
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>

    {foreach $list as $model}
      <tr>                        
        #fields
        <td>
          {button style="detail" action="#Name.detalhes?id={$model->id}" size="small"}
          {button style="edit" action="#Name.editar?id={$model->id}" size="small"}
          {button style="delete" action="#Name.excluir?id={$model->id}" size="small"}
      </tr>
    {foreachelse}  
      <tr><td colspan="#count">Nenhum Dado Encontrado...</td></tr>
    {/foreach}

  </tbody>
</table>
<div class="mws-panel-toolbar bottom clearfix">
  {paginacao total=$total atual=$pg}
</div>
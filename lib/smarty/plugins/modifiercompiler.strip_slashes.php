<?php
function smarty_modifiercompiler_strip_slashes($params, $compiler)
{ 
  return 'stripslashes(' . $params[0] . ')';
}

?>
<?php

define('DIR_BASE', rtrim(__DIR__, '/') . '/../../');
define('DIR_BLOUM', DIR_BASE . 'bloum/');
define('DIR_APP', DIR_BASE . 'app/');
define('DIR_TEMPLATES', 'templates/');

require 'Table.php';
require 'Details.php';
require 'Form.php';

$crud = true;

if(isset($argc) && count($argc) > 0){
  if( isset($argc['crud']) )
    $crud = intval($argc['crud']) == 1;
  if( isset($argc['model']) )
    $model = $argc['model'];
}
elseif(isset($_GET) && count($_GET) > 0){
  if( isset($_GET['crud']) )
    $crud = intval($_GET['crud']) == 1;
  if( isset($_GET['model']) )
    $model = $_GET['model'];
}

if(strlen($model) <= 0)
  die('Model Not Found!');

new \Gen\Table($model, $crud);
new \Gen\Details($model);
new \Gen\Form($model, $crud);
new \Gen\Controller($model, $crud);

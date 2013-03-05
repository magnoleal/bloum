<?php

define('DIR_BASE', rtrim(__DIR__, '/') . '/../../');
define('DIR_BLOUM', DIR_BASE . 'bloum/');
define('DIR_APP', DIR_BASE . 'app/');
define('DIR_TEMPLATES', 'templates/');

require 'Table.php';
require 'Details.php';
require 'Form.php';

$crud = true;
$namespace = '';

if(isset($argc) && count($argc) > 0){
  if( isset($argc['crud']) )
    $crud = intval($argc['crud']) == 1;
  if( isset($argc['model']) )
    $model = $argc['model'];
  if( isset($argc['namespace']) )
    $namespace = $argc['namespace'];
}
elseif(isset($_GET) && count($_GET) > 0){
  if( isset($_GET['crud']) )
    $crud = intval($_GET['crud']) == 1;
  if( isset($_GET['model']) )
    $model = $_GET['model'];
  if( isset($_GET['namespace']) )
    $namespace = $_GET['namespace'];
}

if(strlen($model) <= 0)
  die('Model Not Found!');

new \Gen\Table($model, $namespace, $crud);
new \Gen\Details($model, $namespace);
new \Gen\Form($model, $namespace, $crud);
new \Gen\Controller($model, $namespace, $crud);

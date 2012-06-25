<?php

namespace Gen;

if (!defined('DIR_BLOUM'))
  exit('No direct script access allowed');

include_once DIR_BLOUM . 'core/Main.php';

/**
 * Description of BaseGen
 *
 * @author Magno
 */
abstract class Base {
  
  protected $nameModel;
  protected $model;
  protected $content;
  protected $path = '';

  function __construct($model) {

    if (!file_exists(DIR_APP . 'models/' . $model . ".php"))
      throw new \Exception("Model Class Not Found");

    spl_autoload_register('\Bloum\Main::loader');
    \Bloum\Db::init();

    $model = ucfirst($model);
    $this->nameModel = $model;
    $this->model = new $model();

    $this->generate();
  }

  protected function saveView($name){
    $path = DIR_APP . "views/" . lcfirst($this->nameModel) . "/";
    if (!file_exists($path))
      mkdir($path, 0755, true);
    $path .= $name;
    $this->saveFile($path);
  }
  
  protected function saveController(){
    $path = DIR_APP . "controllers/";
    if (!file_exists($path))
      mkdir($path, 0755, true);
    $path .= ucfirst($this->nameModel) . "Controller.php";
    $this->saveFile($path);
  }


  protected function saveFile($path) {
    
    $fp = fopen($path, "w+");
    fwrite($fp, $this->content);
    fclose($fp);
    
    echo "File <b>$path</b> created<br>";

    #echo "<h4>$name</h4><pre>" .$this->xmlHighlight($this->content). "</pre><hr/>";
  }

  protected function replaceName() {
    $this->content = str_replace("#name", lcfirst($this->nameModel), $this->content);
    $this->content = str_replace("#Name", ucfirst($this->nameModel), $this->content);
  }

  abstract function generate();

  public static function xmlHighlight($s) {
    $s = htmlspecialchars($s);
    $s = preg_replace("#&lt;([/]*?)(.*)([\s]*?)&gt;#sU", "<font color=\"blue\">&lt;\\1\\2\\3&gt;</font>", $s);
    $s = preg_replace("#&lt;([\?])(.*)([\?])&gt;#sU", "<font color=\"#800000\">&lt;\\1\\2\\3&gt;</font>", $s);
    $s = preg_replace("#&lt;([^\s\?/=])(.*)([\[\s/]|&gt;)#iU", "&lt;<font color=\"blue\">\\1\\2</font>\\3", $s);
    $s = preg_replace("#&lt;([/])([^\s]*?)([\s\]]*?)&gt;#iU", "&lt;\\1<font color=\"blue\">\\2</font>\\3&gt;", $s);
    $s = preg_replace("#([^\s]*?)\=(&quot;|')(.*)(&quot;|')#isU", "<font color=\"red\">\\1</font>=<font color=\"purple\">\\2\\3\\4</font>", $s);
    $s = preg_replace("#&lt;(.*)(\[)(.*)(\])&gt;#isU", "&lt;\\1<font color=\"red\">\\2\\3\\4</font>&gt;", $s);
    $s = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $s);

    return nl2br($s);
  }
  
  protected function camelize($name){
    $name = str_replace(array('-', '_'), ' ', $name);
    $name = ucwords($name);
    $name = str_replace(' ', '', $name); 
    return lcfirst($name); 
  }
  
  function underscore($name) {
    $name[0] = strtolower($name[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $name);
  }

}
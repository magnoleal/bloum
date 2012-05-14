<?php
namespace Bloum;

/**
 * Classe Para Gerenciamento de Log<br />
 * 
 * @author Magno Leal <magnoleal89@gmail.com>
 * @version 1.0 - 11 de Maio de 2012
 **/
class Fx_Log
{
    /**
    * Tipos de Log
    */        
    const ERROR = 1;
    const WARN  = 2;
    const INFO  = 3;
    const DEBUG = 4;

    protected $dir;
    protected $nameFile;
    protected $extensionFile;    
    protected $permissionFile = 0777;
    protected $dateFormat;    

    /** variaveis acessorias **/
    private $file;
    private $fileHandle;

    /**
     * Construtor da Classe Log
     * @param $dir String Diretorio onde ficarao guardados todos os logs da aplicacao, <br/>
     *        se nao passado pega valor default em Config
     * @param $nameFile String Nome do arquivo de log, esse sera concatenado com a data atual,<br/>
     *        por exemplo, log-2012-05-11.txt, valor default log.<br/> 
     *        OBS.: NAO PASSAR O NOME DO ARQUIVO COM EXTENSAO
     * @param $extensionFile String Extensao do Arquivo de Log, valor default txt
     * @param $dateFormat String Formato de Data que sera gravada no Log, valor default Y-m-d G:i:s
     * @return void
     * @author Magno Leal <magnoleal89@gmail.com>
     **/    
    function __construct($dir = '', $nameFile = 'log', $extensionFile = 'txt', $dateFormat = 'Y-m-d G:i:s')
    {
        
        $this->dir = strlen($dir) > 0 ? $dir : FX_FNEX.Config::DIR_LOG;
        $this->nameFile = isset($nameFile) ? $nameFile : 'log';
        $this->extensionFile = isset($extensionFile) ? $extensionFile : 'txt';
        $this->dateFormat = isset($dateFormat) ? $dateFormat : 'Y-m-d G:i:s';
        
        //Setando a data atual e a extensao do arquivo
        $this->nameFile .= '-'.date('Y-m-d') . '.' . $this->extensionFile;   
        $this->file = $this->dir . $this->nameFile;

        if(!file_exists($this->dir)){
            if( !mkdir($this->dir, $this->permissionFile, true) )
                throw new Fx_LoggerException("Erro Ao Criar Arquivo de Log!");
        }

        if (file_exists($this->file) && !is_writable($this->file))
            throw new Fx_LoggerException("Arquivo De Log Nao Pode Ser Escrito, Verifique Suas Permissoes!");


        $this->openFile();

            
    }    

    public function __destruct()
    {
        $this->closeFile();
    }
    
    public function getDir() {
        return $this->dir;
    }

    public function setDir($dir) {
        $this->dir = $dir;
    }

    public function getNameFile() {
        return $this->nameFile;
    }

    public function setNameFile($nameFile) {
        $this->nameFile = $nameFile;
    }

    public function getExtensionFile() {
        return $this->extensionFile;
    }

    public function setExtensionFile($extensionFile) {
        $this->extensionFile = $extensionFile;
    }

    public function getPermissionFile() {
        return $this->permissionFile;
    }

    public function setPermissionFile($permissionFile) {
        $this->permissionFile = $permissionFile;
    }

    public function getDateFormat() {
        return $this->dateFormat;
    }

    public function setDateFormat($dateFormat) {
        $this->dateFormat = $dateFormat;
    }
    
    public function getFile() {
        return $this->file;
    }

    public function getFileHandle() {
        return $this->fileHandle;
    }
    
    private function openFile($mode = 'a')
    {
        if ( !isset($this->fileHandle) || $this->fileHandle == false ){
            $this->fileHandle = fopen($this->file, $mode);
            if (!$this->fileHandle) 
                throw new Fx_LoggerException("Erro Ao Abrir O Arquivo de Log!");           
            
        } 
        
    }

    private function closeFile()
    { 
        if ($this->fileHandle){
            if(fclose($this->fileHandle) == false) 
                throw new Fx_LoggerException("Erro Ao Fechar O Arquivo de Log!");

            $this->fileHandle = null;            
        }
    }

    /**
     * Metodo que limpa o arquivo de log
     * @return void
     * @author Magno Leal <magnoleal89@gmail.com>
    **/
    public function clear(){
        $this->closeFile(); //fecha o arquivo
        $this->openFile('w'); //abre no modo w q limpa e coloca o ponteiro no inicio
    }

    
    /**
     * Metodo que escreve no arquivo de log
     * @param $line String que sera escrita no log
     * @param $type Const Tipo de log
     * @return void
     * @author Magno Leal <magnoleal89@gmail.com>
     **/
    protected function write ($line, $type)
    {

        if (Config::LOG_ENABLE) { //soh escreve se o log estivar ativado
            
            $line = $this->getTypeLine($type) . $line . "\n\r";
            rewind($this->fileHandle); //coloca o ponteiro no inicio do arquivo

            if (fwrite($this->fileHandle, $line) === false)
                throw new Fx_LoggerException("Erro Ao Escrever Log!");

        }

    }
   
    /**
    * Metodo que escreve um log de ERRO
    */
    public function error($line)
    {
        $this->write($line, self::ERROR);
    }

    /**
    * Metodo que escreve um log de WARN
    */
    public function warn($line)
    {
        $this->write($line, self::WARN);
    }

    /**
    * Metodo que escreve um log de INFO
    */
    public function info($line)
    {
        $this->write($line, self::INFO);
    }

    /**
    * Metodo que escreve um log de DEBUG
    */
    public function debug($line)
    {
        $this->write($line, self::INFO);
    }

    /**
     * Recupera o log completo do arquivo em questao, do objeto em instancia
     *
     * @return String Conteudo do arquivo de log
     * @author Magno Leal <magnoleal89@gmail.com>
     **/
    public function getLogString ()
    {
        return file_get_contents($this->file);        
    }
    
    /**
     * Recupera o log de um arquivo especifico
     * @param $file_path String caminho para o arquivo
     * @return String Conteudo do arquivo de log
     * @author Magno Leal <magnoleal89@gmail.com>
     */
    public static function getLog($file_path)
    {
        return file_get_contents($file_path);
    }

    /**
     * Metodo que monta o comeco da linha de log
     * @param $type Tipo de log
     * @return String Comeco da linha de log
     * @author Magno Leal <magnoleal89@gmail.com>
     **/
    private function getTypeLine($type)
    {
        $time = date($this->dateFormat);

        switch ($type) {
            case self::ERROR:
                return "$time - ERROR: ";
            case self::WARN:
                return "$time - WARN: ";
            case self::INFO:
                return "$time - INFO: ";
            case self::DEBUG:
                return "$time - DEBUG: ";            
            default:
                return "$time - LOG: ";
        }
    }
    

}
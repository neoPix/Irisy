<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Uploads.                   			*
	/***********************************************************************************/
    set_time_limit(0);//Attention aux boucles infinis, ici c'est sans filet.
    define('TMP', APP.'Tmp'.DS);
    class UploadConfig{
	public $id = 0;
	public $parts = 0;
	public $partsize = 512000;
	public $finalsize = 0;
	public $name = '';
	public $destination = '';
	public $partsPath = array();
    }

    class UploadController extends Controller{
	function __construct(){
	    $this->acl = array(
		'openUpload'=>array('connectedUser'),
		'uploadChunk'=>array('connectedUser'),
		'assemble'=>array('connectedUser'),
		'assembleState'=>array('connectedUser'),
		'setFinalPath'=>array('connectedUser')
	    );
	}
	
	function openUpload(){
	    $config = new UploadConfig();
	    $config->id = $this->getNextId();
	    $config->partsize = $this->getMaxUploadSize();
	    $config->finalsize = doubleval($_POST['filesize']);
	    $config->name = utf8_decode($_POST['filename']);
	    $config->parts = ceil($config->finalsize/$config->partsize);
	
	    file_put_contents($this->getPath($config->id).'/upload.conf', serialize($config));
	    
	    echo json_encode($config);
	    die();
	}
	
	function uploadChunk(){
	    $id = $_POST['uploadid'];
	    $part = intval($_POST['part']);
	    $path = $this->getPath($id);
	    $config = unserialize(file_get_contents($path.'/upload.conf'));
	    $config->partsPath[$part] = $path.'/parts/'.$part.'.part';
	    move_uploaded_file($_FILES['blob']['tmp_name'], $config->partsPath[$part]);
	    file_put_contents($this->getPath($config->id).'/upload.conf', serialize($config));

	    $config->partsPath = array();
	    echo json_encode($config);
	    die();
	}
	
	function setFinalPath(){
	    $id = $_POST['uploadid'];
	    $destination = utf8_decode($_POST['destination']);
	    $path = $this->getPath($id);
	    $config = unserialize(file_get_contents($path.'/upload.conf'));
	    $config->destination = $destination;
	    file_put_contents($this->getPath($config->id).'/upload.conf', serialize($config));
		die();
	}
	
	function assemble(){
	    $id = $_POST['uploadid'];
	    $path = $this->getPath($id);
	    $config = unserialize(file_get_contents($path.'/upload.conf'));

	    $total = count($config->partsPath);
	    foreach($config->partsPath as $k=>$mpath){
		    file_put_contents($path.'/'.$config->name, file_get_contents($mpath), FILE_APPEND);
		    file_put_contents($path.'/assemblageState', ($k/$total)*100);
		    unlink($mpath);
	    }

	    rename($path.DS.$config->name, DATA.$config->destination.DS.$config->name);
	    die();
	}
	
	function assembleState(){
	    $id = $_POST['uploadid'];
	    $path = $this->getPath($id);
	    if(file_exists($path.'/assemblageState'))
		echo file_get_contents($path.'/assemblageState');
	    else
		echo 0;
	    die();
	}
	
	private function getMaxUploadSize()
	{
	    $max_upload = (int)(ini_get('upload_max_filesize'));
	    $max_post = (int)(ini_get('post_max_size'));
	    $memory_limit = (int)(ini_get('memory_limit'));
	    return (min($max_upload, $max_post, $memory_limit) * 1024 * 1024)-10000;
	}
	
	private function getPath($id){
	    if(!file_exists(TMP.$id))
		mkdir(TMP.$id);
	    $path = TMP.$id;
	    if(!is_dir($path.DS.'parts'))
		mkdir($path.DS.'/parts');
	    return $path;
	}
	
	private function getNextId()
	{
	    return uniqid(rand(), true);
	}
	
	private function deleteDirectory($dir) {
	    if (!file_exists($dir)) return true;
	    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
	    foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') continue;
		if (!$this->deleteDirectory($dir . "/" . $item)) {
		    chmod($dir . "/" . $item, 0777);
		    if (!$this->deleteDirectory($dir . "/" . $item)) return false;
		};
	    }
	    return rmdir($dir);
	} 
    }
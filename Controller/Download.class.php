<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Downloads.                   			*
	/***********************************************************************************/
    class DownloadController extends Controller{
	function __construct(){
	    $this->acl = array(
		'getFile'=>array('connectedUser'),
		'download_shared'=>array('all'),
		'download_password'=>array('all'),
		'stream'=>array('all'),
		'streamPath'=>array('connectedUser'),
		'play'=>array('all'),
		'playpassword'=>array('all')
	    );
	}
	
	function getFile(){
	    define('CHUNK_SIZE', 1048576);
	    
	    if(isset($_POST['path'])){
		$p = utf8_decode($_POST['path']);
		$path = DATA.$p;
		$name = basename($path);
		if(is_file($path)){
		    ob_clean();
		    header("Content-Type: application/force-download; name=\"" . $name . "\"", true);
		    header("Content-Transfer-Encoding: binary", true);
		    header("Content-Length: ".filesize($path), true);
		    header("Content-Disposition: attachment; filename=\"" . $name . "\"", true);
		    header("Expires: 0", true);
		    header("Cache-Control: no-cache, must-revalidate", true);
		    header("Pragma: no-cache", true);
		    ob_flush();
		    flush();
		    
		    $this->readfile_chunked($path);

		    die();
		}
	    }
	    throw new Exception(__('Can\'t download this file'));
	}
	
	function download_shared(){
	    if(isset($this->params['uid']))
	    {
		$this->useDAO('Shared');
		$share = $this->Shared->get($this->params['uid']);
		$path = DATA.$share->path;
		$password = '';
		if(isset($this->params['password']))$password=sha1($this->params['password']);
		if($share->password == $password)
		{
		    define('CHUNK_SIZE', 1048576);
		    $name = basename($path);
		    if(is_file($path)){
			ob_clean();
			header("Content-Type: application/force-download; name=\"" . $name . "\"", true);
			header("Content-Transfer-Encoding: binary", true);
			header("Content-Length: ".filesize($path), true);
			header("Content-Disposition: attachment; filename=\"" . $name . "\"", true);
			header("Expires: 0", true);
			header("Cache-Control: no-cache, must-revalidate", true);
			header("Pragma: no-cache", true);
			ob_flush();
			flush();

			$this->readfile_chunked($path);
			
			$share->nbDownload++;
			$this->Shared->update($share);
		    }
		}
		else{
		    $this->redirect(Rooter::url('/').'dp/'.$this->params['uid']);
		}
	    }
	    die();
	}
	
	function download_password(){
	    if(isset($this->params['uid'])){
			$this->useHelper('Html');
			$this->layout = 'password';
			$this->set('uid', $this->params['uid']);
			$this->set('action', Rooter::url('/').'d/');
			
	    }
	    else throw new NotFoundException(__('File does not exists'));
	}
	
	function play(){
		if(isset($this->params['uid']))
	    {
			$this->useDAO('Shared');
			$password = '';
			$share = $this->Shared->get($this->params['uid']);
			if(isset($this->params['password']))$password=sha1($this->params['password']);
			if($share->password == $password){
				$this->useHelper('Html');
				$this->layout = 'player';
				$this->set('uid', $this->params['uid']);
				$this->set('ext', strToLOwer(end(explode('.', $share->path))));
				$this->set('file', basename($share->path));
				$this->set('password', @$this->params['password']);
			}
			else{
				$this->redirect(Rooter::url('/').'playpassword/'.$this->params['uid']);
			}
		}
	}
	
	function playpassword(){
	    if(isset($this->params['uid'])){
			$this->useHelper('Html');
			$this->layout = 'password';
			$this->set('uid', $this->params['uid']);
			$this->set('action', Rooter::url('/').'play/');
	    }
	    else throw new NotFoundException(__('File does not exists'));
	}
	
	function stream(){
		if(isset($this->params['uid']))
	    {
			$this->useDAO('Shared');
			$share = $this->Shared->get($this->params['uid']);
			$path = DATA.$share->path;
		    define('CHUNK_SIZE', 1048576);
		    $name = basename($path);
			$password = '';
			if(isset($this->params['password']))$password=sha1($this->params['password']);
			if($share->password == $password || Session::read('User.user')){
				if(is_file($path)){
					ob_clean();
					header("Accept-Ranges:bytes");
					header("Connection:Keep-Alive");
					$ext = strtolower(end(explode('.',$path)));
					header("Content-Type:".$this->getMime($ext));
					header("Content-Length: ".filesize($path), true);
					ob_flush();
					flush();
					$this->readfile_chunked($path);
				}
			}
		}
	    die();
	}
	
	function streamPath(){
		if(isset($this->params['path']))
	    {
			$p = urldecode(str_replace('@_@', '/', $this->params['path']));
			$path = DATA.$p;
		    define('CHUNK_SIZE', 1048576);
		    $name = basename($path);
			if(is_file($path)){
				ob_clean();
				header("Accept-Ranges:bytes");
				header("Connection:Keep-Alive");
				$ext = strtolower(end(explode('.',$path)));
				header("Content-Type:".$this->getMime($ext));
				header("Content-Length: ".filesize($path), true);
				ob_flush();
				flush();
				$this->readfile_chunked($path);
			}
		}
	    die();
	}
	
	private function getMime($ext){
		switch($ext){
			case 'mp3':
				return 'audio/mpeg';
				break;
			case 'wav':
				return 'x-wav';
				break;
			case 'ogg':
				return 'application/ogg';
				break;
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'm4v':
				return 'video/mp4';
				break;
			case 'webm':
				return 'video/webm';
				break;
			case 'ogv':
				return 'video/ogg';
				break;
			case 'avi':
				return 'video/x-msvideo';
				break;
			case 'wmv':
				return 'video/x-ms-wmv';
				break;
			default:
				return 'application/'.$ext;
		}
	}
	
	
	private function readfile_chunked($filename, $retbytes = TRUE) {
	    $buffer = '';
	    $cnt =0;
	    $handle = fopen($filename, 'rb');
	    if ($handle === false) {
		return false;
	    }
	    while (!feof($handle)) {
		$buffer = fread($handle, CHUNK_SIZE);
		echo $buffer;
		ob_flush();
		flush();
		if ($retbytes) {
		    $cnt += strlen($buffer);
		}
	    }
	    $status = fclose($handle);
	    if ($retbytes && $status) {
		return $cnt;
	    }
	    return $status;
	}
    }
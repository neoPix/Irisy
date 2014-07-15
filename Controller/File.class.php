<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Files.                   			*
	/***********************************************************************************/
	class FileController extends Controller{
		function __construct(){
			$this->acl = array(
				'createDir'=>array('connectedUser'),
				'ls'=>array('connectedUser'),
				'removeDir'=>array('connectedUser'),
				'removeFile'=>array('connectedUser'),
				'downloadP'=>array('connectedUser')
			);
		}
		
		function ls(){
		    if('json' == $this->params['type']){
			$current = isset($_POST['current'])?$_POST['current']:'';
			$current = str_replace('..', '', utf8_decode($current));
			$path = DATA.$current;
			if(!is_dir($path)){
			    $this->set('tag', 'directory.error');
			    $this->set('message', __('Can\'t get the directory content.'));
			}
			else{
			    $content = array();
			    if ($handle = opendir($path)) {
				while (false !== ($entry = readdir($handle))) {
				    if ($entry != "." && $entry != "..") {
					$content[] = array(
							'name'=>utf8_encode($entry),
							'path'=>utf8_encode(($current!='')?rtrim($current, '/').'/':''),
							'type'=>((is_dir($path.DS.$entry))?'DIRECTORY':'FILE'),
							'ext'=>((is_file($path.DS.$entry))?end(explode('.',$entry)):null),
							'readableSize'=>$this->readableSize($path.DS.$entry),
							'size'=>filesize($path.DS.$entry),
							'lastedit'=>date('Y-m-d H:i:s', filemtime($path.DS.$entry)),
							'lastaccessed'=>date('Y-m-d H:i:s', fileatime($path.DS.$entry)),
							'sub'=>((is_dir($path.DS.$entry))?count(glob($path.DS.$entry.DS.'*')):null),
	    					    );
				    }
				}
				closedir($handle);
				$this->set('tag', 'directory.content');
				$this->set('content', $content);
			    }
			    else{
				$this->set('tag', 'directory.error');
				$this->set('message', __('Can\'t get the directory content.'));
			    }
			}
		    }
		}
		
		function createDir(){
		    if('json' == $this->params['type']){
			$current = isset($_POST['current'])?utf8_decode($_POST['current']):null;
			$current = str_replace('..', '', $current);
			$name = isset($_POST['name'])?utf8_decode($_POST['name']):null;
			
			$path = DATA.$current;
			$pathNew = $path.DS.$name;
			
			if($name==null || !is_dir($path) || file_exists($pathNew)){
			    $this->set('tag', 'directory.error');
			    $this->set('message', __('Can\'t create the directory'));
			}
			else{
			    mkdir($pathNew);
			    $this->set('tag', 'directory.created');
			    $this->set('message', __('Directrory successfully created'));
			}
		    }
		}
		
		function removeDir(){
		    if('json' == $this->params['type']){
			$current = isset($_POST['current'])?utf8_decode($_POST['current']):null;
			if($current != null && trim($current) !=''){
				$current = str_replace('..', '', $current);
				$path = DATA.$current;
				if($this->delTree($path)){
					$this->set('tag', 'directory.removed');
					$this->set('message', __('Directory successfully removed'));
				}
				else{
					$this->set('tag', 'directory.error');
					$this->set('message', __('Can\'t remove the directory'));
				}
				}
			}
		}
		
		function removeFile(){
		    if('json' == $this->params['type']){
			$current = isset($_POST['current'])?utf8_decode($_POST['current']):null;
				if($current != null && trim($current) !=''){
			$current = str_replace('..', '', $current);
			$path = DATA.$current;
			if($this->delFile($path)){
			    $this->set('tag', 'directory.removed');
			    $this->set('message', __('File successfully removed'));
			}
			else{
			    $this->set('tag', 'directory.error');
			    $this->set('message', __('Can\'t remove the file'));
			}
		    }
			}
		}
		
		private function readableSize( $path ) {
		    $size = filesize( $path );
		    if( $size === false )
			return false;
		    $index = 0;
		    while( $size > 1024 ) {
			$index ++;
			$size /= 1024;
		    }
		    $units = array( 'o', 'Ko', 'Mo', 'Go', 'To', 'Po', 'Eo', 'Zo', 'Yo' );
		    return round($size, 2) .' '. $units[$index];
		}
		
		private function delTree($dir) {
		    $files = array_diff(scandir($dir), array('.','..'));
		    foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : $this->delFile("$dir/$file");
		    }
		    return rmdir($dir);
	       } 
		   
		private function delFile($file){
			$this->useDAO('Shared');
			$current = substr($file, strlen(DATA)-1, strlen($file));
			$share = $this->Shared->getWithPath($current);
			if($share != null)$this->Shared->remove($share);
			return unlink($file);
		}
	}
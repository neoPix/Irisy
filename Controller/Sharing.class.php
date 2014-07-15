<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) File sharing.                   			*
	/***********************************************************************************/
    class SharingController extends Controller{
	function __construct(){
	    $this->acl = array(
		'getSharingInformations'=>array('connectedUser'),
		'createShared'=>array('connectedUser'),
		'removeShared'=>array('connectedUser'),
		'editShared'=>array('connectedUser')
	    );
	}
	
	function getSharingInformations(){
	    if('json' == $this->params['type']){
		$this->useDAO('Shared');
		$current = @utf8_decode($_POST['current']);
		$share = $this->Shared->getWithPath($current);

		if($share!=null){
		    $this->set('tag', 'sharing.infos');
		    $this->set('infos', $share);
		    $this->set('url', Rooter::url('/', true).'d/'.$share->uid);
			switch(strtolower(end(explode('.', $current)))){
				case 'mp3':
				case 'wav':
				case 'ogg':
				case 'mp4':
				case 'mpg':
				case 'mpeg':
				case 'avi':
				case 'wmv':
				case 'ogv':
				case 'm4v':
				case 'webm':
					$this->set('purl', Rooter::url('/', true).'play/'.$share->uid);
					break;
				default :
					$this->set('purl', false);
			}
		}
		else{
		    $this->set('tag', 'sharing.notShared');
		}
	    }
	}
	
	function createShared(){
	    if('json' == $this->params['type']){
		$this->useDAO('Shared');
		$current = @utf8_decode($_POST['current']);
		$password = @$_POST['password'];
		
		$password = (strlen($password)>0)?sha1($password):'';
		
		$share = new Shared($this->Shared->newId(),$current, $password, 0, date('Y-m-d h:i:s'));
		$this->Shared->add($share);
		$this->set('tag', 'sharing.createdOk');
	    }
	}
	
	function removeShared(){
	    if('json' == $this->params['type']){
		$this->useDAO('Shared');
		$uid = @$_POST['uid'];
		
		$share = $this->Shared->get($uid);
		$this->Shared->remove($share);
		$this->set('tag', 'sharing.removeOk');
	    }
	}
	
	function editShared(){
	    if('json' == $this->params['type']){
		$this->useDAO('Shared');
		$uid = @$_POST['uid'];
		$password = @$_POST['password'];
		
		$share = $this->Shared->get($uid);
		if($password != $share->password){
		    $share->password = (strlen($password)>0)?sha1($password):'';
		    $this->Shared->update($share); 
		}
		$this->set('tag', 'sharing.updateOk');
	    }
	}
    }
<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Shared.                   			*
	/***********************************************************************************/
	class Shared extends Model{
	    public $uid;
	    public $password;
	    public $nbDownload;
	    public $created;
	    public $path;
	    
	    function __construct($u, $pth,$p, $c, $cr){
		$this->uid = $u;
		$this->password = $p;
		$this->path = $pth;
		$this->nbDownload = $c;
		$this->created = $cr;
	    }
	}
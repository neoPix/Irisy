<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Sharing.                   			*
	/***********************************************************************************/
	
	include MODELS.'Shared.class.php';
	
	class SharedDao extends Dao{
	    function __construct(){
		    $this->useConnection = 'default';
		    $this->useTable = 'shared';
	    }

	    function get($uid)
	    {
		$this->useHelper('Sql');
		$request = $this->Sql->createRequest()
			->select(array('uid', 'path', 'password', 'count', 'created'))
			->from($this->useTable)
			->where(array('uid'=>$uid));

		$con = $this->getConnection();
		$con->prepare($request);
		$con->exec(array());

		if($data = $con->read())
			return new Shared($data['uid'], $data['path'],$data['password'],$data['count'],$data['created']);
		return null;
	    }
	    
	    function getWithPath($path)
	    {
		$this->useHelper('Sql');
		$request = $this->Sql->createRequest()
			->select(array('uid', 'path', 'password', 'count', 'created'))
			->from($this->useTable)
			->where(array('path'=>$path));

		$con = $this->getConnection();
		$con->prepare($request);
		$con->exec(array());

		if($data = $con->read())
			return new Shared($data['uid'], $data['path'],$data['password'],$data['count'],$data['created']);
		return null;
	    }
	    
	    function add(Shared $s){
		$con = $this->getConnection();
		$con->prepare('INSERT INTO '.$this->useTable.' VALUES (:uid, :path, :password, :count, :created);');
		$con->exec(array(':uid'=>$s->uid, ':path'=>$s->path, ':count'=>$s->nbDownload, ':password'=>$s->password, 'created'=>$s->created));
		$s = new Shared($con->lastInsertedId(), $s->path, $s->password, $s->nbDownload, $s->created);
	    }
	    
	    function update(Shared $s){
		$con = $this->getConnection();
		$con->prepare('UPDATE '.$this->useTable.' SET password=:password, count=:count WHERE uid = :uid;');
		$con->exec(array(':uid'=>$s->uid, ':password'=>$s->password, ':count'=>$s->nbDownload));
	    }
	    
	    function remove(Shared $s){
		$con = $this->getConnection();
		$con->prepare('DELETE FROM '.$this->useTable.' WHERE uid = :uid;');
		$con->exec(array(':uid'=>$s->uid));
		$s = null;
	    }
	    
	    function newId(){
		return uniqid(rand(), true);
	    }
	}
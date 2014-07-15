<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Session.                   				*
	/***********************************************************************************/
    class Session{
	public static function init(){
		$sessionname = Configure::read('Session.name');
		$sessiontimeout = Configure::read('Session.timeout');
		if($sessionname != null)session_name($sessionname);
		if($sessiontimeout != null)session_cache_expire($sessiontimeout);
	    session_start();
	}
	
	public static function read($k){
		$ks = explode('.', $k);
		$elm = &$_SESSION['session'];
		for($i=0;$i<count($ks);$i++){
			if(isset($elm[$ks[$i]]))
				$elm = &$elm[$ks[$i]];
			else
				return null;
		}
		return $elm;
	}
	
	public static function write($key, $value){
		$ks = explode('.',$key);
		$elm = &$_SESSION['session'];
		for($i=0;$i<count($ks);$i++)
		{
			if(!isset($elm[$ks[$i]]))
				$elm[$ks[$i]] = array();
			$elm = &$elm[$ks[$i]];
		}
		$elm = $value;
	}
	
	public static function remove($k){
		$ks = explode('.', $k);
		$elm = '$_SESSION[\'session\']';
		for($i=0;$i<count($ks);$i++){
		    $elm.='[\''.$ks[$i].'\']';
		}
		eval('unset('.$elm.');');
	}
    }
    
    Session::init();
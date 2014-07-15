<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Configure.                   			*
	/***********************************************************************************/
	class Configure{
		private static $_config;
		
		/**
	     * Lis une clé de configuration
	     * @example: Configure::read('lang');
	    **/
		static function read($k)
		{
			$ks = explode('.', $k);
			$elm = &self::$_config;
			for($i=0;$i<count($ks);$i++){
				if(isset($elm[$ks[$i]]))
					$elm = &$elm[$ks[$i]];
				else
					return null;
			}
			return $elm;
		}
		
		/**
		 * Ecris une clé de configuration
		 * @example: Configure::write('lang', 'en');
		**/
		static function write($k, $v)
		{
			$ks = explode('.',$k);
			$elm = &self::$_config;
			for($i=0;$i<count($ks);$i++)
			{
				if(!isset($elm[$ks[$i]]))
					$elm[$ks[$i]] = array();
				$elm = &$elm[$ks[$i]];
			}
			$elm = $v;
		}
	}
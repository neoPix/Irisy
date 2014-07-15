<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Cache.                   				*
	/***********************************************************************************/
	class Cache{
		private static function clearFileName($filename){
			return strtr(strToLower(str_replace('\\', '_', str_replace('/', '\\', str_replace('.', '_',str_replace(' ', '_',trim($filename)))))), '���������������������������', 'aaaaaaceeeeiiiinooooouuuuyy');
		}
		
		/**
		 * Lis une valeur dans le cache, false si aucune valeur
		**/
		static function read($key, $cache='default')
		{
			$cachename = $cache;
			$cache = Configure::read('Cache.'.$cache);
			if($cache)
			{
				$path = CACHE.$cachename.'_'.((isset($cache['prefix']))?$cache['prefix']:'').self::clearFileName($key);
				if(file_exists($path))
				{
					$f = fopen($path, 'r');
					$date=(int)fgets($f);
					$serialized='';
					while($data=fgets($f))$serialized.=(string)$data;
					fclose($f);
					if((isset($cache['duration']) && $date>=time()) || !isset($cache['duration']))
					{
						return unserialize($serialized);
					}
				}
			}
			return null;
		}
		
		/**
		 * Supprime un valeur du cache
		**/
		static function remove($key, $cache='default')
		{
			$cachename = $cache;
			$cache = Configure::read('Cache.'.$cache);
			if($cache)
			{
				$path = CACHE.$cachename.'_'.((isset($cache['prefix']))?$cache['prefix']:'').self::clearFileName($key);
				if(file_exists($path))
					return unlink($path);
			}
			return false;
		}
		
		/**
		 * Ecrit une valeur dans le cache
		**/
		static function write($key, $value, $cache='default')
		{
			$cachename = $cache;
			$cache = Configure::read('Cache.'.$cache);
			if($cache!=null)
			{
				$path = CACHE.$cachename.'_'.((isset($cache['prefix']))?$cache['prefix']:'').self::clearFileName($key);
				$f = fopen($path, 'w+');
				fwrite($f, time()+((isset($cache['duration']))?$cache['duration']:0)."\n");
				fwrite($f, serialize($value));
				fclose($f);
			}
		}
		
		/**
		 * Supprime toutes les valeurs d'un type de cache
		**/
		static function clear($cache=null)
		{
			$path = CACHE.(($cache!=null)?$cache.'_*':'*');
			foreach (glob($path) as $filename)
				unlink($filename);
		}
	}
<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Translator.                   			*
	/***********************************************************************************/
	class Translator{
		public $lang = '';
		private $_strings = array();
		private static $_translated=array();
		
		private function __construct($lang)
		{
			if(file_exists(I18N.$lang.DS.'i18n.po'))
			{
				$f = fopen(I18N.$lang.DS.'i18n.po', 'r');
				while(!feof($f))
				{
					$dat=trim(fgets($f));
					if(strpos($dat, 'msgid') === 0)
					{
						$key = substr($dat, 7, strlen($dat)-8);
						$nline = trim(fgets($f));
						$val = substr($nline, 8, strlen($nline)-9);
						$this->_strings[str_replace('\"', '"', $key)]=str_replace('\"', '"', $val);
					}
				}
				fclose($f);
			}
		}
		
		/**
		 * Singleton, récupère un Translator pour une langue donnée
		**/
		static function getTranslator($lang)
		{
			if(!isset(self::$_translated[$lang]))
				self::$_translated[$lang] = new Translator($lang);
			return self::$_translated[$lang];
		}
		
		/**
		 * Recupère la valeur de langue pour une clé donnée
		**/
		function get($key)
		{
			if(isset($this->_strings[$key]))
				return $this->_strings[$key];
			return $key;
		}
	}
	
	/**
	 * Renvois une chaine traduite, prend la langue dans Configure::read('lang');
	 * @example : echo __('{number} results', array('number'=>25));
	 * @example : echo __('Home');
	**/
	function __($key, $params=array())
	{
		$translated = Translator::getTranslator(Configure::read('lang'));
		$str = $translated->get($key);
		foreach($params as $k=>$v)
			if(!is_array($v))$str = str_replace('{'.$k.'}', $v, $str);
			else $str = str_replace('{'.$k.'}', print_r($v, true), $str);
		return $str;
	}
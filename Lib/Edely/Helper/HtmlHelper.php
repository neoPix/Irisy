<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) HtmlHelper.                   			*
	/***********************************************************************************/
	class HtmlHelper{
		/**
		 * Génére un lien
		**/
		public function link($text, $url, $params=array())
		{
			if(is_array($url))$url=Rooter::url($url);
			return $this->markup('a', $params+array('href'=>$url), $text);
		}
		/**
		 * Génére une image
		**/
		public function image($src, $params=array())
		{
			if(file_exists(IMG.$src))$src=HOST_IMG.$src;
			return $this->markup('img', $params+array('src'=>$src));
		}
		/**
		 * Génére un script
		**/
		public function script($src)
		{
			if(file_exists(JS.$src))$src=HOST_JS.$src;
			return $this->markup('script', array('type'=>'text/javascript', 'src'=>$src), '');
		}
		/**
		 * Génére un lien css
		**/
		public function style($src, $device='all')
		{
			if(file_exists(CSS.$src))$src=HOST_CSS.$src;
			return $this->markup('link', array('type'=>'text/css', 'media'=>$device,'rel'=>'stylesheet', 'href'=>$src));
		}
		/**
		 * Génére une meta
		**/
		public function meta($name, $content)
		{
			return $this->markup('meta', array('name'=>$name, 'content'=>$content));
		}
		/**
		 * Génére un httpequiv
		**/
		public function httpequiv($name, $content)
		{
			return $this->markup('meta', array('http-equiv'=>$name, 'content'=>$content));
		}
		/**
		 * Génére un markup
		**/
		public function markup($type, $params=array(), $content=null, $escape=false)
		{
			if($escape)$content=htmlentities($content);
			$markup='<'.$type;
			foreach($params as $name=>$value){
				if(!is_integer($name))
				{
					if(!is_array($value))
						$markup.=(' '.$name.'="'.$value.'"');
					else{
						$markup.=(' '.$name.'="');
						foreach($value as $subvalue)$markup.=($subvalue.' ');
						$markup=trim($markup).'"';
					}
				}
				else $markup.=$value;
			}
			if($content===null) return $markup.'/>';
			return $markup.'>'.$content.'</'.$type.'>';
		}
	}
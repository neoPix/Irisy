<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Renderer.                     			*
	/***********************************************************************************/
	class Renderer extends base{
		private $_params;
		public $view;
		
		public function __construct($params)
		{
			$this->_params = $params;
		}
		
		/**
		 * Lis la valeur d'un paramètre passé à la vue
		**/
		public function get($k)
		{
			if(isset($this->_params[$k])) return $this->_params[$k];
			return NULL;
		}
		
		/**
		 * Rendu de la vue
		**/
		public function render($mview = NULL)
		{
			$res='';
			if($mview != NULL)
				$this->view = $mview;
			$this->view.='.ctp';
			ob_start();
			include $this->view;
			$res = ob_get_contents();
			ob_end_clean();
			return $res;
		}
		
		/**
		 * Ajoute un element à la vue
		**/
		public function element($element)
		{
			include ELEMENTS.$element.'.ctp';
		}
	}
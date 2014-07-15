<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Controller.                     			*
	/***********************************************************************************/
	include dirname(__FILE__).DS.'Renderer.class.php';

	DEFINE('RENDER_NORMAL', 0);
	DEFINE('RENDER_JSON', 1);
	DEFINE('RENDER_XML', 2);

	/**
	 * Classe Controller
	 * @role: Gestion des actions, intéraction avec les models, gestion des vues
	 * @creator: Balan David
	 * @updated: 01/06/2013
	**/
	class Controller extends base{
		private $_passedVars = array();
		private $_daos=array();
		protected $acl=array();

		public $layout="default";
		public $view="default";
		public $type = RENDER_NORMAL;
		public $name = 'controller';
		public $params = array();
		public $cacheView=null;
		public $viewInCache=false;
		public $action=null;
		
		/**
		 * Définis les variables passés à la vue.
		 * @example: $this->set('list', $list); 
		**/
		public function set($k, $v)
		{
			$this->_passedVars[$k]=$v;
		}
		
		/**
		 * Lis une variable passée à la vue.
		 * @example: $this->get('list');
		**/
		public function get($k)
		{
			if(isset($this->_passedVars[$k])) return $this->_passedVars[$k];
			return NULL;
		}
		
		/**
		 * Lis un helper ou une dao
		**/
		public function __get($key){
			if(isset($this->helpers[$key])){
				return $this->helpers[$key];
			}
			if(isset($this->_daos[$key]))
				return $this->_daos[$key];
			return null;
		}
		
		public function checkAccess(){
			$access = false;
			if(isset($this->acl[$this->action])){
				if(Session::read('User.groups')) 
				foreach(Session::read('User.groups') as $group){
					if(in_array($group, $this->acl[$this->action])){
						$access = true;
					}
				}
				if(!$access){
					$access = in_array('all', $this->acl[$this->action]);
				}
			}
			if(!$access){throw new NotAllowedException(__('You don\'t have the rigths to access this page.'));}
		}
		
		/**
		 * Redirige le navigateur vers une autre page
		 * @example: $this->redirect(Router::url('/', true));
		**/
		public function redirect($url, $permanent = null)
		{
			if($permanent===true)header('Status: 301 Moved Permanently', true, 301);
			elseif($permanent===false) header('Status: 301 Moved Temporary', true, 302);
			header('Location: '.$url, true); 
			die();
		}
		
		/**
		 * Charge un model et l'initialise
		 * @example: $this->loadModel('category');
		**/
		public function useDAO($modelName)
		{
			if(!class_exists($modelName.'Dao')){
				$path = DAOS.$modelName.'.class.php';
				if(file_exists($path)){
					include $path;
				}
				else
					throw new ServerErrorException(__('Unable to load the dao {model}', array('model'=>$modelName)));
			}
			if(!isset($this->_daos[$modelName])){
				if(class_exists($modelName.'Dao'))eval('$this->_daos[\''.$modelName.'\']=new '.$modelName.'Dao();');
				else throw new ServerErrorException(__('Unable to load the dao {model}', array('model'=>$modelName)));
			}
		}
		
		/**
		 * Methode en charge du rendu de la vue et de son intégration dans le layout
		 * @example: $this->render('custom/myview');
		**/
		public function render($mview = NULL)
		{
			if($mview != NULL)
				$this->view=$mview;
			$result = '';
			switch($this->type)
			{
				case RENDER_XML:
				case RENDER_NORMAL:
				{
					if($this->type==RENDER_XML){
						$this->view.='.xml';
						$this->layout.='.xml';
					}
					
					//render view
					$cache = null;$cachekey=(string)('render_view_'.get_class($this).'_'.$this->view.'_'.Configure::read('lang').'_'.$this->type);
					if(isset($this->cacheView[$this->action]))$cache=Cache::read($cachekey, $this->cacheView[$this->action]);
					if($cache)$result=$cache;
					else{
						$renderer = new Renderer($this->_passedVars);
						$renderer->view = VIEWS.$this->view;
						$renderer->helpers=$this->helpers;
						$result = $renderer->render();
						if(isset($this->cacheView[$this->action]))Cache::write($cachekey, $result, $this->cacheView[$this->action]);
					}
					//render layout
					$vars = array('content_for_layout'=> $result, 'title_for_layout'=>$this->get('title_for_layout'));
					$renderer = new Renderer($vars);
					$renderer->view = LAYOUTS.$this->layout;
					$renderer->helpers=$this->helpers;
					$result = $renderer->render();
				}
				break;
				case RENDER_JSON:
				{
					$result = json_encode($this->_passedVars);
				}
			}
			print $result;
			die();
		}
	}
<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Rooter.                   				*
	/***********************************************************************************/
	class Rooter{
		private static $_routes = array();
		/**
		 * Lis l'url et appelle le controlleur et l'action voulue
		**/
		static function process()
		{
			$ctrl=null;
			$act=null;
			$type=null;
			
			$path = isset($_REQUEST['p'])?'/'.ltrim($_REQUEST['p'], '/'):'/';
			$params = array();
			
			try{
				foreach(self::$_routes as $k=>$v){
					if(preg_match('/^'.str_replace('/','\\/', $k).'$/', $path, $matches)){
						$params = $v+$matches;
						break;
					}
				}
				
				self::execute($params);
			}
			catch(ServerErrorException $se){
				$params=array('controller'=>'Error', 'action'=>'serverError', 'message'=>$se->getMessage());
				self::execute($params);
			}
			catch(NotFoundException $nfe){
				$params=array('controller'=>'Error', 'action'=>'notFound', 'message'=>$nfe->getMessage());
				self::execute($params);
			}
			catch(NotAllowedException $na){
				$params=array('controller'=>'Error', 'action'=>'notAllowed', 'message'=>$na->getMessage());
				self::execute($params);
			}
			catch(Exception $e){
				$params=array('controller'=>'Error', 'action'=>'all', 'message'=>$e->getMessage());
				self::execute($params);
			}
		}
		
		private static function execute($params)
		{
			if(trim($params['controller'])=='' || trim($params['action'])=='')
				throw new NotFoundException(__('Dont have a controller or an action to play.'));
			$ctrl = $params['controller'];
			$act = $params['action'];
			$type=(isset($params['type']))?$params['type']:'';
			
			self::loadController($ctrl);
			$controller = null;
			if (class_exists($ctrl.'Controller'))
			{
				eval('$controller = new '.$ctrl.'Controller();');
				$controller->view = $ctrl.DS.$act;
				$controller->params = $params;
				$controller->action = $act;
				$controller->checkAccess();
				switch($type)
				{
					case 'xml':
						$controller->type = RENDER_XML;
						break;
					case 'json':
						$controller->type = RENDER_JSON;
						break;
				}
				self::setHeaders($controller->type);
				if(method_exists($controller,$act))
				{
					$cache = null;
					$cachekey=(string)('render_view_'.get_class($controller).'_'.$controller->view.'_'.Configure::read('lang').'_'.$controller->type);
					if(isset($controller->cacheView[$act]))$cache=Cache::read($cachekey, $controller->cacheView[$act]);
					if($cache)$controller->viewInCache = true;
					eval('$controller->'.$act.'();');
					$controller->render();
				}
				else
				{
					throw new ServerErrorException(__('The action "{action}" does not exists in the controller "{controller}".', array('action'=>$act, 'controller'=>$ctrl)));
				}
			}
			else
			{
				throw new ServerErrorException(__('The controller "{controller}" does not exists.', array('controller'=>$ctrl)));
			}
		}
		
		private static function loadController($ctrl)
		{
			$path = CONTROLLERS.$ctrl.'.class.php';
			if(file_exists($path))
				include $path;
			else
				throw new ServerErrorException(__('Can\'t load the controller {controller} on {path}', array('controller'=>$ctrl, 'path'=>$path)));
		}
		
		private static function setHeaders($renderType = RENDER_DEFAULT)
		{
			switch($renderType)
			{
				case RENDER_JSON:
					header('Cache-Control: no-cache, must-revalidate');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
					header('Content-Type: application/json; charset=utf-8');
					break;
				case RENDER_XML:
					header('Cache-Control: no-cache, must-revalidate');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
					header('Content-Type: application/xml; charset=utf-8');
					break;
				default:
					header('Content-Type: text/html; charset=utf-8');
			}
		}
		
		/**
		 * Ajoute une route
		**/
		public static function add($path, $params = array())
		{
			self::$_routes[$path] = $params;
		}
		
		private static function rewriteEngineOn()
		{
			if(!function_exists('apache_get_modules'))return true;
			foreach(apache_get_modules() as $module)
				if($module=='mod_rewrite') return true;
			return false;
		}
		
		/**
		 * Génère une URL depuis la table de routage
		 * @param $base=false: si true renvoi l'adresse complète du serveur
		**/
		public static function url($params=array(), $base=false)
		{
			$ret = HOST_APP;
			if(!self::rewriteEngineOn())$ret.='?p=';
			if(!is_array($params)){
				$ret.=$params;
			}
			else{
				if(count($params)>0){
					foreach(self::$_routes as $k=>$v){
						$diff = array_diff_assoc($v,$params);
						if(count($diff) === 0){
							$ret.=$k;
							$diff = array_diff_assoc($params, $v);
							foreach($diff as $k=>$v)
							    $ret = str_replace('(?P<'.$k.'>.*)', $v, $ret);
							$ret=str_replace('\\.', '.', $ret);
							break;
						}
					}
				}
			}
			$ret = str_replace('//', '/', $ret);
			if($base)$ret=((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$ret;
			return $ret;
		}
	}
<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) base.                   					*
	/***********************************************************************************/
	class base{
		public $helpers=array();

		public function useHelper($name){
			if(!class_exists($name.'Helper')){
				if(file_exists(HELPER.$name.'Helper.php'))
					include HELPER.$name.'Helper.php';
				else throw new ServerErrorException(__('The Helper {name} does not exists.', array('name'=>$name)));
			}
			if(!isset($this->helpers[$name])){
				if(class_exists($name.'Helper'))eval('$this->helpers[$name]=new '.$name.'Helper();');
				else throw new ServerErrorException(__('The Helper {name} does not exists.', array('name'=>$name)));
			}
		}

		public function __get($key){
			if(isset($this->helpers[$key])){
				return $this->helpers[$key];
			}
			return null;
		}
	}
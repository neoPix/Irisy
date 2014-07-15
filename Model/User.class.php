<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Users.                   			*
	/***********************************************************************************/
	class User extends Model{
		
		private $_id=null;
		private $_name=null;
		private $_password=null;
		private $_email=null;
		
		/**
		 * Génère le hash d'un mot de passe
		 * @param String $password
		 * @return String
		 */
		public static function password($password){
		    return sha1($password);
		}
		
		/**
		 * Constructeur d'un user
		 * @param int $id
		 * @param String $name
		 * @param String $password
		 * @param String $email
		 */
		public function __construct($id=null, $name=null, $password=null, $email=null){
			$this->_id=intval($id);
			$this->_name=substr($name,0, 32);
			$this->_password=substr($password, 0, 40);
			$this->_email=substr($email, 0, 320);
		}
		
		public function __get($key){
		    switch($key){
			case 'id':
			    return $this->_id;
			case 'name':
			    return $this->_name;
			case 'password':
			    return $this->_password;
			case 'email':
			    return $this->_email;
			default:
			    throw new Exception(__('The parametter {key} does not exists.', array('key'=>$key)));
		    }
		}
		
		public function __set($key, $value){
		    switch($key){
			case 'name':
			    $this->_name=substr($value,0, 32);
			case 'password':
			    $this->_password=substr($value,0, 40);
			case 'email':
			    $this->_email=substr($value,0, 320);
			default:
			    throw new Exception(__('The parametter {key} does not exists.', array('key'=>$key)));
		    }
		}
	}
<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) SqlConnection.                   		*
	/***********************************************************************************/
	class SqlConnection{
		private static $_connections;
	
		private $_con=null;
		private $_stm=null;
		public $name = '';
		
		private function __construct($connection='default')
		{
			$this->name = $connection;
		}
		
		function __destruct()
		{
			unset(SqlConnection::$_connections[$this->name]);
		}
		
		private function open()
		{
			try{
				$conf = Configure::read('database.'.$this->name);
				$this->_con = new PDO('mysql:host='.$conf['server'].';dbname='.$conf['base'], $conf['user'], $conf['password']);
			}
			catch(Exception $e){
				throw new ServerErrorException(__('Unable to connect to SQL server : {message}', array('message'=>$e->getMessage())));
			}
		}
		
		/**
		 * Prepare une requête.
		 * @example: $mysql->prepare('SELECT * FROM table WHERE id=:id');
		**/
		public function prepare($request)
		{
			if($this->_con == null)
				$this->open();
			$this->_stm = $this->_con->prepare($request);
		}
		
		/**
		 * Execute une requête préparée et remplace les paramètres par ceux passés
		 * @example: $mysql->exec(array(':id'=>2));
		**/
		public function exec($params=array())
		{
			try{
				$this->_res = $this->_stm->execute($params);
				if($this->_res===false){
					$err = $this->_stm->errorInfo();
					throw new Exception($err[2]);
				}
			}
			catch(Exception $e){
				throw new ServerErrorException(__('Unable to execute the request : {message}', array('message'=>$e->getMessage())));
			}
		}
		
		/**
		 * Lis la ligne de résultat suivante
		 * @example: while($line = $mysql->read())print_r($line);
		**/
		public function read()
		{
			return $this->_stm->fetch(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Récupère le dernier id inséré
		 * @example: $id = $mysql->lastInsertedId();
		**/
		public function lastInsertedId()
		{	
			$id=null;
			try{
				$this->_con->lastInsertId(); 
			}
			catch(Exception $e){
				throw new ServerErrorException(__('Unable to get the last inserted id : {message}', array('message'=>$e->getMessage())));
			}
			return $id;
		}
		
		/**
		 * Démare une transaction SQL
		**/
		public function beginTransaction()
		{
			if($this->_con == null)
				$this->open();
			$this->_con->beginTransaction();
		}
		
		/**
		 * Annule et ferme une transaction SQL
		**/
		public function rollback()
		{
			if($this->_con->inTransaction())
				$this->_conn->rollBack();
		}
		
		/**
	     * Enregistre et ferme une transaction SQL
	    **/
		public function commit()
		{
			if($this->_con->inTransaction())
				$this->_conn->commit();
		}
		
		/**
		 * Singleton, récupère la connexion SQL
		**/
		static public function getConnection($connection='default')
		{
			if(!isset(self::$_connections[$connection]))
				self::$_connections[$connection] = new SqlConnection($connection);
			return self::$_connections[$connection];
		}
	}
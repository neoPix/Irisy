<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Dao.		                     			*
	/***********************************************************************************/
	class Dao extends base{
		protected $useConnection=true;
		protected $useDatabase=null;
		protected $useTable='default';
		
		protected function getConnection()
		{
			return SqlConnection::getConnection($this->useConnection);
		}
		
		/**
		 * Recupère tous les éléments de la table concernée par la vue
		**/
		public function find($fields='all')
		{
			if(!is_array($fields) && $fields=='all')$fields='*';
			//construction de la requête
			$this->useHelper('Sql');
			$request = $this->sql->createRequest()
				->select($fields)
				->from($this->useTable);

			//Exécution de la réquête et lecture du flux de résultat
			$con = $this->getConnection();
			$con->prepare($request);
			$con->exec(array());
			$res=array();
			while($res[] = $con->read());
			unset($res[count($res)-1]);
			return $res;
		}
	}
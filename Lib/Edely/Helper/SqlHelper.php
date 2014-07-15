<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) SqlHelper.                   			*
	/***********************************************************************************/
	class SqlHelper{
		/**
		 * Créer une nouvelle requête SQL
		**/
		function createRequest()
		{
			return new sqlSelect();
		}

		static function clean($str)
		{
			return str_replace('\'', '\'\'', str_replace('/*%*/', '', str_replace('--%', '', $str)));
		}
	}

	class sqlSelect{
		function select($params, $distinct=false){
			$str='SELECT '.(($distinct)?'DISTINCT ':'');
			if(!is_array($params))$str.=sqlHelper::clean($params);
			else{
				foreach($params as $param)$str.=(sqlHelper::clean($param).',');
				$str=rtrim($str, ',');
			}
			return new sqlFrom($str);
		}
	}

	class sqlFrom{
		private $_select;
		function __construct($select){
			$this->_select = $select;
		}

		function from($table){
			$this->_select.=(' FROM '.sqlHelper::clean($table));
			return new sqlJoin($this->_select);
		}
	}

	class sqlJoin{
		private $_from;
		function __construct($from){
			$this->_from = $from;
		}

		function inner($table, $on){
			$this->_from.=' INNER JOIN '.sqlHelper::clean($table).' ON '.sqlHelper::clean($on);
			return $this;
		}

		function left($table, $on){
			$this->_from.=' LEFT JOIN '.sqlHelper::clean($table).' ON '.sqlHelper::clean($on);
			return $this;
		}

		function right($table, $on){
			$this->_from.=' RIGHT JOIN '.sqlHelper::clean($table).' ON '.sqlHelper::clean($on);
			return $this;
		}

		function where($params){
			return new sqlAndWhere($this->_from.' WHERE', $params);
		}

		function group($groups){
			return new sqlGroup($this, $groups);
		}

		function limit($nb, $from){
			return new sqlLimit($this->_from, $nb, $from);
		}

		function order($orders){
			return new sqlOrder($this, $orders);
		}

		function __tostring(){
			return $this->_from;
		}
	}

	class sqlAndWhere{
		private $_join;
		private $_where;
		function __construct($join, $params){
			$this->_join = $join;
			$this->_where = '';
			$this->parse($params);
		}
		
		function parse($params)
		{
			if(!is_array($params))$this->_where = $params;
			else{
				foreach($params as $k=>$v){
					if(is_array($v)){
						switch($k){
							case 'LIKE':
								$this->like($v);
							break;
							case 'LIKE%':
								$this->like($v, false, true);
							break;
							case '%LIKE':
								$this->like($v, true);
							break;
							case '%LIKE%':
								$this->like($v, true, true);
							break;
							case 'IN':
								$this->in($v);
							break;
							case 'OR':
								$this->por($v);
							break;
							default:
								$this->parse($v);
						}
					}
					else $this->_where .= ('AND '.sqlHelper::clean($k).' = "'.sqlHelper::clean($v).'" ');
				}
			}
		}
		
		private function like($compare, $begin=false, $end=false){
			foreach($compare as $k=>$v)
				$this->_where .= ('AND '.sqlHelper::clean($k).' LIKE "'.(($begin)?'%':'').sqlHelper::clean($v).(($end)?'%':'').'" ');
		}
		
		private function in($field, $params){
			if(!is_array($params))$this->_where .= ('AND '.sqlHelper::clean($field).' IN ('.$params.')');
			else{
				$this->_where .= ('AND '.sqlHelper::clean($field).' IN (');
				foreach($params as $param)$this->_where.=('"'.sqlHelper::clean($param).'",');
				$this->_where=rtrim($this->_where, ',').')';
			}
		}
		
		private function por($or)
		{
			$this->_where .= 'OR (';
			$this->parse($or);
			$this->_where .= ')';
		}

		function group($groups){
			return new sqlGroup($this, $groups);
		}

		function order($orders){
			return new sqlOrder($this, $orders);
		}

		function limit($nb, $from){
			return new sqlLimit($this, $nb, $from);
		}

		function __tostring(){
			return $this->_join.' '.ltrim(ltrim($this->_where, 'AND '), 'OR ');
		}
	}

	class sqlGroup{
		private $_source;
		function __construct($source, $groups){
			$this->_source = $source.' GROUP BY ';
			foreach($groups as $group)$this->_source.=(sqlHelper::clean($group).',');
			$this->_source=rtrim($this->_source, ',');
		}
		function order($orders){
			return new sqlOrder($this, $orders);
		}

		function limit($nb, $from){
			return new sqlLimit($this, $nb, $from);
		}

		function having($havings){
			return new sqlHaving($this, $havings);
		}

		function __tostring(){
			return $this->_source;
		}
	}

	class sqlHaving{
		private $_source;
		function __construct($source, $havings){
			$this->_source = $source.' HAVING ';
			foreach($havings as $having)$this->_source.=(sqlHelper::clean($having).' AND ');
			$this->_source=rtrim($this->_source, 'AND ');
		}
		function order($orders){
			return new sqlOrder($this, $orders);
		}

		function limit($nb, $from){
			return new sqlLimit($this, $nb, $from);
		}

		function __tostring(){
			return $this->_source;
		}
	}

	class sqlOrder{
		private $_source;
		function __construct($source, $orders){
			$this->_source = $source.' ORDER BY ';
			foreach($orders as $order)$this->_source.=(sqlHelper::clean($order).',');
			$this->_source=rtrim($this->_source, ',');
		}

		function limit($nb, $from){
			return new sqlLimit($this, $nb, $from);
		}

		function __tostring(){
			return $this->_source;
		}
	}

	class sqlLimit{
		private $_source;
		function __construct($source, $nb, $from){
			$this->_source = $source.' LIMIT '.intval($nb).','.intval($from);
		}

		function __tostring(){
			return $this->_source;
		}
	}
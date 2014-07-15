<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Users.                   				*
	/***********************************************************************************/
	include MODELS.'User.class.php';
	
	class UserDao extends Dao{
		function __construct(){
			$this->useConnection = 'default';
			$this->useTable = 'users';
		}
		
		function get($id)
		{
			$this->useHelper('Sql');
			$request = $this->Sql->createRequest()
				->select(array('id', 'name', 'email', 'password'))
				->from($this->useTable)
				->where(array('id'=>$id));
			
			$con = $this->getConnection();
			$con->prepare($request);
			$con->exec(array());
			
			if($data = $con->read())
				return new User($data['id'], $data['name'],$data['password'],$data['email']);
			return null;
		}
		
		function getEmail($email)
		{
			$this->useHelper('Sql');
			$request = $this->Sql->createRequest()
				->select(array('id', 'name', 'email', 'password'))
				->from($this->useTable)
				->where(array('email'=>$email));
			
			$con = $this->getConnection();
			$con->prepare($request);
			$con->exec(array());
			
			if($data = $con->read())
				return new User($data['id'], $data['name'],$data['password'],$data['email']);
			return null;
		}
		
		function add(User $user){
			$con = $this->getConnection();
			$con->prepare('INSERT INTO '.$this->useTable.' VALUES (NULL, :name, :password, :email);');
			$con->exec(array(':name'=>$user->name, ':password'=>$user->password, ':email'=>$user->email));
			$user = new User($con->lastInsertedId(), $user->name, $user->password, $user->email);
		}
		
		function update(User $user){
			$con = $this->getConnection();
			$con->prepare('UPDATE '.$this->useTable.' SET name=:name, password=:password, email=:email WHERE id=:id;');
			$con->exec(array(':id'=>$user->id,':name'=>$user->name, ':password'=>$user->password, ':email'=>$user->email));
		}
		
		function delete(User $user){
			$con = $this->getConnection();
			$con->prepare('DELETE FROM '.$this->useTable.' WHERE id=:id;');
			$con->exec(array(':id'=>$user->id));
		}
	}
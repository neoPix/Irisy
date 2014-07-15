<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Users.                   			*
	/***********************************************************************************/
	class UserController extends Controller{
		function __construct(){
			$this->acl = array(
				'login'=>array('all'),
				'logout'=>array('connectedUser')
			);
		}
		function login()
		{
			$this->layout= 'login';
			$this->useHelper('Html');
			$user = Session::read('User.user');
			if($user!=null){
			    $this->redirect(Rooter::url('/').'f');
			}
			else{
			    if(isset($_POST['email'], $_POST['password']))
			    {
				$this->useDao('User');
				$user = $this->User->getEmail($_POST['email']);
				if($user!=null){
				    if($user->password == User::Password($_POST['password'])){
					Session::write('User.user.name', $user->name);
					Session::write('User.user.id', $user->id);
					Session::write('User.groups', array('connectedUser'));
					$this->redirect(Rooter::url('/').'f');
				    }
				}
			    }
			}
		}
		
		function logout(){
		    Session::remove('User');
		    $this->redirect(Rooter::url('/'));
		}
	}
	
<?php
	/************************************************************************************
	 * Balan David on Irisy. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Main page.                   			*
	/***********************************************************************************/
	class MainController extends Controller{
		function __construct(){
			$this->acl = array(
				'index'=>array('connectedUser')
			);
		}
		
		function index(){
		    $this->useHelper('Html');
		    
		}
	}
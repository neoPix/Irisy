<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) test.                   					*
	/***********************************************************************************/
	include LIB.'Exception'.DS.'ServerErrorException.class.php';
	include LIB.'Exception'.DS.'NotFoundException.class.php';
	include LIB.'Exception'.DS.'NotAllowedException.class.php';
	include LIB.'base.class.php';
	
	include TOOLS.'Translator.class.php';
	include TOOLS.'Configure.class.php';
	include TOOLS.'Rooter.class.php';
	include CONFIGS.'all.config.php';
	include TOOLS.'Session.class.php';
	include TOOLS.'Cache.class.php';
	include TOOLS.'SqlConnection.class.php';
	
	include dirname(__FILE__).DS.'Model'.DS.'Model.class.php';
	include dirname(__FILE__).DS.'Dao'.DS.'Dao.class.php';
	include dirname(__FILE__).DS.'Controller'.DS.'Controller.class.php';
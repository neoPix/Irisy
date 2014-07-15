<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Test lib.                   				*
	/***********************************************************************************/
	DEFINE('DS', DIRECTORY_SEPARATOR);
	DEFINE('APP', dirname(dirname(dirname(__FILE__))).DS);
	DEFINE('LIB', APP.'Lib/Edely'.DS);
	DEFINE('VIEWS', APP.'View'.DS);
	DEFINE('MODELS', APP.'Model'.DS);
	DEFINE('DAOS', APP.'Dao'.DS);
	DEFINE('TOOLS', LIB.'Tool'.DS);
	DEFINE('LAYOUTS', VIEWS.'Layout'.DS);
	DEFINE('CONTROLLERS', APP.'Controller'.DS);
	DEFINE('CONFIGS', APP.'Config'.DS);
	DEFINE('I18N', APP.'Locale'.DS);
	DEFINE('LOG', APP.'Log'.DS);
	DEFINE('CACHE', APP.'Cache'.DS);
	DEFINE('TESTS', APP.'Test'.DS);
	DEFINE('HELPER', LIB.'Helper'.DS);
	DEFINE('WWWDATA', APP.'wwwdata'.DS);
	DEFINE('DATA', WWWDATA.'Data'.DS);
	DEFINE('IMG', WWWDATA.'Img'.DS);
	DEFINE('JS', WWWDATA.'Js'.DS);
	DEFINE('CSS', WWWDATA.'Css'.DS);
	
	include LIB.'lib.php';
	include LIB.'Test/TestExecutor.class.php';
	include TESTS.'test.config.php';
	
	$sendmail = Configure::read('test.sendEmailReport')!=null;
	$strmail = '';
	
	echo '- '.date('Y-m-d h:i:s').' begining of the tests'."\n";
	$dateTest = date('Ymd-his',time());
	$testDirs = array('Controller', 'Models', 'Dao', 'Tool');
	foreach($testDirs as $testDir){
	    echo '- '.date('Y-m-d h:i:s').' Beginig tests on '.$testDir."\n";
	    $test = new TestExecutor();
	    $test->executeAllPath(TESTS.$testDir.DS);
	    if(!file_exists(LOG.'Test'.DS.$dateTest))
		mkdir(LOG.'Test'.DS.$dateTest);
	    file_put_contents(LOG.'Test'.DS.$dateTest.DS.$testDir.'.log', $test->getRepport());
	    if($sendmail){
		$strmail.='---------------------'.$testDir.'---------------------'."\n";
		$strmail.=$test->getRepport();
	    }
	    echo '- '.date('Y-m-d h:i:s').' Ending tests on '.$testDir."\n";
	}
	echo '- '.date('Y-m-d h:i:s').' end of the tests'."\n";
	
	if($sendmail){
	    $mail = Configure::read('test.sendEmailReport');
	    mail($mail['email'], $mail['subject'], $strmail);
	}
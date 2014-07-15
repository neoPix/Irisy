<?php
	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}
		return (substr($haystack, -$length) === $needle);
	}

	//Définition
	DEFINE('DS', DIRECTORY_SEPARATOR);
	DEFINE('APP', dirname(dirname(__FILE__)).DS);
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
	DEFINE('HELPER', LIB.'Helper'.DS);
	DEFINE('WWWDATA', APP.'wwwdata'.DS);
	DEFINE('DATA', APP.'PrivateData'.DS);
	DEFINE('IMG', WWWDATA.'Img'.DS);
	DEFINE('JS', WWWDATA.'Js'.DS);
	DEFINE('CSS', WWWDATA.'Css'.DS);
	
	$apphost=str_replace('/',DS,$_SERVER['REQUEST_URI']);
	while(strpos(APP, $apphost)===false)
		$apphost = dirname($apphost);
	$apphost = str_replace(DS, '/', $apphost);
	if(!endsWith($apphost, '/'))$apphost.='/';
	
	DEFINE('HOST_APP', $apphost);
	DEFINE('HOST_DATA', HOST_APP.'Data/');
	DEFINE('HOST_IMG', HOST_APP.'Img/');
	DEFINE('HOST_JS', HOST_APP.'Js/');
	DEFINE('HOST_CSS', HOST_APP.'Css/');
	
	include LIB.'lib.php';

	function logErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
	{
		$fh = fopen(LOG.'error.log', 'a+');
		$error = __('{date}, An error {errornum} Append on {errfile} at line {line}. {text}. Context : {Context}', 
				array(
					'date'=>date('y-m-d h:i:s',time()),
					'errornum'=>$errno,
					'errfile'=>$errfile,
					'line'=>$errline,
					'Context'=>$errcontext,
					'text'=>$errstr
				)
			);
		fwrite($fh, $error);
		fclose($fh);
	}
	
	error_reporting(Configure::read('error'));
	if(Configure::read('error') == 0)set_error_handler('logErrorHandler');
	
	Rooter::process();
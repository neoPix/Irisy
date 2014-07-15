<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) create pot files.                   		*
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
     
    function glob_recursive($pattern, $flags = 0)
    {
	$files = glob($pattern, $flags);
	    foreach (glob(dirname($pattern).DS.'*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
	    {
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
	    }
	return $files;
    }

    $files = glob_recursive(APP.'*.php');
    $files = array_merge($files, glob_recursive(APP.'*.ctp'));

    $res=array();
    foreach($files as $file){
	    $content = file_get_contents($file);
	    preg_match_all('/__\(["\'](.*)[\'"](, array\(.*\))?\)/', $content, $matches);
	    foreach($matches[1] as $matche)
		$res[str_replace('"', '\"', str_replace('\\\'', '\'',trim($matche)))]=$file;
    }

    $f=fopen(I18N.'default.pot', 'w+');
    foreach($res as $k=>$v){
	fwrite($f, '#:'.$v);
	fwrite($f, "\n");
	fwrite($f, 'msgid "'.$k.'"');
	fwrite($f, "\n");
	fwrite($f, 'msgstr ""');
	fwrite($f, "\n");
	fwrite($f, "\n");
	fwrite($f, "\n");
    }
    fclose($f);
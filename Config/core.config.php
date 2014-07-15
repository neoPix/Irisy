<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Core config file.                   	*
	/***********************************************************************************/
	Configure::write('debug', true);
	Configure::write('error', 0);
	Configure::write('lang', 'fre');
	
	Configure::write('Session.name', 'Irisy');
	Configure::write('Session.timeout', 60); // une heure
	
	date_default_timezone_set('Europe/Paris');
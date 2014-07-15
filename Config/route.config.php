<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Gestion des routes.                   	*
	/***********************************************************************************/
	Rooter::add('/dp/(?P<uid>.*)', array('controller'=>'Download', 'action'=>'download_password'));
	Rooter::add('/d/(?P<uid>.*)/(?P<password>.*)', array('controller'=>'Download', 'action'=>'download_shared'));
	Rooter::add('/d/(?P<uid>.*)', array('controller'=>'Download', 'action'=>'download_shared'));
	Rooter::add('/stream/(?P<uid>.*)/(?P<password>.*)', array('controller'=>'Download', 'action'=>'stream'));
	Rooter::add('/streamp/(?P<path>.*)', array('controller'=>'Download', 'action'=>'streamPath'));
	Rooter::add('/stream/(?P<uid>.*)', array('controller'=>'Download', 'action'=>'stream'));
	Rooter::add('/play/(?P<uid>.*)/(?P<password>.*)', array('controller'=>'Download', 'action'=>'play'));
	Rooter::add('/play/(?P<uid>.*)', array('controller'=>'Download', 'action'=>'play'));
	Rooter::add('/playpassword/(?P<uid>.*)', array('controller'=>'Download', 'action'=>'playpassword'));
	Rooter::add('/(?P<controller>.*)/(?P<action>.*)\.(?P<type>.*)', array());
	Rooter::add('/(?P<controller>.*)/(?P<action>.*)', array());
	Rooter::add('/f', array('controller'=>'Main', 'action'=>'index'));
	Rooter::add('/', array('controller'=>'User', 'action'=>'login'));
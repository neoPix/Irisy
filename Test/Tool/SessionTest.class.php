<?php
    class SessionTest extends test{
	function sessionWriteReadTest(){
	    $a = array('avion'=>'a380');
	    Session::write('avion', $a);
	    $b = Session::read('avion');
	    $this->assertArrayEquals($a, $b);
	}
	
	function sessionRemoveTest(){
	    $a = array('avion'=>'a380');
	    Session::write('avion', $a);
	    Session::remove('avion');
	    $a = Session::read('avion');
	    $this->assertNull($a);
	}
    }
<?php
    class ConfigureTest extends Test{
	
	function testWriteRead(){
	    $array = array('test'=>20, 'test2'=>3);
	    Configure::write('testValue', $array);
	    $this->assertArrayEquals($array, Configure::read('testValue'));
	}
	
	function testWriteReadPart(){
	    $array = array('test'=>20, 'test2'=>3);
	    Configure::write('testValue', $array);
	    $this->assertEquals(3, Configure::read('testValue.test2'));
	}
	
	function testReadNull(){
	    $array = array('test'=>20, 'test2'=>3);
	    Configure::write('testValue', $array);
	    $this->assertNull(Configure::read('testValue.test3'));
	}
    }
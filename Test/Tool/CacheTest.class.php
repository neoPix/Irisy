<?php
    class CacheTest extends test{
	
	function testCacheWriteRead(){
	    $arr = array(1,2,3,4,5,6,7,8,9);
	    Cache::write('key', $arr);
	    $read = Cache::read('key');
	    $this->assertNotNull($read);
	    $this->assertArrayEquals($arr, $read);
	    Cache::remove('key');
	}
	
	function testCacheWriteRemove(){
	     Cache::write('key', 'test');
	     $this->assertNotNull(Cache::read('key'));
	     Cache::remove('key');
	     $this->assertNull(Cache::read('key'));
	}
	
	function testCacheTime()
	{
	    Configure::write('Cache.testSec', array('duration'=>1, 'prefix'=>'testSec_'));
	    Cache::write('key', 'test', 'testSec');
	    $this->assertNotNull(Cache::read('key', 'testSec'));
	    sleep(2);
	    $this->assertNull(Cache::read('key', 'testSec'));
	    Cache::remove('key', 'testSec');
	}
	
    }
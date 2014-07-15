<?php
    class TestLibTest extends test{
	function testAssertEquals(){
	    $this->assertEquals(10,10);
	}
	
	function testAssertNotEquals(){
	    $this->assertNotEquals(10, '10');
	}
	
	function testAssertLessThan(){
	   $this->assertLessThan(0, 10);
	}
	
	function testAssertLessOrEquals(){
	   $this->assertLessOrEqual(0, 10);
	   $this->assertLessOrEqual(0, 0);
	}
	
	function testAssertGretterThan(){
	   $this->assertGretterThan(10, 0);
	}
	
	function testAssertGretterOrEquals(){
	   $this->assertGretterOrEqual(10, 0);
	   $this->assertGretterOrEqual(0, 0);
	}
	
	function testAssertArrayEquals(){
	    $this->assertArrayEquals(array(1,2,3), array(1,2,3));
	    $this->assertArrayEquals(array('a'=>1,'b'=>2,'c'=>3), array('a'=>1,'b'=>2,'c'=>3));
	}
	
	function testAssertArrayNotEquals(){
	    $this->assertArrayNotEquals(array(1,2,3), array(4,5,6));
	    $this->assertArrayNotEquals(array('a'=>1,'b'=>2,'c'=>3), array('a'=>4,'b'=>5,'c'=>6));
	    $this->assertArrayNotEquals(array('a'=>1,'b'=>2,'c'=>3), array('d'=>1,'e'=>2,'f'=>3));
	}
	
	function testAssertNull(){
	    $this->assertNull(null);
	}
	
	function testAssertNotNull(){
	    $this->assertNotNull('a');
	    $this->assertNotNull(0);
	    $this->assertNotNull(false);
	}
	
	function testAssertFalse(){
	    $this->assertFalse(false);
	    $this->assertFalse(0==1);
	}
	
	function testAssertTrue(){
	    $this->assertTrue(true);
	    $this->assertTrue(1==1);
	}
	
	function testAssertArrayHasKey(){
	    $this->assertArrayHasKey(array('A'=>'test'), 'A');
	    $this->assertArrayHasKey(array('test'), 0);
	}
	
	function testAssertNotArrayHasKey(){
	    $this->assertNotArrayHasKey(array('A'=>'test'), 'B');
	    $this->assertNotArrayHasKey(array('test'), 1);
	}
	
	function testAssertContains(){
	    $this->assertContains(array('A'=>'test'), 'test');
	    $this->assertContains(array('test'), 'test');
	}
	
	function testAssertNotContains(){
	    $this->assertNotContains(array('A'=>'test'), 'test2');
	    $this->assertNotContains(array('test'), 'test2');
	}
	
	function testAssertCount(){
	    $this->assertCount(array(0,1,2,3,4,5,6,7,8,9), 10);
	}
    }

<?php
    class TestController extends test{
	function test1()
	{
	    $this->assertEquals(true, true);
	    $this->assertArrayEquals(array('test'=>1), array('test'=>1));
	}
    }
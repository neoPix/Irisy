<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) Test.                   	*
	/***********************************************************************************/
	class Test{
		protected function assertEquals($a, $b){
			if($a!==$b)throw new testException('assertEquals, "'.$a.'" different of "'.$b.'"');
		}

		protected function assertNotEquals($a, $b){
			if($a===$b)throw new testException('assertNotEquals, "'.$a.'" equals to "'.$b.'"');
		}
		
		protected function assertLessThan($a, $b){
		    if($a>=$b)throw new testException('assertLessThan, "'.$a.'" is gretter or equal to "'.$b.'"');
		}
		
		protected function assertGretterThan($a, $b){
		    if($a<=$b)throw new testException('assertGretterThan, "'.$a.'" is lower or equal to "'.$b.'"');
		}
		
		protected function assertGretterOrEqual($a, $b){
		    if($a<$b)throw new testException('assertGretterOrEqual, "'.$a.'" is lower than "'.$b.'"');
		}
		
		protected function assertLessOrEqual($a, $b){
		    if($a>$b)throw new testException('assertLessOrEqual, "'.$a.'" is gretter than "'.$b.'"');
		}

		protected function assertArrayEquals($a, $b){
			if(count(array_diff_assoc($a, $b))!==0 && count(array_diff_assoc($b, $a))!==0)throw new testException('assertArrayEquals, "'.print_r($a, true).'" different of "'.print_r($b, true).'"');
		}

		protected function assertArrayNotEquals($a, $b){
			if(count(array_diff_assoc($a, $b))===0 && count(array_diff_assoc($b, $a))===0)throw new testException('assertArrayNotEquals, "'.print_r($a, true).'"" equals to "'.print_r($b, true).'"');
		}
		
		protected function assertNull($a){
			if($a!==null)throw new testException('assertNull, "'.$a.'" is not null');
		}
		
		protected function assertNotNull($a){
			if($a===null)throw new testException('assertNotNull, "'.$a.'" is null');
		}
		
		protected function assertFalse($a){
			if($a!==false)throw new testException('assertFalse, "'.$a.'" is not false');
		}
		
		protected function assertTrue($a){
			if($a!==true)throw new testException('assertTrue, "'.$a.'" is not true');
		}
		
		protected function assertArrayHasKey($a, $key){
		    if(!isset($a[$key]))throw new testException('assertArrayHasKey, the key "'.$key.'" does not exists in "'.print_r($a, true).'"');
		}
		
		protected function assertNotArrayHasKey($a, $key){
		    if(isset($a[$key]))throw new testException('assertNotArrayHasKey, the key "'.$key.'" does exists in "'.print_r($a, true).'"');
		}
		
		protected function assertContains($a, $value){
		    if(!in_array($value, $a))throw new testException('assertContains, the value "'.$value.'" is not in "'.print_r($a, true).'"');
		}
		
		protected function assertNotContains($a, $value){
		    if(in_array($value, $a))throw new testException('assertNotContains, the value "'.$value.'" is in "'.print_r($a, true).'"');
		}
		
		protected function assertCount($a, $value){
		    if(count($a)!=$value)throw new testException('assertCount, the number of element in "'.print_r($a, true).'" is different than "'.$value.'"');
		}
	}
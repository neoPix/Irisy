<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) TestExecutor.		                   	*
	/***********************************************************************************/
	include dirname(__FILE__).'/TestException.class.php';
	include dirname(__FILE__).'/Test.class.php';
	class TestExecutor{
		private $_result=array();
		function execute($class,$function){
			try{
				$i=null;
				eval('$i=new '.$class.'();');
				if($i==null)throw new Exception('Can\'t create an instance of '.$class);
				if(!method_exists($i, $function))throw new Exception('Method '.$function.' does not exists for class '.$class);
				eval('$i->'.$function.'();');
				unset($i);
			}
			catch(TestException $e){
				$this->_result[$class.'::'.$function] = array('passed'=>false, 'exception'=>$e, true);
				return;
			}
			catch(Exception $e){
				$this->_result[$class.'::'.$function] = array('passed'=>false, 'exception'=>$e, true);
				return;
			}
			$this->_result[$class.'::'.$function] = array('passed'=>true);
		}

		function executeAll($class){
			$methods = get_class_methods($class);
			foreach($methods as $method)
				$this->execute($class, $method);
		}

		function executeAllPath($path){
			foreach (glob($path.'*.class.php') as $filename) {
			    $class = current(explode('.', basename($filename)));
			    include $filename;
			    $this->executeAll($class);
			}
		}
		
		function getRepport(){
			$res ='';
			foreach($this->_result as $key=>$result){
			    $res.=$key.' - ';
			    $res.=($result['passed']==true)?'PASSED':'FAILED';
			    if($result['passed']==false){
				$res.=' - '.$result['exception']->getMessage();
				foreach($result['exception']->getTrace() as $trace){
				    $res.="\n\t".' - file : '.$trace['file'];
				    $res.="\n\t".' - line : '.$trace['line'];
				    $res.="\n\t".' - function : '.$trace['function'];
				}
				$res.="\n";
			    }
			    $res.=($result['passed']==false)?(' - '.$result['exception']->getMessage()):'';
			    $res.="\n";
			}
			return trim($res);
		}
	}
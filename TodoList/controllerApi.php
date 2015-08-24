<?php
/* 
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * Class ControllerAPI : manage API 
 * 
 */
namespace CakeMailTest\TodoList;
use CakeMailTest\TodoList\errorApi as errorApi;	


class controllerApi {
	
	public function request() {
		if (version_compare(PHP_VERSION, '5.4.0', '<')) throw new ExceptionTodoList('The CakeMailTest TodoList Api requires version 5.4 or higher .');
		$requestArr = (METHOD_TYPE=='POST') ? filter_input_array(INPUT_POST) : filter_input_array(INPUT_GET); // GET ALL POST or GET VALUES
		if (self::mandatoryField($requestArr)) {
			try {
				$Request = new request($requestArr,$requestArr['ACTIONTYPE']);
				echo $Request->getReponse();		 				
			} catch (ExceptionTodoList $e) {
				echo $e->getMessage();
				exit();
			}	
		}		
		
		
	}
	
	static function mandatoryField($requestArr) {
		$err='';
		if (!is_array($requestArr))  $err = errorApi::EMPTYREQUEST_ERR;
		elseif (!array_key_exists('LOGIN', $requestArr)) $err = errorApi::EMPTYLOGIN_ERR;
		elseif (!array_key_exists('PWD', $requestArr)) $err = errorApi::EMPTYPWD_ERR;
		elseif (!array_key_exists('ACTIONTYPE', $requestArr)) $err = errorApi::EMPTYACTION_ERR;
		elseif (!user::authorization($requestArr['LOGIN'], $requestArr['PWD'])) $err = errorApi::AUTHENTICATION_ERR;
		if ($err!='') throw new ExceptionToDoList ($err);
		return true;
	}
	
	
}


?>
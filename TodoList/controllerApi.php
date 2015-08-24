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
	
	public function send() {
		
		$requestArr = filter_input_array(INPUT_POST); // GET ALL POST VALUES
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
		if (!array_key_exists('LOGIN', $requestArr)) $err = errorApi::EMPTYLOGIN_ERR;
		if (!array_key_exists('PWD', $requestArr)) $err = errorApi::EMPTYPWD_ERR;
		if (!array_key_exists('ACTIONTYPE', $requestArr)) $err = errorApi::EMPTYACTION_ERR;
		if (!user::authorization($tabRequest['LOGIN'], $tabRequest['PWD'])) $err = errorApi::AUTHENTICATION_ERR;
		if ($err!='') throw new ExceptionToDoList ($err);
		return true;
	}
	
	
}


?>
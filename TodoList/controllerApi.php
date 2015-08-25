<?php
/* 
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * Class ControllerAPI : manage API 
 * 
 */
namespace CakeMailTest\TodoList;

class controllerApi {
	
	public function request() {
		if (version_compare(PHP_VERSION, '5.4.0', '<')) throw new ExceptionTodoList('The CakeMailTest TodoList Api requires version 5.4 or higher .');
		$requestArr = (METHOD_TYPE=='POST') ? filter_input_array(INPUT_POST) : filter_input_array(INPUT_GET); // GET ALL POST or GET VALUES
		if (validRequest::mandatoryField($requestArr)) {
			try {
				$Request = new request($requestArr,$requestArr['ACTIONTYPE']); // send Request
				echo $Request->getResponse();   	 				
			} catch (ExceptionTodoList $e) {
				echo $e->getMessage();
				exit();
			}	
		}				
	}
}


?>
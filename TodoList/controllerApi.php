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
		$reponse = new response();
		if (user::authorization()) {
			$reponse->authorization(true);
			try {
				$request = new request($_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD']); // send Request	
				call_user_func_array(array($reponse,'set_content'),$request->getResponse());			
			} catch (ExceptionTodoList $e) {
				$reponse->set_content('404',$e->getMessage(),null);
				echo $reponse->send();
				exit();
			}	
		} else {  
			$reponse->set_content('401',errorApi::AUTHENTICATION_ERR,null);
		}				
		echo $reponse->send();
	}
}


?>
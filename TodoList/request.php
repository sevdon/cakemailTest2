<?php
/* 
 * Class request : create and getResponse request
 * Author : Severine Donnay
 * 
 */
namespace CakeMailTest\TodoList;
final class request {
	
	private $requestArr =null;
	private $actionType =null;
	private $handleLists;
	
	public function __construct($requestArr,$actionType) {
		$this->requestArr = $requestArr;
		$this->actionType = $actionType;
		
	}
	
	function getReponse() {
		
		
		if (!validRequest::checkFormat($this->requestArr,$this->actionType)) throw new ExceptionTodoList (errorApi::POSTFORMAT_ERR) ;

	     $this->handleLists = new handleListsStorageDB(); // objet to manage ToLists and storage to DB
         switch ($this->requestArr['ACTIONTYPE']) {
				
			case actionType::CREATE_ACTION :
				$func_name='create';
				$args = array_values(array_intersect_key($this->requestArr, array_flip(array('NAMELIST')))); 
				break;

			case actionType::ADDITEM_ACTION : 
				$func_name='addItem';
				$args = array (array($this->requestArr['NAMELIST']),array_intersect_key($this->requestArr, array_flip(array('CONTENT','STATUS'))));
				break;	
         }		
            
         
         var_dump($args);
		 $method = array ($this->handleLists,$func_name);
       	 echo (call_user_func_array($method,$args)) ? (responseMessage::MESSAGE_CREATE_SUCCESS) : (responseMessage::MESSAGE_CREATE_FAIL);
				
	 }
}
			

?>
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
				$Response = $this->handleLists->create($this->requestArr['NAMELIST']);
				echo ($Response==true) ? (responseMessage::MESSAGE_CREATE_SUCCESS) : (responseMessage::MESSAGE_CREATE_FAIL);
				break;	
         }		
				
	 }
}
			

?>
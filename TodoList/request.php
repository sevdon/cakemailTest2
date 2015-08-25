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
	
	public function getResponse() {
		
		
		if (!validRequest::checkFormat($this->requestArr,$this->actionType)) throw new ExceptionTodoList (errorApi::POSTFORMAT_ERR) ;

	     $this->handleLists = new handleListsStorageDB(); // objet to manage ToLists and storage to DB
	     $responseType = 'text/html';
         switch ($this->requestArr['ACTIONTYPE']) {
				
			case actionType::CREATE_ACTION :
				$func_name='create';
				$args = array_values(array_intersect_key($this->requestArr, array_flip(array('NAMELIST')))); 
				break;

			case actionType::ADDITEM_ACTION : 
				$func_name='addItem';
				$args = array($this->requestArr['NAMELIST'],array_intersect_key($this->requestArr, array_flip(array('CONTENT','STATUS'))));
				break;	
			case actionType::DELETE_ACTION :
				$func_name='delete';
				$args = array_values(array_intersect_key($this->requestArr, array_flip(array('NAMELIST')))); 
				break;
			case actionType::MODIFY_ACTION :
				$func_name='modifyLst';
				$args = array_values(array_intersect_key($this->requestArr, array_flip(array('NAMELIST','NEWNAMELIST')))); 
				break;	
         }		
            
         
         var_dump($args);

		 $method = array ($this->handleLists,$func_name);
       	 return self::sendResponse(call_user_func_array($method,$args),$responseType);
				
	 }
	 
	 private function sendResponse($content,$responseType) {
	 	
	 	if (!is_array($content) && $responseType=='text/html') return $content; // this API return simple content for all action except for getList action
	 	else { // only for GETLIST action to return XML file
	 		$Response = new response($content,$responseType);
	 		return $Response->send();
	 	}
	 	
	 } 
	 
	 
}
			

?>
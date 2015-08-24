<?php
/* 
 * Class request : create and getResponse request
 * Author : Severine Donnay
 * 
 */
namespace CakeMailTest\TodoList;
public class request {
	
	private $requestArr =null;
	private $actionType =null;
	private $handleLists;
	
	public function __construct($requestArr,$actionType) {
		$this->requestArr = $requestArr;
		$this->actionType = $actionType;
		
	}
	
	function getReponse() {
		
		
		if (!validRequest::createFormat($this->requestArr,$this->actionType)) {
			echo errorApi::POSTFORMAT_ERR ;
			exit;
		}
	    $this->handleLists = new handleLists();
         switch ($requestArr['ACTIONTYPE']) {
				
			case actionType::CREATE_ACTION : 
				echo ($this->handleLists->create($requestArr['NAMELIST'])) ? response::MESSAGE_CREATE_SUCCESS ; response::MESSAGE_CREATE_FAIL;
				break;	
				
	 }
}
			

?>
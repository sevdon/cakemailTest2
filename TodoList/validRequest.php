<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * abstract class validRequest : valid format of request
 * BOOL FormatCreate($request,$actionType)
 * 
 */
namespace CakeMailTest\TodoList;
abstract class validRequest {

	static function checkFormat($request,$actionType) {
		
		
		if ($actionType==actionType::CREATE_ACTION) {
			return (array_key_exists('NAMELIST', $request)) ? true : false;
		}
		
		return false;
		
	}

}

?>
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
		
		
		if ($actionType==actionType::CREATE_ACTION) return (array_key_exists('NAMELIST', $request)) ? true : false;
	    if ($actionType==actionType::ADDITEM_ACTION) {
	    	$keysRequired=array('NAMELIST','CONTENT','STATUS');
	    	return (self::array_all_keys_exist($keysRequired,$request)) ? true : false;
	    }
		
		return false;
		
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

	static function  filter_items($var,$key) {
		
		return ($key=='content' || $key=='status');
		
	}
	
	static function array_all_keys_exist($keysRequired,$argArr) {
		
		return (count(array_intersect_key(array_flip($keysRequired), $argArr)) === count($keysRequired)) ? true : false;
		
	}
	
}

?>
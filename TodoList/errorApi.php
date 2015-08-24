<?php
/*
 * Abstract class Error API
 * Author : Severine Donnay
 * Define all Error Message in API
 * 
 */
namespace CakeMailTest\TodoList;
abstract class errorApi {
	
	const EMPTYLOGIN_ERR='Login is missing';
	const EMPTYPWD_ERR='Password is missing';
	const EMPTYACTION_ERR='ActionType is missing';
	const AUTHENTICATION_ERR='Authentification fail';
	const POSTFORMAT_ERR='Post format error';
	
	
}

?>
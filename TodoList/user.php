<?php
/**
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * static class User 
 * for only one user with static login and password
 * implements here for evolution of API with multi-user authentification and storage in DDB
 * 
 */
namespace CakeMailTest\TodoList;

abstract class user {
	
	const ID = '1';
	const TOKEN = '819e1f61626f992055f6167dcf3d9a86b32d4e'; // detravaillerchezCakeMail
	
	static function authorization() {
		
		$HEADERS = apache_request_headers();
		if (!isset($HEADERS['Authorization']) || !isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['REQUEST_URI'])) return false;
		$pass = $HEADERS['Authorization'];
	/**	$request =$_SERVER['REQUEST_METHOD'].$_SERVER['REQUEST_URI'];
		$token = sha1(self::TOKEN.$request);
		return ($token==$pass) ? true:false;	// for a better token secure system
     **/		
		return (self::TOKEN==$pass) ? true:false; // a simple verification with the token
	}
	
}
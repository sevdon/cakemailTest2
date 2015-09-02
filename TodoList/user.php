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
	const TOKEN = '819e1f61626f992055f6167dcf3d9a86b32d4e79'; // detravaillerchezCakeMail
	
	static function authorization() {
		// return true;
		if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['REQUEST_URI'])) return false;
		$pass = $_SERVER['PHP_AUTH_USER'];
		$request =$_SERVER['REQUEST_METHOD'].$_SERVER['REQUEST_URI'];
		$token = sha1(self::TOKEN.$request);
		return (TOKEN==$pass) ? true:false;
	//	return ($token==$pass) ? true:false;		
	}
	
}
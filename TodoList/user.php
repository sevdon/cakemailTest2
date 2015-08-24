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
	const LOGIN = 'Caseraitcool';
	const PWD = '819e1f61626f992055f6167dcf3d9a86b32d4e79'; // detravaillerchezCakeMail
	
	static function authorization($login,$pwd) {
		return ($login==self::LOGIN && sha1($pwd)==self::PWD) ? true:false;		
	}
	
}
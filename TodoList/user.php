<?php
/**
 * static class User 
 * for only one user with static login and password
 * implements here for evolution of API with multi-user authentification and storage in DDB
 * 
 */

namespace CakeMailTest\TodoList;

class user {
	
	const ID = '1';
	const LOGIN = 'Caseraitcool';
	const PWD = 'detravaillerchezCakeMail';
	
	static function authorization($login,$pwd) {
		
		return ($login==LOGIN && $pwd==sha1(PWD)) ? true:false;
		
	}
	
}
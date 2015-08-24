<?php
/* 
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * class actionType
 * Define all constantes for ACTIONTYPE field
 * 
 */
namespace CakeMailTest\TodoList;
abstract class actionType {
	const CREATE_ACTION = 'CREATE';
	const MODIFY_ACTION = 'MOD';
	const DELETE_ACTION = 'DEL';
	const GETLIST_ACTION = 'GETLIST';
	const ADDITEM_ACTION = 'ADDITEM';
	const MODIFYITEM_ACTION = 'MODITEM';
	const DELETEITEM_ACTION = 'DELITEM';
	
}

?>
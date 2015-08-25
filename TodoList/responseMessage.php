<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * abstract class responseMessage 
 * 
 * 
 */
namespace CakeMailTest\TodoList;
abstract class responseMessage {
	
	const MESSAGE_CREATE_SUCCESS='New ToDolist was created successfully';
	const MESSAGE_MODIFY_SUCCESS='ToDolist was modified successfully';
	const MESSAGE_DEL_SUCCESS='ToDolist was deleted successfully';
	const MESSAGE_ADDITEM_SUCCESS='New item was added successfully';
	const MESSAGE_MODIFYITEM_SUCCESS='Item was modified successfully';
	const MESSAGE_DELITEM_SUCCESS='Item was deleted successfully';	
	
	
} 

?>
<?php
/*
 * API CakeMailTest TodoList V2.0
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
	const MESSAGE_GETITEM_SUCCESS='Items was listed sucessfully';
	const MESSAGE_GETLIST_SUCCESS='Todolist was listed sucessfully';
	const MESSAGE_UPDATEITEM_SUCCESS='Item status was modified successfully';
	const MESSAGE_DELITEM_SUCCESS='Item was deleted successfully';	
	const MESSAGE_DELITEM_FAIL='No item deleted : id and item-id not found';
	const MESSAGE_DEL_FAIL='No todolist deleted : id not found';
		
} 

?>
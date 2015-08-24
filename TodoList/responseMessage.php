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
	const MESSAGE_CREATE_FAIL='Fail : ToDolist was not created';
	const MESSAGE_MODIFY_FAIL='Fail : ToDolist was not modified';
	const MESSAGE_DEL_FAIL='Fail : ToDolist was not deleted';
	const MESSAGE_ADDITEM_FAIL='Fail : item was not added';
	const MESSAGE_MODIFYITEM_FAIL='Fail : Item was not modified';
	const MESSAGE_DELITEM_FAIL='Fail : Item was not deleted';
	const MESSAGE_GETLIST_FAIL='Fail : ToDolist was not gotten';
	
} 

?>
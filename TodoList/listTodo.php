<?php
/**
 * public class listTodo 
 * @name String : Identification name of the list
 * @itemArr Array : list of item object
 * 
 */
namespace CakeMailTest\TodoList;

public class listTodo extends listObject,handle {
	
	public $name = null;
	protected $itemArr=array();
	
	public function __construct($name) {
		$this->name=$name;
	}
	
	public function getItems($content) {

		
	}	
	
	public function setItems($itemsArr) {
		
		if ($this->isExist($itemsArr)) $this->itemArr['']
	}
	
	protected function isExist($contentItem) {
		if (array_key_exists($contentItem,$this->listsArr)) return true;
		else throw new ExceptionTodoList('Delete handle error :  name list not found');	
	}
	
}

?>
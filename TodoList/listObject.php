<?php
namespace CakeMailTest\TodoList;

abstract class listObjects {
		
	protected $listsArr=array();
	
	protected function isExist($nameList) {
		if (array_key_exists($nameList,$this->listsArr)) return true;
		else throw new ExceptionTodoList('Delete handle error :  name list not found');	
	}
	
	protected function getObject($nameList) {
		if ($this->isExist($nameList)) return listTodo($this->listsArr[$nameList])->getItems();
	}
	
}

?>
<?php
/*
 * static class handle : modify function 
 * public class handleLists : controller lists (add, modify, del,get)
 * void create : create a new list
 * void delete
 * 
 * 
 * public class handleItem : controller item (add,modify,del,get)
 * void create_list : create a new list
 * void modify_listinfo : modify infos of a list
 * void modify_itemlist : modify an item in a list 
 * void delele_list : delete a list
 * void delete_item : delete an item in a specific list
 * private void get_list : get an object list from lists
 * void get_itemlist :get an item in a specific  list
 * 
 */
namespace CakeMailTest\TodoList;

class handleLists extends handle {
	
	protected $user = null; // list owner
	protected $listsArr = array(); // Array of listTodo object
	
	public function __construct(user $user) {
		$this->user = $user;
	}
	
	public function create($nameList) {
	
		if (!array_key_exists($nameList,$this->listsArr)) { 
			$listTodo = new listTodo($nameList);
			$this->listsArr[$nameList]=$listTodo;	
		} else throw new ExceptionTodoList('Create handle error : duplicated list name');	
	}
	
	public function delete($nameList) {
		$this->getObject($nameList)->__destruct();
		unset($this->listsArr[$nameList]); 
		
	}
	
	
	public function setItems($nameList,Array $itemsArr) { 
		
			$listTodo = getObject($nameList);
			if (!array_key_exists('content', $listTodo)) (!getObject($nameList))->isExist())
			
			
		}
	}
	
		
}






?>
<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * public class handleLists => manage list to create, delete, addItem, getItem, modifyItemValue 
 * 
 * @itemArr : defined in parent class : list of todoLists array
 *  
 *  void create(string $namelist) => create a list identified by a name
 *  void delete(string $namelist) => delete a list identified by a name
 *  BOOL addItem(string $namelist, array $itemsArr) => add new item in list the content of the itemArr Array
 *  ARRAY getItem(string $nameList,array $itemsArr) => get array of items object 
 *  BOOL  delItem($nameList,$filters='') => del items in list defined by filters 
 *  
*/

namespace CakeMailTest\TodoList;

class handleLists extends listObjects {

		
	/*
	 * function create => to create a new Todolist with a specific name to be identified
	 * @var nameList = String => name of the list 
	 * 
	 */
	
	public function create($nameList) { 
	
		if (!$this->isNameExistInArray($nameList,'NOEXCEPTION')) { // There no list with this name 
			$listTodo = new listTodo($nameList);
			$this->listsArr[$nameList]=$listTodo;	
		} else throw new ExceptionTodoList('Create handle error : duplicated list name');	
		return true;
	}
	
	
	public function modify($nameList, array $valuesArr) {
		
		if (parent::arrayKeyExists('NAMELIST', $valuesArr)) parent::modify($this->getObject($nameList),$valuesArr);
		
	}
	
	/*
	 * function delete => to delete a Todolist identified with his name
	 * @var nameList = String => name of the list to be deleted
	 * 
	 */
	
	public function delete($nameList) {
		$this->getObject($nameList)->__destruct();
		unset($this->listsArr[$nameList]); 
		
	}
	
	/*
	 * function addItem => to add a new item in a specific list
	 * @var nameList = String => name of the list to add item
	 * @ itemArr = Array => contain item values (content and status) - Ex : Array ('content'=> 'task name to do','status'=>'TODO')
	 * return a BOOL
	 */
	
	
	public function addItem($nameList,array $itemsArr) { 
		
			echo 'on ajouter addItem';
			if (parent::arrayKeyExists('CONTENT', $itemsArr)) {	
				return $this->getObject($nameList)->addItem($itemsArr);
			}	
		}
		
	/*
	 * function getItem => to get an array with all items filtered by filters if defined
	 * @var nameList = String => name of the list to get items
	 * @var filter => filters 
	 * 
	 * return an Array of item objects
	 */
	
	public function getItem($nameList,$filters='') {
		
		 return $this->getObject($nameList)->getItem($filters);
	} 
	
	/*
	 * BOOL function delItem => to del items in list defined by filters
	 * @var nameList = String => name of the list to delete items
	 * @var filters => Array of filters to delete ex : array ('content'=>'buy a car') OR ('status'=>'TODO')
	 * 
	 * return an Array of item objects
	 */
	
	public function delItem($nameList,$filters) {
		 return $this->getObject($nameList)->delItem($filters);	
	}
		
}

?>
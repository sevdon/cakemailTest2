<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * public class handleListsStorageDB => manage list with DB Mysql
 * to create, delete, addItem, getItem, modifyItemValue 
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

final class handleListsStorageDB extends handleLists {

	
	
	/*
	 * function create => to create a new Todolist with a specific name to be identified
	 * @var nameList = String => name of the list 
	 * 
	 */
	
	public function create($nameList) { 
	
		if (parent::create($nameList)) {
			try {
				$DB = new DataBase(DB_NAME,DB_USER,DB_PWD,DB_HOST);
				$DB->prepareAndExecute("INSERT into lists (name,user_id) VALUES ('".DataBase::format_text_sql($nameList)."',".user::ID.")",'INSERT');
			} catch (\PDOException $e)	{
				throw new ExceptionToDoList ('Error DB to insert new list : '.$e->getMessage()); 
			}
			return true;
		} 
		return false;
		
	} 
	
	public function modify($nameList, array $valuesArr) {
		
		if (arrayKeyExists('nameList', $valuesArr)) parent::modify($this->getObject($nameList),$valuesArr);
		
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
		
			if (arrayKeyExists('content', $itemsArr)) {	
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
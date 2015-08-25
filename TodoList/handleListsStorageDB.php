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

	private $DB = null;
	
	
	/*
	 * function create => to create a new Todolist with a specific name to be identified
	 * @var nameList = String => name of the list 
	 * 
	 */
	
	public function __construct() {
		
		try {
			$this->DB = new DataBase(DB_NAME,DB_USER,DB_PWD,DB_HOST);
			
		} catch (\PDOException $e)	{
				throw new ExceptionToDoList ('Error DB to connect : '.$e->getMessage()); 
		} 
			
	}
	
	
	public function create($nameList) { 
		
	    if ($nameList=='') throw new ExceptionToDoList ('Error create new list : Namelist is empty'); 
		if (self::list_existInDB($nameList)) throw new ExceptionToDoList ('Error create : a list with a same name '.$nameList.' already exist'); 
		try {
			$this->DB->prepareAndExecute("INSERT into lists (name,user_id) VALUES ('".DataBase::format_text_sql($nameList)."',".user::ID.")",'INSERT');
		} catch (\PDOException $e)	{
			throw new ExceptionToDoList ('Error DB to insert new list : '.$e->getMessage()); 
		}
		return responseMessage::MESSAGE_CREATE_SUCCESS;
		
	} 
	
	function modifyLst ($nameList,$newNameList) {
		if ($nameList=='' || $newNameList=='') throw new ExceptionToDoList ('Error modify name list : Namelist is empty'); 
		if (!self::list_existInDB($nameList)) throw new ExceptionToDoList ('Error modify : no listname '.$nameList.' found');
		if (self::list_existInDB($newNameList)) throw new ExceptionToDoList ('Error modify : the new listname '.$nameList.' already exist'); 
		
		try {
			$this->DB->prepareAndExecute("UPDATE lists SET name='".DataBase::format_text_sql($newNameList)."' WHERE name='".DataBase::format_text_sql($nameList)."' ",'UPDATE');
		} catch (\PDOException $e)	{
			throw new ExceptionToDoList ('Error DB to update listName : '.$e->getMessage()); 
		}
		return responseMessage::MESSAGE_MODIFY_SUCCESS;
		
	}
	
	/*
	public function modify($nameList, array $valuesArr) {
		
		
		
	}
	*/
	
	/*
	 * function delete => to delete a Todolist identified with his name
	 * @var nameList = String => name of the list to be deleted
	 * 
	 */
	
	
	public function delete($nameList) {
		if ($nameList=='') throw new ExceptionToDoList ('Error delete list : Namelist is empty'); 
		if (!self::list_existInDB($nameList)) throw new ExceptionToDoList ('Error delete : no listname '.$nameList.' found'); 
		try {
			$this->DB->prepareAndExecute("DELETE from lists WHERE name = '".DataBase::format_text_sql($nameList)."'",'DELETE'); // items are deleted by Trigger on DB
		} catch (\PDOException $e)	{
			throw new ExceptionToDoList ('Error DB to delete list : '.$e->getMessage()); 
		}
		return responseMessage::MESSAGE_DEL_SUCCESS;
	}
	
	/*
	 * function addItem => to add a new item in a specific list
	 * @var nameList = String => name of the list to add item
	 * @ itemArr = Array => contain item values (content and status) - Ex : Array ('content'=> 'task name to do','status'=>'TODO')
	 * return a BOOL
	 */
	
	
	public function addItem($nameList,array $itemsArr) { 
			
		$list_id=self::get_listItems_fromDB($nameList);
		$item = parent::addItem($nameList,$itemsArr);
			try {
				$this->DB->prepareAndExecute("INSERT into items (content,list_id,status) VALUES ('".DataBase::format_text_sql($item->content)."',$list_id,'".DataBase::format_text_sql($item->status)."')",'INSERT');
			} catch (\PDOException $e)	{
				throw new ExceptionToDoList ('Error DB to insert new list : '.$e->getMessage()); 
			}
			return true;
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
	
	
	/*
	 * BOOL function list_existInDB( string $nameList) 
	 * Check if a lisf with namelist already exist in DB 
	 * return a BOOL
	 */
		
	
	private function list_existInDB($nameList) {
		
		$listArr = $this->DB->query_select("SELECT lists.id FROM lists WHERE name='".DataBase::format_text_sql($nameList)."' order by id DESC limit 0,1"); 
		if ($listArr[0]['count_row']==0) return false;
		else return true;
		
	}
	
	/*
	 * function generate_lists_fromDB() 
	 * Generate all lists object from DB records 
	 * 
	 */
	
	private function generate_lists_fromDB() {
		
		try {
			$listArr = $this->DB->query_select("SELECT lists.name FROM lists"); 
		} catch (\PDOException $e)	{
			throw new ExceptionToDoList ('Error DB to connect : '.$e->getMessage()); 
		} 
		for ($i=0;$i<$listArr[0]['count_row'];$i++) parent::create($listArr[$i]['name']);
	}
	
	/*
	 * function generate_list_fromDB(string $nameList) 
	 * Generate  one object list defined by listname from DB record
	 * Return id in DB for this list
	 */
	
	private function generate_list_fromDB($nameList) {
		
		try {
			$listArr = $this->DB->query_select("SELECT lists.id FROM lists WHERE name='".DataBase::format_text_sql($nameList)."' order by id DESC limit 0,1"); 
			if ($listArr[0]['count_row']==0) throw new ExceptionToDoList ('Error : name list not found');
		} catch (\PDOException $e)	{
			throw new ExceptionToDoList ('Error DB to connect : '.$e->getMessage()); 
		}
		return  array([$listArr[0]['id']]=>parent::create($nameList));	
	}
	
	/*
	 * BOOL function generate_lstitems_fromDB(int $list_id) 
	 * @list_id int : id list in DB
	 * Generate  all items object in this list from DB records
	 * Return BOOL
	 */
	
	
	private function generate_lstitems_fromDB($list_id) {
		
		
	}
}

?>
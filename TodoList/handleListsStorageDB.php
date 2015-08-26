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
	
	public function __construct($DB_NAME,$DB_USER,$DB_PWD,$DB_HOST) {
		$this->DB = new DataBase($DB_NAME,$DB_USER,$DB_PWD,$DB_HOST);	
	}
	
	
	public function create($nameList) { 
		
	    if ($nameList=='') throw new ExceptionToDoList ('Error create new list : Namelist is empty'); 
		if (self::list_existInDB($nameList)) throw new ExceptionToDoList ('Error create : a list with a same name '.$nameList.' already exist'); 
		$this->DB->prepareAndExecute("INSERT into lists (name,user_id) VALUES ('".DataBase::format_text_sql($nameList)."',".user::ID.")",'INSERT');
		return responseMessage::MESSAGE_CREATE_SUCCESS;
		
	} 
	
	function modifyLst ($nameList,$newNameList) {
		if ($nameList=='' || $newNameList=='') throw new ExceptionToDoList ('Error modify name list : Namelist is empty'); 
		if (!self::list_existInDB($nameList)) throw new ExceptionToDoList ('Error modify : no listname '.$nameList.' found');
		if (self::list_existInDB($newNameList)) throw new ExceptionToDoList ('Error modify : the new listname '.$nameList.' already exist'); 
		$this->DB->prepareAndExecute("UPDATE lists SET name='".DataBase::format_text_sql($newNameList)."' WHERE name='".DataBase::format_text_sql($nameList)."' ",'UPDATE');
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
		$this->DB->prepareAndExecute("DELETE from lists WHERE name = '".DataBase::format_text_sql($nameList)."'",'DELETE'); // items are deleted by Trigger on DB
		return responseMessage::MESSAGE_DEL_SUCCESS;
	}
	
	/*
	 * function addItem => to add a new item in a specific list
	 * @var nameList = String => name of the list to add item
	 * @ itemArr = Array => contain item values (content and status) - Ex : Array ('content'=> 'task name to do','status'=>'TODO')
	 * return message
	 */
	
	
	public function addItem($nameList,array $itemsArr) { 
		list($list_id,$lstTodo) = self::generate_listItem_fromDB($nameList);	
		$item=$lstTodo->addItem($itemsArr);	
		$this->DB->prepareAndExecute("INSERT into items (content,list_id,status) VALUES ('".DataBase::format_text_sql($item->content)."',$list_id,'".DataBase::format_text_sql($item->status)."')",'INSERT');
		return responseMessage::MESSAGE_ADDITEM_SUCCESS;
	}	
	
		
	/*
	 * function getItem => to get an array with all items filtered by filters if defined
	 * @var nameList = String => name of the list to get items
	 * @var filter => filters 
	 * 
	 * return an Array of item objects
	 */
	
	public function getItem($nameList,$filters='') {
		 list($list_id,$lstTodo) = self::generate_listItem_fromDB($nameList,$filters);
		 return $lstTodo;
	} 
	
	/*
	 * BOOL function delItem => to del items in list defined by filters
	 * @var nameList = String => name of the list to delete items
	 * @var filters => Array of filters to delete ex : array ('content'=>'buy a car') OR ('status'=>'TODO')
	 * return message
	 * 
	 */
	
	public function delItem($nameList,$filters) {
		 list($list_id,$lstTodo) = self::generate_listItem_fromDB($nameList,$filters);
		 $lstTodo->deleteAllItem();
		 $filters_sql = DataBase::generate_filter_sql($filters);	
		 $this->DB->prepareAndExecute("DELETE FROM items WHERE list_id=$list_id  $filters_sql",'DELETE');
		 return responseMessage::MESSAGE_DELITEM_SUCCESS;
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
		
		$listArr = $this->DB->query_select("SELECT lists.name FROM lists"); 
		for ($i=0;$i<$listArr[0]['count_row'];$i++) parent::create($listArr[$i]['name']);
	}
	
	/*
	 * function generate_listItem_fromDB(string $nameList,array $filterArr) 
	 * Generate  one object list defined by listname from DB record
	 * Generate all items in this list
	 * Return an array int $list_id,lstTodo $Object
	 * 
	 * $filterArr : associatif array with key='value' to filter in Where clause Request
	 */
	
	private function generate_listItem_fromDB($nameList,$filters='') {
		
		// Request for this nameList in DB
		
		$listArr = $this->DB->query_select("SELECT lists.id FROM lists WHERE name='".DataBase::format_text_sql($nameList)."' order by id DESC limit 0,1"); 
		if ($listArr[0]['count_row']==0) throw new ExceptionToDoList ('Error : name list not found');
		$id = $listArr[0]['id'];
		$lstTodo = parent::create($nameList); // Create a new object of listTodo class
		
		// Request for items nameList in DB
		
		if (is_array($filters)) $filters = DataBase::generate_filter_sql($filters);
		
		$listArr = $this->DB->query_select("SELECT items.content, items.status FROM items WHERE list_id=$id $filters order by id DESC"); 
		for ($i=0;$i<$listArr[0]['count_row'];$i++) {
			if ($listArr[$i]['content']!='') $lstTodo->addItem(array('content'=>$listArr[$i]['content'],'status'=>$listArr[$i]['status']));	
		}
		return array($id,$lstTodo);	
	}
	
	
}

?>
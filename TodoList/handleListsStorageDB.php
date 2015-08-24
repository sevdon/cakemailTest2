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
	 * static function generate_lists(Array $listArr) 
	 * This method is not optimized 
	 * Using 2 loop is not the best solution 
	 * To next version of API : check for better solution by remplacing fo & while loops by manipulate array functions with filters and a new function add_items to add multiple items 
	 * 
	 * 
	 */
	
	protected function generate_lists($listArr) {
		
		for ($i=0;$i<$listArr[0]['count_row'];$i++) {
			
			$nameList = $listArr[$i]['name'];
			parent::create($nameList);
			$id = $listArr[$i]['id'];			
			while ($id == $listArr[$i]['id']) {
				if ($listArr[$i]['content']!='') parent::addItem($nameList,array ('CONTENT'=>$listArr[$i]['content'],'STATUS'=>$listArr[$i]['status']));
				$i++;
				if ($i>=$listArr[0]['count_row']) break;
			}
		}
	}
	
	
	/*
	 * function create => to create a new Todolist with a specific name to be identified
	 * @var nameList = String => name of the list 
	 * 
	 */
	
	public function __construct() {
		
		try {
			$this->DB = new DataBase(DB_NAME,DB_USER,DB_PWD,DB_HOST);
			$sql = "SELECT lists.name,lists.id,'' AS content,'' AS status FROM lists WHERE lists.id NOT IN (select items.list_id from items) 
				    UNION SELECT lists.name,lists.id,items.content,items.status FROM lists,items WHERE lists.id = items.list_id";
			$listArr = $this->DB->query_select($sql); 
		} catch (\PDOException $e)	{
				throw new ExceptionToDoList ('Error DB to connect : '.$e->getMessage()); 
		} 
		
		$this->generate_lists($listArr);
	}
	
	
	public function create($nameList) { 

	    if ($nameList=='') throw new ExceptionToDoList ('Error create new list : Namelist is empty'); 
		if (parent::create($nameList)) {
			try {
				$this->DB->prepareAndExecute("INSERT into lists (name,user_id) VALUES ('".DataBase::format_text_sql($nameList)."',".user::ID.")",'INSERT');
			} catch (\PDOException $e)	{
				throw new ExceptionToDoList ('Error DB to insert new list : '.$e->getMessage()); 
			}
			return true;
		} 
		return false;
		
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
	
	/*
	public function delete($nameList) {
		$this->getObject($nameList)->__destruct();
		unset($this->listsArr[$nameList]); 
		
	}
	*/
	
	/*
	 * function addItem => to add a new item in a specific list
	 * @var nameList = String => name of the list to add item
	 * @ itemArr = Array => contain item values (content and status) - Ex : Array ('content'=> 'task name to do','status'=>'TODO')
	 * return a BOOL
	 */
	
	
	public function addItem($nameList,array $itemsArr) { 
		
			if (parent::addItem($nameList,$itemsArr)) {
				return true;
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
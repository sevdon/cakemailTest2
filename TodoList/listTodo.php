<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * public class listTodo : a Todolist
 * @nameList String : Identification name of the list
 * @itemArr array : defined in parent class : list of item object
 * 
 */

namespace CakeMailTest\TodoList;

class listTodo extends listObjects {
	
	public $nameList = null;
	
	public function __construct($nameList) {
		$this->nameList=$nameList;
	}
	
	
	/*
	 * function filter_status_default
	 * callback function to filter only status default item
	 * 
	 */
	
	static function filter_status_default($var) {
		
		return ($var->get_status()==statusItem::DEFAULT_STATUS);
	}
	
	/*
	 * function filter_status_default
	 * callback function to filter only status default item
	 * 
	 */
	
	
	static function filter_status_done($var) {
		
		return ($var->get_status()==statusItem::DONE_STATUS);
	}
	
	
	/*
	 * function getItem => get all items or items filtered in list
	 * return Array of Items objects with Item content of key 
	 * @filter : array with filters - ex : array('status'=>'TODO') 
	 * 
	 */
	
	public function getItem(array $filter) {

		if ($filter=='') return $this->listsArr;
		elseif (parent::arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_STATUS) return array_filter($this->listsArr,'self::filter_status_default');
		elseif (parent::arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_DONE) return array_filter($this->listsArr,'self::filter_status_done');
		else throw new ExceptionTodoList('Error : filters no defined');
		
	}

	/*
	 * function addItem => add item in list  
	 * @itemArr : array with contents for items - ex : array('content'=>'Buy a new car','status'=>'DONE'....all new Fields) 
	 * 
	 */
	
	public function addItem($itemsArr) {

			$args = array_values($itemsArr);
			$refItem = new \ReflectionClass('CakeMailTest\TodoList\item');
			$item = $refItem->newInstanceArgs($args);
		 	array_push($this->listsArr, $item);
		 	return $item;		
	}
	
	/*
	 * function deleteAllItem => delete all items in list  
	 * 
	 */
	
	public function deleteAllItem() {
		
		foreach ($this->listsArr as $item) $item->__destruct();
		$this->listsArr = array();
		
	}
		
	
}

?>
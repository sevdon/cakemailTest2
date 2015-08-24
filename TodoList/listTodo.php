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

public class listTodo extends listObjects {
	
	private $nameList = null;
	protected $itemArr=array();
	
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

		if ($filter=='') return $this->itemArr;
		elseif (arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_STATUS) return array_filter($this->itemArr,'self::filter_status_default');
		elseif (arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_DONE) return array_filter($this->itemArr,'self::filter_status_done');
		else throw new ExceptionTodoList('Error : filters for getitems not defined');
		
	}

	/*
	 * function addItem => add item in list 
	 * return BOOL 
	 * @itemArr : array with contents for items - ex : array('content'=>'Buy a new car') 
	 * 
	 */
	
	public function addItem($itemsArr) {
		
		 if (arrayKeyExists('content', $itemsArr)) {
		 	$this->itemArr['content']=new item($itemsArr['content']);	
		 	return true;	
		 }
	}
	
	/*
	 * function delItem => delete items in list 
	 * return BOOL 
	 * @itemArr : array with contents for items - ex : array('content'=>'Buy a new car') 
	 * 
	 */
	
	public function delItem($filters) {
		if (arrayKeyExists('content', $filter)) {
			
			unset($this->itemArr[$filter['content']]);
		} elseif (arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_STATUS) $this->itemArr = array_filter($this->itemArr,'self::filter_status_done');
		elseif (arrayKeyExists('status', $filter) && $filter['status']==statusItem::DEFAULT_DONE) $this->itemArr = array_filter($this->itemArr,'self::filter_status_default');
	}
	
	
	
	
	
	
}

?>
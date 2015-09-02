<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * public class handleListsStorageDB => manage list with DB Mysql
 * 
 * 
 * (integer) function post_todolists()  add a new todolists - return id created
 * get_todolists() list all todolists  	
 * (integer) delete_todolists(id) delele a todolists with id - return id deleted
 * put_items(id,content,status='') add item or updat status item in a todolist 
 * get_items(id,statuts='') list of all items in todolist id - filter with status = done | default 
 * delete_items(id,item_id) delete item defined by  item_id in totolist defined by id
 *  
*/

namespace CakeMailTest\TodoList;

final class handleListsStorageDB {

	private $DB = null;
	public function __construct($DB_NAME,$DB_USER,$DB_PWD,$DB_HOST) {
		$this->DB = new DataBase($DB_NAME,$DB_USER,$DB_PWD,$DB_HOST);	
	}
	
	/*
	 * function post_todolists() = add a new todolist 
	 * 
	 */
	
	public function post_todolists() { 
		
		$id = $this->DB->prepareAndExecute("INSERT into lists (datecrea,user_id) VALUES (Now(),".user::ID.")",'INSERT');
		return (array(200,responseMessage::MESSAGE_CREATE_SUCCESS,array('id'=>$id)));
		
	} 
	
	/*
	 * function delete_todolists($id) = delete a todolist 
	 * 
	 */
	
	public function delete_todolists($id) {
		if (empty($id)) throw new ExceptionToDoList ('Error delete : missing id of todolist'); 
		$id = intval($id);
		$count = $this->DB->prepareAndExecute("DELETE from lists WHERE id = $id",'DELETE'); // items are deleted by Trigger on DB
		return ($count>0) ? array(200,responseMessage::MESSAGE_DEL_SUCCESS,array('id'=>$id)) :  array(404,responseMessage::MESSAGE_DEL_FAIL,null);
	}
	
	
	/*
	 * function put_items($id,$content,$status='')  add a new item with content in a todolist with id=id OR update an item in todolist with status
	 * status take default_status if is empty or with an undefined value
	 */
	
	
	public function put_items($id,$content,$status='') {
		
		if (empty($id) || empty($content)) throw new ExceptionToDoList ('Error put item : missing id todolist or content item'); 
		if (!self::list_existInDB($id)) throw new ExceptionToDoList ('Error put item : no todolist '.$id.' found');
		if ($status=='' || !defined('CakeMailTest\TodoList\statusItem::'.strtoupper($status).'_STATUS')) $status = statusItem::DEFAULT_STATUS;
		 $id = intval($id);
		// update status
		$count = $this->DB->prepareAndExecute("UPDATE items SET status='".DataBase::format_text_sql($status)."' WHERE content ='".DataBase::format_text_sql($content)."' AND list_id=$id",'UPDATE');
		$rs = $this->DB->query_select("SELECT id FROM items WHERE content ='".DataBase::format_text_sql($content)."' AND list_id=$id order by id DESC limit 0,1"); 
		if ($count>0 || $rs[0]['count_row']>0) { // update return
			$data = json_decode(json_encode(new item($rs[0]['id'],$content,$status)),true);
			return (array(200,responseMessage::MESSAGE_UPDATEITEM_SUCCESS,$data));
		} else {	// insert a new item
			$id_item = $this->DB->prepareAndExecute("INSERT into items (content,list_id,status) VALUES ('".DataBase::format_text_sql($content)."',$id,'".DataBase::format_text_sql($status)."')",'INSERT');
			$data = json_decode(json_encode(new item($id_item,$content,$status)),true);
			return (array(200,responseMessage::MESSAGE_ADDITEM_SUCCESS,$data));
		} 	
	}	
	
	
	/*  
	 *  function delete_items: delete an item id_item in todolist id 
	 * 
	 */
	
	public function delete_items($id,$id_item) {
		 if (empty($id) || empty($id_item)) throw new ExceptionToDoList ('Error delete item : missing id todolist or id item');
		 $id = intval($id);
		 $id_item = intval($id_item);
		 $count = $this->DB->prepareAndExecute("DELETE FROM items WHERE list_id=$id AND id=$id_item",'DELETE');
		 return ($count>0) ? array(200,responseMessage::MESSAGE_DELITEM_SUCCESS,array('id'=>$id,'id_item'=>$id_item)) :  array(404,responseMessage::MESSAGE_DELITEM_FAIL,null);
	}
	
	
	
	
		
	/*
	 * function get_items(id,status)
	 * return all item in selected todolist by id
	 * filter by status if defined
	 */
	
	public function get_items($id,$status='') {
		 if (empty($id)) throw new ExceptionToDoList ('Error get items : missing id of todolist'); 
		 $id = intval($id);
		 ($status !='') ? $filters = " AND status='".DataBase::format_text_sql($status)."' ": $filters=''; 
		 $data = json_decode(json_encode(self::generate_listItem_fromDB($id,$filters)));
		 return (array(200,responseMessage::MESSAGE_GETITEM_SUCCESS,$data));
	} 
	
	
	/*
	 * function get_todolists()
	 * return all todolist 
	 * 
	 */
	
	public function get_todolists() {
		
		 $rs = $this->DB->query_select("SELECT lists.id, DATE_FORMAT(lists.datecrea,'%m-%d-%Y') as date FROM lists order by id ASC"); 	 
		 $data = json_decode(json_encode($rs),true);	
		 return (array(200,responseMessage::MESSAGE_GETLIST_SUCCESS,$data));
	} 
	
	
	
	/*
	 * function generate_listItem_fromDB(string $nameList,array $filterArr) 
	 * Generate  one object list defined by listname from DB record
	 * Generate all items in this list
	 * Return an array int $list_id,lstTodo $Object
	 * 
	 */
	
	private function generate_listItem_fromDB($id,$filters='') {
		
		$id = intval($id);
		$listArr = $this->DB->query_select("SELECT lists.id FROM lists WHERE id=$id order by id DESC limit 0,1"); 
		if ($listArr[0]['count_row']==0) throw new ExceptionToDoList ('Error : list not found');
		$lstTodo = new listTodo($id); // Create a new object of listTodo class
		
		$listArr = $this->DB->query_select("SELECT items.content, items.status,items.id FROM items WHERE list_id=$id $filters order by id DESC"); 
		for ($i=0;$i<$listArr[0]['count_row'];$i++) {
			if ($listArr[$i]['content']!='') $lstTodo->addItem(array('id'=>$listArr[$i]['id'],'content'=>$listArr[$i]['content'],'status'=>$listArr[$i]['status']));	
		}
		return $lstTodo;	
	}
	
/*
	 * BOOL function list_existInDB( $id) 
	 * Check if a lisf with id already exist in DB 
	 * return a BOOL
	 */
		
	
	private function list_existInDB($id) {
		$id = intval($id);
		$listArr = $this->DB->query_select("SELECT lists.id FROM lists WHERE id=$id order by id DESC limit 0,1"); 
		if ($listArr[0]['count_row']==0) return false;
		else return true;
		
	}
	
	
	
	/*
	 * catch all calls to undefined method 
	 * 
	 */
	
	public function __call($name, $arguments) { 
		throw new ExceptionToDoList ('Error call : method '.$name.' doesn\'t exist');	
	}
	
	
}

?>
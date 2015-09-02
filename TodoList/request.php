<?php
/* 
 * Api RESTFul V1.0 CakeMailTest TodoList
 * Class request : send request to HandleListStorageDB object and send response to APIController
 * Author : Severine Donnay
 * 
 * 
 * /api/todolists      		    				POST    add a new todolist  :: return id created                      
 * /api/todolists/:id      						DELETE  delete the todolist with id ofÊ: id
 * /api/todolists	  			    			GET     return all todolist
 * /api/todolists/:id/items/:content:status		PUT		update/add item with content ofÊ: content for the todolists with id ofÊ: id - update status if this value is filled
 * /api/todolists/:id/items 					GET     return the items for the totolistÊwith id ofÊ: id
 * /api/todolists/:id/items/:status   		 	GET     return the item with status ofÊ: status for the todolists with id ofÊ: id
 * /api/todolists/:id/items/:iid	  		 	DELETE	delete item with iid ofÊ: iid for the todolists with id ofÊ: id
 *  
 */

namespace CakeMailTest\TodoList;
final class request {
	
	private $uri;
	private $verb;
	private $handleLists;
	private $response;
	private $verbsArr = array('GET','POST','PUT','DELETE');
	private $collectionArr = array('todolists','items');
	
	public function __construct($uri,$verb) {
		$this->uri = $uri;
		$this->verb = $verb;
		if (!in_array($verb,$this->verbsArr)) throw new ExceptionToDoList (errorApi::VERB_ERR);	
	}
	
	public function getResponse() {
			
	    $this->handleLists = new handleListsStorageDB(DB_NAME,DB_USER,DB_PWD,DB_HOST); // objet to manage ToLists and storage to DB
	    list($method,$args)=self::generate_call($this->verb,$this->uri);
        $method = array ($this->handleLists,$method); //  method with args to manage request
       	return call_user_func_array($method,$args); // call this method and return to Controller
	}
	
	private function generate_call($verb,$uri) {
		/*
		 * generate method from uri and verbs : post_items, get_items, put_items, post_todolists...
		 * generate array of args from uri
		 */
		
		$uriArr = explode('/',substr($uri,1));
		$collections = array_intersect($uriArr, ($this->collectionArr)); // only collections name
		$collection_call = array_pop($collections); // the first collection name is the collection call
		$method = strtolower($verb.'_'.$collection_call); // method to call has format verb_collection 
		if (DEBBUGAGE_MODE) echo $method;
		$args = array_filter($uriArr, function($val) { // filter for arguments only val begin with : 
			$exp = '/^:/';
			return preg_match($exp,$val); 
		});
		$arg_str = implode('',$args);
		$args = explode(':',substr($arg_str,1));
		if (DEBBUGAGE_MODE) var_dump($args);
		return (array($method,$args));
	}
	 
}


			

?>
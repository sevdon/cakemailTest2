<?php
namespace CakeMailTest\TodoList;

abstract class handle {
		
	public function modify ($object,Array $values) {
		
		if (!is_object($object)) throw new ExceptionsTodoList('Modify handle error : object required');
		foreach ($values as $key=>$value) { 
			if (property_exists(get_class($object),$key)) $object->$key = $value; // implements all public properties of class object defined in $values array keys
		}	
	}
	
	
}

?>
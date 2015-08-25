<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * abstract class listObjects
 * @nameList String : Identification name of the list
 * @itemArr array : list of item object
 * 
 */
namespace CakeMailTest\TodoList;

abstract class listObjects {
		
	public $listsArr=array();
	
    /*
     * BOOL function isNameExistInArray(string $nameList, string $flag) : return true if this nameList already exist in $listArr array
     * $nameList string : name of list
     * throw an Exception if flag is 'THROW_EXCEPTION'
     * 
     */
	
	
	protected function isNameExistInArray($nameList,$flag='THROW_EXCEPTION') {
		if (array_key_exists($nameList,$this->listsArr)) return true;
		elseif ($flag=='THROW_EXCEPTION') throw new ExceptionTodoList('Error : name list not found');	
	}
	
	
	/*
     * BOOL function arrayKeyExists(string $key,array $search ) : check if key is in array  
     * $key : key 
     * $search : array to search
     * throw an Exception 
     * 
     */
	
	protected function arrayKeyExists($key,array $search) {
		
	if (array_key_exists($key, $search)) return true;
	else throw new ExceptionTodoList('Error : field '.$key.' not found in array');
			
		
	}
	
	/*
     * Mix function getObject(string $nameList ) : get Object List defined by nameList  
     * $nameList string : name of list 
     * return object defined by nameList in $listsArr array
     * 
     */
	
	protected function getObject($nameList) {
		if ($this->isNameExistInArray($nameList)) return $this->listsArr[$nameList];
	}
	
	/*
     * function modify($object,array $values)  : modify all object attribute defined in values array
     * $objet : object 
     * $valuesArr : array of values - ex : array ('content'=>'buy a new car','status','TODO');
     * check if each key in values array is attribute in class object before change value
     * 
     */
	
	public function modify ($object,array $valuesArr) {
		
		if (!is_object($object)) throw new ExceptionsTodoList('Modify handle error : object required');
		foreach ($valuesArr as $key=>$value) { 
			if (property_exists(get_class($object),$key)) $object->$key = $value; // implements all public properties of class object defined in $values array keys
		}	
	}
	
	
	
}

?>
<?php
/* public class item 
 * @content String : description of the todo thing
 * @status Char : statut of item 
 */

namespace CakeMailTest\TodoList;

final public class item {
	
	$content = null;
	$statut = null;
	
	public function __construct($content) {
		$this->content = $content;
		$this->statut = $content;
	}
	
	public function change_statut($statut) {
		
		$this->statut = $statut;
	}
	
}

?>
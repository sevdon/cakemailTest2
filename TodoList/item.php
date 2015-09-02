<?php
/* API CakeMailTest TodoList V1.0
 * public class item 
 * @content String : description of task to todo 
 * @status Char : status of item 
 */
namespace CakeMailTest\TodoList;

class item {
	
	public $id = null;
	public $content = null;
	public $status = null;
	
	function __construct($id,$content,$status=statusItem::DEFAULT_STATUS) {
		
		$this->id = $id;
		$this->content = $content;
		$this->status = $status;
			
	}
	
	public function set_status($status) {
		
		$this->status = $status;
	}
	
	public function get_status() {
		return $this->status;
	}
	
}

?>
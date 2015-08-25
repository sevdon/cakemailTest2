<?php
/* 
 * Class response : response to send
 * Author : Severine Donnay
 * 
 */
namespace CakeMailTest\TodoList;
final class response {
	
	$content = array();
	$type_content='text/html';
	
		public function __construct($content,$type_content) {
			
			$this->content=$content;
			$this->type_content=$type_content;
		}
		
		public function send() {
			
			header('Content_type: '.$this->type_content);
			if ($this->type_content=='text/html') echo $this->content[0];
			elseif ($this->type_content=='text/xml') echo self::generate_xml($this->content);
		}
		
		
		private generate_xml($contentArr) {
			
			
		}
	
}

?>
<?php
/* 
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * Class response : response to send
 * 
 */
namespace CakeMailTest\TodoList;
class response {
	
	private $content=array();
	private $type_content='text/html';
	
		public function __construct($content,$type_content) {
			
			$this->content=$content;
			$this->type_content=$type_content;
		}
		
		public function send() {
			$content = $this->type_content;
			header("Content-Type: $content");
			header("Cache-Control: no-cache, must-revalidate");
			if ($this->type_content=='text/html') return $this->content;
			elseif ($this->type_content=='text/xml') return self::generate_xml($this->content);
		}
		
		
		private function generate_xml($contentObject) {
			
			$contentArr = json_decode(json_encode($contentObject),true);
			$xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Todolst></Todolst>');
			self::array_to_xml($contentArr,$xml_data);
			return $xml_data->asXML();	
			
		}
		
		
		public function array_to_xml( $data,&$xml_data ) {
			
   		 foreach( $data as $key => $value ) {
   		 	
	        	if (is_array($value)) {
	            	if( is_numeric($key)) $key = 'item'.$key; 
	            	$subnode = $xml_data->addChild($key);
	            	self::array_to_xml($value, $subnode);
	        	} else {
	            	$xml_data->addChild($key,htmlspecialchars($value));
	       		 }
    	 }
}


		
		
}

?>
<?php
/* 
 * API CakeMailTest TodoList V2.0
 * Author : Severine Donnay
 * Class response : response to send
 * 
 */
namespace CakeMailTest\TodoList;
class response {
	
	const   CONTENT_TYPE ='application/json';
	private $status_message;
	private $status;
	private $data;
	private $authorization = false;
	
		public function __construct() {

		}
		
		public function set_content($status,$status_message,$data) {
			$this->data=$data;
			$this->status=$status;
			$this->status_message=$status_message;
		}
		
		public function send() {
			
			$content_type = self::CONTENT_TYPE;
			$status = $this->status;
			$status_message = $this->status_message;
			
			$responseArr['status']=$status;
			$responseArr['status_message']=$status_message;
			 
			if ($this->data !=null) $responseArr['data'] = json_decode(json_encode($this->data),true); // transform to array
			if ($this->authorization) header("HTTP/1.1 : $status $status_message");
			else {
				header('WWW-Authenticate: Token');
				header('HTTP/1.0 401 Unauthorized');
			}
			
			header("Content-Type: $content_type");
			header("Cache-Control: no-cache, must-revalidate");
			return json_encode($responseArr);
			
		}
		
		public function authorization($bool) {
			$this->authorization = $bool;
		}


		
		
}

?>
<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * abstract class DataBase : object to manipulate all DataBase (Oracle, Mysql,SQLite) with PDO 
 * injection SQL verification
 * 
 */
namespace CakeMailTest\TodoList;
 
final class DataBase {
	
 	private $dsn=null;
	private $pdo = null;
	private $typeBDD='MYSQL';
	public $debugg_affiche = true;
	protected $prep=null;
	private $check_injection_sql = false;
	
	
	public function __construct($dbname,$user='',$pwd='',$host='',$typeDB='MYSQL') {
		
		$this->typeBDD=$typeDB;
		try {
			if ($this->typeBDD=='MYSQL') {
				$this->dsn = "mysql:host=$host;dbname=$dbname";
				$this->pdo = new \PDO($this->dsn,$user,$pwd);
				$this->pdo->setAttribute (\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			} elseif ($this->typeBDD=='ORACLE') {
				$this->dsn = "oci:dbname=$dbname";
				$this->pdo = new \PDO($this->dsn);
			} elseif ($this->typeBDD=='SQLITE') {
				$this->dsn = "sqlite2:$dbname";
				$this->pdo = new \PDO($this->dsn);
			}
		} catch (\PDOException $e) {
			die('Error connect to DB : '.$e->getMessage());	
		}
		
	}
	
	public function check_injection_sql(BOOL $status) {
		
		$this->check_injection_sql = $status;
	}
	
	public function connect_dsn($dsn) {
		
		$this->dsn = $dsn;
		try {
			$this->pdo = new \PDO($this->dsn);
		} catch (\PDOException $e) {
			die('Error connect to DB : '.$e->getMessage());	
		}
	}
	
	
	
	public function query_select($sql) {
		
		if ((!$this->check_injection_sql) || ($this->check_injection_sql && self::is_sql_valide($sql))) {
			if ($this->debugg_affiche) echo 'on fait la requete SELECT: '.$sql.'<br/>';
			$results = $this->query($sql);
			$i=0;
			while ($result = $results->fetch()) {
				foreach($result as $key => $elem) {
					$tab_ass[$i][$key] = stripslashes($elem);	
				}			
				$i++;
			}
			$tab_ass[0]['count_row']=$i;
			return $tab_ass;
		} 
		return false;
	}
	
	
	public function query_get_valeur($sql,$field) {
		
		if ($this->debugg_affiche) echo 'on fait la requete dans get_valeur : '.$sql.'<br/>';
		$tab_ass = $this->query_select($sql);
		return (isset($tab_ass[0]['count_row']) && $tab_ass[0]['count_row']>0)?$tab_ass[0][$field]:false;
		
	}
	
	public function query_exec($sql,$type_requete="update") {
	
		if ($this->debugg_affiche) echo 'on fait la requete : '.$sql.'<br/>';
		if ((!$this->check_injection_sql) || ($this->check_injection_sql && self::is_sql_valide($sql))) $count_row = $this->query_ex($sql);
		else return false;	
		return true;
		
	}
	
	private function query($sql) {
		try {
			$results = $this->pdo->query($sql);
		} catch (\PDOException $e) {
			die('Query error on : '.$sql.' <br/> : '.$e->getMessage());
		}
		return $results;
		
	}
	
	 private function query_ex($sql) {
		try {
			$count_row = $this->pdo->exec($sql);
		} catch (\PDOException $e) {
			die('Query error on '.$sql.' <br/> : '.$e->getMessage());
		}
		return $count_row;
		
	}
	
	public function query_num_rows($sql) {
	
		if ($this->debugg_affiche) echo 'on vŽrifie le nb d\'enreg : '.$sql;
		if ((!$this->check_injection_sql) || ($this->check_injection_sql && self::is_sql_valide($sql))) return $this->query_ex($sql);
		return false;	
	}

	public function prepareAndExecute($sql,$type_requete='update',$tabmarqueurs=null) {
		try {
				
			$this->prepare($sql,$type_requete);
			$this->execute_sqlprepared($tabmarqueurs);
		} catch (\PDOException $e) {
				die('Query error on '.$sql.' <br/> : '.$e->getMessage());
		}
	}
	
	public function prepare($sql,$type_requete='update') {
		
		if ($this->debugg_affiche) echo 'on prepare '. $type_requete .' : '.$sql;
		
		if ((!$this->check_injection_sql) || ($this->check_injection_sql && self::is_sql_valide($sql))) {
			try {
				$this->prep = $this->pdo->prepare($sql);
			} catch (\PDOException $e) {
				die('Query error on '.$sql.' <br/> : '.$e->getMessage());
			}
		}	
		
	}	

	public function execute_sqlprepared($tabmarqueurs=null) {
		if ($this->debugg_affiche) echo 'on execute';
		try {
			if (is_array($tabmarqueurs)) $this->prep->execute($tabmarqueurs);
			else $this->prep->execute();
		} catch (\PDOException $e) {
				die('Query error on <br/> : '.$e->getMessage());
			}	
		
	}
	
	static function is_sql_valide($sql,$type_requete='select') {
			
		$pattern='';
		switch ($type_requete) {
			
			case 'SELECT' : $pattern = '/^select/i'; $tab_forbidden=array('delete','insert','update','alter','describe','create');break;
			case 'UPDATE' : $pattern = '/^update/i'; $tab_forbidden=array('delete','insert','select','alter','describe','create');break;
			case 'INSERT' : $pattern = '/^insert/i'; $tab_forbidden=array('delete','select','update','alter','describe','create');break;
			case 'DELETE' : $pattern = '/^delete/i'; $tab_forbidden=array('select','insert','update','alter','describe','create');break;
			
		}
			
		return ((preg_match($pattern, trim($sql))==true) && (self::stristr_multi(trim($sql), $tab_forbidden)==false)) ? true : false;
		
	}
	
	
	static function has_injection_sql($var) {
		
		if (!empty($var)) { // utilisation de stripos car insensible Ã  la casse
			$tab_forbidden=array(';select',';delete',';insert',';update',';alter',';describe',';create'); 
			if (self::stristr_multi($var,$tab_forbidden)) return true;
		}
		return false;
		
	}
	
	static function format_text_sql ($data) {
		
			return addslashes($data);
		
	} 
	
	static function stristr_multi($chaine,array $tab,$flag='ONE_ELEM') {
	
		foreach ($tab as $elem) {
			if (stristr($chaine, $elem)===false) {
				if ($flag=='ALL_ELEM') return false;	
			} elseif ($flag=='ONE_ELEM') return true;
		}
		return ($flag=='ALL_ELEM') ? true : false;
	}
			
	
}	
	
?>
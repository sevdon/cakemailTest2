<?php
/*
 * API CakeMailTest TodoList V1.0
 * Author : Severine Donnay
 * abstract class validRequest : valid format of request
 * BOOL FormatCreate($request,$actionType)
 * 
 */
namespace CakeMailTest\TodoList;
 
class DBHandle {
	
 	private $dsn=null;
	private $pdo = null;
	private $typeBDD='MYSQL';
	public $port_host= 8889; // port par d√©faut pour Mysql
	public $debugg_affiche = false;
	protected $prep=null;
	
	
	public function __construct($dbname,$user='',$pwd='',$host='',$typeDB='MYSQL',) {
		
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
			die('Error de connection à la BDD : '.$e->getMessage());	
		}
		
	}
	
	public function define_dsn($dsn) {
		
		$this->dsn = $dsn;
		try {
			$this->pdo = new \PDO($this->dsn);
		} catch (\PDOException $e) {
			die('Error de connection à la BDD : '.$e->getMessage());	
		}
	}
	
	
	public function open() {
		return true;
	}
	
   public function close() {
		return true;
	}
	
	
	public function query_select($sql) {
		
		if (parent::is_sql_valide($sql)) {
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
		if (parent::is_sql_valide($sql,$type_requete)) $count_row = $this->query_ex($sql);
		else return false;	
		return true;
		
	}
	
	private function query($sql) {
		try {
			$results = $this->pdo->query($sql);
		} catch (\PDOException $e) {
			die('Erreur sur la requête'.$sql.' <br/>Détail erreur : '.$e->getMessage());
		}
		return $results;
		
	}
	
	 private function query_ex($sql) {
		try {
			$count_row = $this->pdo->exec($sql);
		} catch (\PDOException $e) {
			die('Erreur sur la requête'.$sql.' <br/>Détail erreur : '.$e->getMessage());
		}
		return $count_row;
		
	}
	
	public function query_num_rows($sql) {
	
		if ($this->debugg_affiche) echo 'on vérifie le nb d\'enreg : '.$sql;
		if (parent::is_sql_valide($sql)) { // verifie que c un select unique -- pas de insert, update, drop, delete...(injection SQL)
			return $this->query_ex($sql);
		} 
		return false;	
	}

	
	public function prepare($sql,$type_requete='update') {
		if (parent::is_sql_valide($sql,$type_requete)) {
			try {
				$this->prep = $this->pdo->prepare($sql);
			} catch (\PDOException $e) {
				die('Erreur sur la requête'.$sql.' <br/>Détail erreur : '.$e->getMessage());
			}
		}	
		
	}	

	public function execute_sqlprepared($tabmarqueurs=null) {
		
		try {
			if (is_array($tabmarqueurs)) $this->prep->execute($tabmarqueurs);
			else $this->prep->execute();
		} catch (\PDOException $e) {
				die('Erreur sur la requête'.$sql.' <br/>Détail erreur : '.$e->getMessage());
			}	
		return $this->prep->fetchAll;
		
	}
		
	
}	
	
?>
<?php


/* =============================================================== */
/* =========================== INTERFACE ========================= */
/* =============================================================== */


interface Crud{
	public function create($name,$tel,$email);
	public function read();
	public function update($tel,$email);
	public function del($email);
	public function conectar();
	public function desconectar();
	public function estaContido($email);
}

/* =============================================================== */
/* ============= CLASSE PARA CONECTAR AO DB ====================== */
/* =============================================================== */


class ConexaoDb implements Crud{
	private $db;
	private $dbname;
	private $host;
	private $user;
	private $password;
	private $pdo;
	private $stmt;
	private $sql_comand;

	/* ========================= CONSTRUTOR ========================== */

	public function __construct($db,$dbname,$host,$user,$password){
		$this->db 		= $db;
		$this->dbname   = $dbname;
		$this->host     = $host;
		$this->user     = $user;
		$this->password = $password;
	}

	/* ===================== GETTERS E SETTERS ======================= */

	public function getDb(){
		return $this->db;
	}

	public function setDb($new_db){
		$this->db = $new_db;
	}

	public function getDbname(){
		return $this->dbname; 
	}

	public function setDbname($new_dbname){
		$this->dbname = $new_dbname;
	}

	public function getHost(){
		return $this->host;
	}

	public function setHost($new_host){
		$this->host = $new_host;
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser(){
		$this->user = $new_user;
	}

	public function getPassword(){
		return $this->password;
	}

	public function setPassword($new_password){
		$this->password = $new_password;
	}

	/* =============================================================== */
	/* ========================== MÉTODOS ============================ */
	/* =============================================================== */
	public function conectar(){
		try{
			$comand = $this->db.":dbname=".$this->dbname.";host=".$this->host; 
			$this->pdo = new PDO($comand,$this->user,$this->password);
		}catch(PDOException $error){
			echo "Erro com banco de dados: ".$error->getMessage();
		}catch(Exception $error){
			echo "Erro: ".$error->getMessage();
		}
	}

	public function create($name,$tel,$email){ 
		$email 		= filter_var($email,FILTER_SANITIZE_EMAIL);
		$name 		= strtolower($name);
		$name 		= ucwords($name);
		if($name == '' || $email == '' || $tel == '' || !is_int($tel)){
			echo "<h2>Preencha todos os campos corretamente!</h2>";
			return false;
		}
		$this->sql_comand = "INSERT INTO usuario(nome,telefone,email) VALUES (:nome,:telefone,:email)";
		$this->stmt 	  = $this->pdo->prepare($this->sql_comand);
		$this->stmt->bindValue(":nome",$name);
		$this->stmt->bindValue(":telefone",$tel);
		$this->stmt->bindValue(":email",$email);
		$this->stmt->execute();
		return true;
	}

	public function read(){
		$this->sql_comand = "SELECT * FROM usuario ORDER BY nome";
		$this->stmt 	  = $this->pdo->prepare($this->sql_comand);
		$this->stmt->execute();
		$search = $this->stmt->fetchAll(PDO::FETCH_ASSOC); 
		return $search;
	}

	public function update($tel,$email){
		$email = filter_var($email,FILTER_SANITIZE_EMAIL);
		if($email == '' || $tel == '' || !is_int($tel)){
			echo "<h2>Entre com dados válidos!</h2>";
			return false;
		}
		$this->sql_comand = "UPDATE usuario SET telefone = :tel WHERE email = :email";
		$this->stmt 	  = $this->pdo->prepare($this->sql_comand);
		$this->stmt->bindValue(":tel",$tel);
		$this->stmt->bindValue(":email",$email);
		$this->stmt->execute();
		return true;
	}

	public function del($email){
		$email 			  = filter_var($email,FILTER_SANITIZE_EMAIL);
		if($email == ''){
			echo "<h2>Digite um email válido!</h2>";
			return false;
		}
		$this->sql_comand = "DELETE FROM usuario WHERE email = :email";
		$this->stmt       = $this->pdo->prepare($this->sql_comand);
		$this->stmt->bindValue(":email",$email);
		$this->stmt->execute();
		echo "<h2>Excluido com sucesso!</h2>";
		return true;
	}

	public function estaContido($email){
		$email 		      = filter_var($email,FILTER_SANITIZE_EMAIL);
		$this->sql_comand = "SELECT id FROM usuario WHERE email= :email";
		$this->stmt 	  = $this->pdo->prepare($this->sql_comand);
		$this->stmt->bindValue(":email",$email);
		$this->stmt->execute();
		$search           = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		if($this->stmt->rowCount() > 0){
			return true;
		}
		return false;
	}

	public function desconectar(){
		$this->pdo = NULL;
	}

}

?>


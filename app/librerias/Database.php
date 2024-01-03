<?php
	class Database {
		# Database 
		public $host = DB_HOST;
		public $user = DB_USER;
		public $password = DB_PASSWORD;
		public $db_name = DB_NAME;

		public $dbh;
		public $stmt;
		public $error;

		public function __construct() {
			# Configurar la conexion
			$dsn = 'mysql:host='.$this->host.';dbname='.$this->db_name;

			$opciones = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);

			# Instancia PDO
			try {
				$this->dbh = new PDO($dsn, $this->user, $this->password, $opciones);
				# Arreglo de caracteres especiales
				// $this->dbh->exec('set names utf8');
				$this->dbh->exec('set names utf8mb4');
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				echo $this->error;
			}
		} // <- Fin del constructor

		# 
		public function query($sql) {
			$this->stmt = $this->dbh->prepare($sql);
		}

		# Vincular la consulta con bind
		public function bind($parametro, $valor, $tipo = null) {
			if (is_null($tipo)) {
				switch (true) {
					case is_int($valor):
						$tipo = PDO::PARAM_INT;
						break;
					case is_bool($valor):
						$tipo = PDO::PARAM_BOOL;
						break;
					case is_null($valor):
						$tipo = PDO::PARAM_NULL;
						break;
					default:
						$tipo = PDO::PARAM_STR;
						break;
				}
			}
			$this->stmt->bindValue($parametro, $valor, $tipo);
			
		}

		# Ejecutar la consulta
		public function execute() {
			return $this->stmt->execute();
		}

		# Ejecuta la consulta y retorna el id trabajado
		public function execute2() {
			$this->stmt->execute();
		    return $this->dbh->lastInsertId();
		}

		# Obtener los registros
		public function registros() {
			$this->execute();
			return $this->stmt->fetchall(PDO::FETCH_OBJ);
		}

		# Obtener un solo registro
		public function registro() {
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		}

		public function beginTransaction() {
        	return $this->dbh->beginTransaction();
    	}

		public function commit() {
			return $this->dbh->commit();
		}

		public function rollBack() {
			return $this->dbh->rollBack();
		}

	}
?>
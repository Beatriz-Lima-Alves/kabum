<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'kabum');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configurações gerais
define('SITE_URL', 'http://localhost/Kabum-original');
define('SITE_NAME', 'Sistema de Gerenciamento de Clientes');
define('DEFAULT_TIMEZONE', 'America/Sao_Paulo');

// Configurações de sessão
define('SESSION_TIMEOUT', 3600); // 1 hora em segundos

/**
 * Conexão com MySQL
 */
class Database {
    private static $instance = null;
    private $connection = null;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
                ]
            );
        } catch (PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Previne clonagem da instância
    private function __clone() {}
    
    // Previne deserialização da instância
    public function __wakeup() {}
}

class DB {
    private static $db = null;
    
    private static function getDB() {
        if (self::$db === null) {
            self::$db = Database::getInstance()->getConnection();
        }
        return self::$db;
    }
    
    /**
     * Executa uma consulta
     */
    public static function select($sql, $params = []) {
        try {
            $stmt = self::getDB()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Executa uma consulta retornando apenas um registro
     */
    public static function selectOne($sql, $params = []) {
        try {
            $stmt = self::getDB()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Executa um INSERT, UPDATE ou DELETE
     */
    public static function execute($sql, $params = []) {
        try {
            $stmt = self::getDB()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Executa INSERT e retorna o ID inserido
     */
    public static function insert($sql, $params = []) {
        try {
            $stmt = self::getDB()->prepare($sql);
            if ($stmt->execute($params)) {
                return self::getDB()->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Inicia uma transação
     */
    public static function beginTransaction() {
        return self::getDB()->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     */
    public static function commit() {
        return self::getDB()->commit();
    }
    
    /**
     * Desfaz uma transação
     */
    public static function rollback() {
        return self::getDB()->rollback();
    }
}

// Configurar timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

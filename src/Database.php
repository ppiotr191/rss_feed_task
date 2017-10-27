<?php 
namespace RSSPackage;

class Database {
    const INI_FILE = "config.ini";
    private static $instance;
    private $pdo;

    private function __construct() {
        if (!$config = parse_ini_file(self::INI_FILE)){
            throw new Exception('Unable to open ' . self::INI_FILE . '.');
        } 
        $dns = $config['driver'] .':host=' . $config['host'];
        $dns .= (!empty($config['port'])) ? ';port=' . $config['port'] : '';
        $dns .= ';dbname=' . $config['database'];
        $dns .= ';charset=utf8';

        $this->pdo = new \PDO($dns, $config['username'], $config['password']);
    }
    private function __clone() {}
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getPDO(){
        return $this->pdo;
    }
}
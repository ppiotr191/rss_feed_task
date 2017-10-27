<?php 
namespace RSSPackage;

class Log {
    const LOG_FILE = "log.txt";
    const FORMAT_DATE = "Y-m-d H:i:s";

    private static $instance;

    private function __clone() {}
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function log($info){
        file_put_contents(self::LOG_FILE, $info."\n", FILE_APPEND | LOCK_EX);
    }

    public function startScript(){
        $datetime = new \DateTime();
        self::log('[' . $datetime->format(self::FORMAT_DATE) . '] Start.');
    }

    public function record($id){
        $datetime = new \DateTime();
        self::log('[' . $datetime->format(self::FORMAT_DATE) . '] Article[id='.$id.'] was added.');
    }

    public function finishScript(){
        $datetime = new \DateTime();
        self::log('[' . $datetime->format(self::FORMAT_DATE) . '] Finish.');  
    }
}
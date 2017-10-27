<?php 
namespace RSSPackage\RSSSite;

use DateTime;
use PicoFeed\Reader\Reader;
use RSSPackage\Database;
use RSSPackage\Log;

class Site{
    const DATE_FORMAT = "Y-m-d H:i:s";
    const INSERT_FEED_SQL = "INSERT INTO rss_articles(title, published_at, content, created_at, guid) 
    VALUES(?, ?, ?, ?, ?)";
    const MAX_FEED_AMOUNT = 5;
    const SELECT_FEED_SQL = "SELECT guid FROM rss_articles WHERE guid = ?";
    private $counter = 0;

    public function downloadRSS(){
        $reader = new Reader;
        $resource = $reader->download(static::SITE);
    
        $parser = $reader->getParser(
            $resource->getUrl(),
            $resource->getContent(),
            $resource->getEncoding()
        );
    
        $feeds = $parser->execute();
        foreach ($feeds->items as $item){
            $this->saveRecord($item);
            if ($this->counter === static::MAX_FEED_AMOUNT){
                return;
            }
        }
    }
    private function saveRecord($item){
        $database = Database::getInstance();
        $statement = $database->getPDO()->prepare(self::INSERT_FEED_SQL);  
        $publishDate = new DateTime();
        $publishDate->setTimestamp($item->publishedDate->getTimestamp());
        $createDate = new DateTime();
        
        $statementSelect = $database->getPDO()->prepare(self::SELECT_FEED_SQL);
        $statementSelect->execute(array(
            $item->url
        ));

        if ($statementSelect->fetch() === FALSE){
            try { 
                $statement->execute(array(
                    $item->title, 
                    $publishDate->format(self::DATE_FORMAT),
                    $item->content,
                    $createDate->format(self::DATE_FORMAT),
                    $item->url)
                );
                Log::record($database->getPDO()->lastInsertId());
            } catch(PDOExecption $e) { 
                $dbh->rollback(); 
                Log::record('['. $createDate->format(self::DATE_FORMAT) .'] ' . $e->getMessage()); 
            } 
            
            
            $this->counter++;
        }
    }
}
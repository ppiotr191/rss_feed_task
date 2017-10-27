<?php 
namespace RSSPackage;

use RSSPackage\RSSSite\KomputerSwiat;
use RSSPackage\RSSSite\Rmf;
use RSSPackage\RSSSite\Xmoon;

class Manager {
    public function start(){
        $sites = [
            new KomputerSwiat(), 
            new Rmf(), 
            new Xmoon()
        ];
        foreach ($sites as $site){
            try{               
                $site->downloadRSS();
            }
            catch(\FeedException $e){
                echo $e->getMessage();
            }
        }
    }
}
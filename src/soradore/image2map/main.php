<?php
namespace soradore\image2map;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use soradore\image2map\item\Map;
use soradore\image2map\ImageFactory;

class main extends PluginBase implements Listener{

    function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $ev){
        $imgs = ImageFactory::cutImage(__DIR__ . "/soradore.png");
        $player = $ev->getPlayer();
        foreach($imgs as $img){
            $map = new Map();
            $map->setImage($img);
            $map->sendMap();
            $player->getInventory()->addItem($map);
        }
    }
}
  
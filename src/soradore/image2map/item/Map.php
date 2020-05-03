<?php

namespace soradore\image2map\item;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\ClientboundMapItemDataPacket;
use pocketmine\utils\Color;
use pocketmine\Server;

class Map extends Item {

    public static $map_count = 1;
    public $map_uuid;
    public $custom_tag;
    public $colors = [];
    public $image;
    public $height;
    public $width;

    public $id = Item::FILLED_MAP;
    public $meta = 0;

    public function __construct(){
        $this->map_uuid = Map::$map_count++;
        $this->custom_tag = new CompoundTag();
    }

    public function getMapId(){
        return $this->map_uuid;
    }

    public function setImage($image){
        $this->width = imagesx($image);
        $this->height = imagesy($image);
        for($x=0;$x<$this->width;$x++){
            for($y=0;$y<$this->height;$y++){
                $rgb = \imagecolorat($image, $x, $y);
				$r = ($rgb >> 16) & 0xff;
				$g = ($rgb >> 8) & 0xff;
				$b = $rgb & 0xff;
				$colors[$y][$x] = new Color($r, $g, $b);
            }
        }
        $this->setColor($colors);
        $this->image = $image;
    }

    public function setColor(array $colors){
        $this->colors = $colors;
    }

    public function sendMap(){
        $this->custom_tag->setString("map_uuid", $this->getMapId());
        $this->setCompoundTag($this->custom_tag);
        $packet = new ClientboundMapItemDataPacket();
        $packet->mapId = $this->getMapId();
        $packet->scale = 0;
        $packet->width = $this->width;
        $packet->height = $this->height;
        $packet->xOffset = $packet->yOffset = 0;
        $packet->type = ClientboundMapItemDataPacket::BITFLAG_TEXTURE_UPDATE;
        $packet->colors = $this->colors;
        Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $packet);
    }
}
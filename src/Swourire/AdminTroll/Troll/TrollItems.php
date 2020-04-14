<?php


namespace Swourire\AdminTroll\Troll;


use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Swourire\AdminTroll\Main;
use Swourire\AdminTroll\Troll\Trolls\FakeLightning;

class TrollItems
{
    private static $TrollItemsIds = [
        ItemIds::BLAZE_ROD,
        ItemIds::STICK,
        ItemIds::GLASS,
        ItemIds::END_PORTAL,
        ItemIds::FROSTED_ICE,
        ItemIds::COBWEB,
        ItemIds::SLIME_BLOCK,
        ItemIds::FIREBALL,
        ItemIds::TOTEM,
        ItemIds::PUMPKIN,
        ItemIds::SNOWBALL,
        ItemIds::SLIME_BALL
    ];

    private static $TrollItemsNames = [
        '§l§1> §r§bReal Smiter',
        '§l§1> §r§bFalse Smiter',
        '§l§1> §r§bTrapper',
        '§l§1> §r§b360°',
        '§l§1> §r§bFreezer',
        '§l§1> §r§bSlower',
        '§l§1> §r§bJumper',
        '§l§1> §r§bFake OP',
        '§l§1> §r§bPerfect Vanisher',
        '§l§1> §r§bPumpkiner',
        '§l§1> §r§bSwitcher',
        '§l§1> §r§bPuncher'
    ];

    public static function isTrollItem(Item $item): bool
    {
        return $item->getNamedTag()->hasTag("TrollItem");
    }

    public static function getTrollIdFromItem(Item $item): int
    {
        foreach (self::$TrollItemsIds as $key => $id){
            if($item->getId() === $id){
                return $key;
            }
        }

        return -1;
    }

    public static function addTrollInventory(Player $player){

        for($i = 0; isset(self::$TrollItemsIds[$i]); $i++){
            $trollItem = Item::get(self::$TrollItemsIds[$i], 0, 1);
            $trollItemNbtTag = $trollItem->getNamedTag();
            $trollItemNbtTag->setInt("TrollItem", 0, true);
            $trollItem->setNamedTag($trollItemNbtTag);

            $trollItem->setCustomName(self::$TrollItemsNames[$i]);
            if($player->getInventory()->canAddItem($trollItem)) $player->getInventory()->addItem($trollItem);
            else{
                $player->sendMessage(Main::PREFIX . TextFormat::RED . "Could not give you the full troll inventory, make some space and try again.");
                return;
            }
        }
    }
}
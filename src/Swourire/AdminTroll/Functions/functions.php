<?php

namespace Swourire\AdminTroll\Functions;

use Swourire\AdminTroll\Main;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\entity\Entity;
use pocketmine\block\Glass;
use Swourire\AdminTroll\Task\ResetTask;
use Swourire\AdminTroll\Task\threesixtyTask;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;





class Functions {
    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function addTrollInventory(Player $player){
        $smite = Item::get(369, 0, 1);
        $smite->setCustomName('§l§1> §r§bReal Smiter');
        $player->getInventory()->addItem($smite);

        $fsmite = Item::get(280, 0, 1);
        $fsmite->setCustomName('§l§1> §r§bFalse Smiter');
        $player->getInventory()->addItem($fsmite);

        $trap = Item::get(20, 0, 1);
        $trap->setCustomName('§l§1> §r§bTrapper');
        $player->getInventory()->addItem($trap); 
        
        $void = Item::get(119, 0, 1);
        $void->setCustomName('§l§1> §r§b360°');
        $player->getInventory()->addItem($void);

        $freezer = Item::get(174, 0, 1);
        $freezer->setCustomName('§l§1> §r§bFreezer');
        $player->getInventory()->addItem($freezer);

        $slower = Item::get(30, 0, 1);
        $slower->setCustomName('§l§1> §r§bSlower');
        $player->getInventory()->addItem($slower);

        $bunny = Item::get(165, 0, 1);
        $bunny->setCustomName('§l§1> §r§bJumper');
        $player->getInventory()->addItem($bunny);

        $op = Item::get(385, 0, 1);
        $op->setCustomname('§l§1> §r§bFake OP');
        $player->getInventory()->addItem($op);

        $vanish = Item::get(449, 0, 1);
        $vanish->setCustomName('§l§1> §r§bPerfect Vanisher');
        $player->getInventory()->addItem($vanish);
    }

     /**
     * @param Player $player
     */
    public function sendLightning(Player $player): void
    {

        $level = $player->getLevel();

        $lightning = new AddEntityPacket();
        $lightning->type = 93;
        $lightning->entityRuntimeId = Entity::$entityCount++;
        $lightning->metadata = [];
        $lightning->position = $player->asVector3()->add(0, $height = 0);
        $lightning->yaw = $player->getYaw();
        $lightning->pitch = $player->getPitch();
        $player->getServer()->broadcastPacket($level->getPlayers(), $lightning);

    }

    public function trapPlayer(Player $player){
        
        $pos = $player->getPosition();
        $level = $player->getLevel();
        $x = $pos->getX();
        $y = $pos->getY();
        $z = $pos->getZ();

        $b1 = new Position($x + 1, $y, $z); //block in front down
        $b2 = new Position($x + 1, $y + 1, $z); //block in front up
        $b3 = new Position($x - 1, $y + 1, $z); //block behind up
        $b4 = new Position($x - 1, $y, $z); //block behind down
        $b5 = new Position($x, $y + 2, $z); //block on top
        $b6 = new Position($x, $y, $z + 1); //block down on right
        $b7 = new Position($x, $y + 1, $z + 1); //block up on right
        $b8 = new Position($x, $y, $z - 1); //block down on left
        $b9 = new Position($x, $y + 1, $z - 1); //block up on left
        $b10 = new Position($x, $y - 1, $z); //block under

        $pos = array(
        $b1, 
        $b2, 
        $b3,
        $b4, 
        $b5, 
        $b6, 
        $b7, 
        $b8, 
        $b9, 
        $b10
        );
        $block = array(
        $level->getBlockAt($x + 1, $y, $z),
        $level->getBlockAt($x + 1, $y + 1, $z),
        $level->getBlockAt($x - 1, $y + 1, $z),
        $level->getBlockAt($x - 1, $y, $z),
        $level->getBlockAt($x, $y + 2, $z),
        $level->getBlockAt($x, $y, $z + 1), 
        $level->getBlockAt($x, $y + 1, $z + 1), 
        $level->getBlockAt($x, $y, $z - 1),
         $level->getBlockAt($x, $y + 1, $z - 1), 
         $level->getBlockAt($x, $y - 1, $z)
        );
        $this->plugin->getScheduler()->scheduleRepeatingTask(new ResetTask($this->plugin, $block, $pos, $level), 20);
        $level->setBlock($b1, new Glass(), false);
        $level->setBlock($b2, new Glass(), false);
        $level->setBlock($b3, new Glass(), false);
        $level->setBlock($b4, new Glass(), false);
        $level->setBlock($b5, new Glass(), false);
        $level->setBlock($b6, new Glass(), false);
        $level->setBlock($b7, new Glass(), false);
        $level->setBlock($b8, new Glass(), false);
        $level->setBlock($b9, new Glass(), false);
        $level->setBlock($b10, new Glass(), false);

    }

    public function threesixtyPlayer(Player $player){
        $this->plugin->player = $player;
        $this->plugin->getScheduler()->scheduleRepeatingTask(new threesixtyTask($this->plugin, $player), 20);
    }

    public function freezePlayer(Player $player){
        if($player->isImmobile()){
            $player->setImmobile(false);
        }else{
            $player->setImmobile(true);
        }
    }

    public function slowPlayer(Player $player){

        $slowness = Effect::getEffect(2);
        $instance = new EffectInstance($slowness, 2 * 20, 3, false);
        $player->addEffect($instance);

    }

    public function jumpPlayer(Player $player){

        array_push($this->plugin->toJump, $player);

    }

    public function vanishPlayer(Player $player){

        $invis = Effect::getEffect(14);
        $instance = new EffectInstance($invis, 2147483647, 3, false);
        $player->addEffect($instance);

    }

    public function fakeOpPlayer(Player $player){

        $player->sendMessage("§7You are now op!");

    }
    
    public function resetAdminMode(Player $player){

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->removeAllEffects();

    }
}

<?php
namespace Swourire\AdminTroll\Events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\player\PlayerInteractEvent;
use Swourire\AdminTroll\Main;
use pocketmine\event\player\PlayerQuitEvent;


class Events implements Listener{
    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onPlayerHit(EntityDamageByEntityEvent $event){
        $damager = $event->getDamager();

        $player = $event->getEntity();

        $item = $damager->getInventory()->getItemInHand();
        if($item->getId() === 369 && $item->getCustomName() === '§l§1> §r§bReal Smiter'){
            $this->plugin->functions->sendLightning($player);
        }
        if($item->getId() === 280 && $item->getCustomName() === '§l§1> §r§bFalse Smiter'){
            $this->plugin->functions->sendLightning($player);
            $event->setCancelled();
        }
        if($item->getId() === 20 && $item->getCustomName() === '§l§1> §r§bTrapper'){
            $this->plugin->functions->trapPlayer($player);
            $event->setCancelled();
        }
        if($item->getId() === 119 && $item->getCustomName() === '§l§1> §r§b360°'){
            $this->plugin->functions->threesixtyPlayer($player);
            $event->setCancelled();
        }
        if($item->getId() === 174 && $item->getCustomName() === '§l§1> §r§bFreezer'){
            $this->plugin->functions->freezePlayer($player);
            $event->setCancelled();
        }
        if($item->getId() === 30 && $item->getCustomName() === '§l§1> §r§bSlower'){
            $this->plugin->functions->slowPlayer($player);
            $event->setCancelled();
        }
        if($item->getId() === 165 && $item->getCustomName() === '§l§1> §r§bJumper'){
            $this->plugin->functions->jumpPlayer($player);
            $event->setCancelled();
        }

        if($item->getId() === 385 && $item->getCustomName() === '§l§1> §r§bFake OP'){
            $this->plugin->functions->fakeOpPlayer($player);
            $event->setCancelled();
        }
    }

    public function onJump(PlayerJumpEvent $event){
        $player = $event->getPlayer();
        if(in_array($player, $this->plugin->toJump)){
            $jump = Effect::getEffect(24);
        $instance = new EffectInstance($jump, 1 * 20, 50, false);
        $player->addEffect($instance);
        $key = array_search($player, $this->plugin->toJump);
        unset($this->plugin->toJump[$key]);
        }
    }

    public function onInteract(PlayerInteractEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        
        if($item->getId() === 449 && $item->getCustomName() === '§l§1> §r§bPerfect Vanisher'){
            $this->plugin->functions->vanishPlayer($player);
            $event->setCancelled();
        }
    }

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        if(in_array($player, $this->plugin->switch)){
            $key = array_search($player, $this->plugin->switch);
            unset($this->plugin->switch[$key]);
        }
        if($player->isImmobile()){
            $player->setImmobile(false);
        }
    }
}


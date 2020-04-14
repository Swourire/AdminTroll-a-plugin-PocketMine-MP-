<?php


namespace Swourire\AdminTroll\Troll;


use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\projectile\Snowball;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\Player;
use Swourire\AdminTroll\Troll\Trolls\FakeLightning;
use Swourire\AdminTroll\Troll\Trolls\FakeOp;
use Swourire\AdminTroll\Troll\Trolls\Freeze;
use Swourire\AdminTroll\Troll\Trolls\Jump;
use Swourire\AdminTroll\Troll\Trolls\Pumpkin;
use Swourire\AdminTroll\Troll\Trolls\Punch;
use Swourire\AdminTroll\Troll\Trolls\RealLightning;
use Swourire\AdminTroll\Troll\Trolls\Slow;
use Swourire\AdminTroll\Troll\Trolls\SnowSwitch;
use Swourire\AdminTroll\Troll\Trolls\ThreeSixty;
use Swourire\AdminTroll\Troll\Trolls\Trap;
use Swourire\AdminTroll\Troll\Trolls\Vanish;

class TrollEventWatcher implements Listener
{

    public static $toMakeJump = [];

    public function onPlayerHit(EntityDamageByEntityEvent $event)
    {
        $damager = $event->getDamager();
        $damaged = $event->getEntity();
        if (!$damager instanceof Player || !$damaged instanceof Player) return;

        $itemInHand = $damager->getInventory()->getItemInHand();
        if(!TrollItems::isTrollItem($itemInHand)) return;

        $troll = null;
        switch (TrollItems::getTrollIdFromItem($itemInHand)){

            case TrollBase::REAL_SMITE:
                $troll = new RealLightning($damager, $damaged);
                break;
            case TrollBase::FAKE_SMITE:
                $troll = new FakeLightning($damager, $damaged);
                break;
            case TrollBase::TRAP:
                $troll = new Trap($damager, $damaged);
                break;
            case TrollBase::THREESIXTY:
                $troll =  new ThreeSixty($damager, $damaged);
                break;
            case TrollBase::FREEZE:
                $troll =  new Freeze($damager, $damaged);
                break;
            case TrollBase::SLOW:
                $troll =  new Slow($damager, $damaged);
                break;
            case TrollBase::JUMP:
                $troll =  new Jump($damager, $damaged);
                break;
            case TrollBase::FAKE_OP:
                $troll =  new FakeOp($damager, $damaged);
                break;
            case TrollBase::PUMPKIN:
                $troll =  new Pumpkin($damager, $damaged);
                break;
            case TrollBase::PUNCH:
                $troll =  new Punch($damager, $damaged);
                $event->setKnockBack(6);
                break;
        }

        if($troll instanceof TrollBase){
            $troll->apply();
            $event->setCancelled($troll->needsCancel());
        }
    }

    public function onJump(PlayerJumpEvent $event)
    {
        $player = $event->getPlayer();
        if(in_array($player->getName(), self::$toMakeJump)){
            $jump = Effect::getEffect(Effect::LEVITATION);
            $instance = new EffectInstance($jump, 1 * 20, 50, false);
            $player->addEffect($instance);
            $key = array_search($player->getName(), self::$toMakeJump);
            unset(self::$toMakeJump[$key]);
        }
    }

    public function onProjectileHit(ProjectileHitEntityEvent $event): void
    {
        $projectile = $event->getEntity();
        $target = $event->getEntityHit();
        if ($projectile instanceof Snowball && $target instanceof Player){
            $sender = $projectile->getOwningEntity();
            if($sender instanceof Player){
                $troll = new SnowSwitch($sender, $target);
                $troll->apply();
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if(TrollItems::isTrollItem($item)){
            if(TrollItems::getTrollIdFromItem($item) === TrollBase::VANISH){
                (new Vanish($player, $player))->apply();
            }
        }
    }
}
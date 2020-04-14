<?php

namespace Swourire\AdminTroll;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use Swourire\AdminTroll\Troll\TrollBase;
use Swourire\AdminTroll\Troll\TrollEventWatcher;
use Swourire\AdminTroll\Troll\TrollItems;
use Swourire\AdminTroll\Troll\Trolls\FakeLightning;
use Swourire\AdminTroll\Troll\Trolls\FakeOp;
use Swourire\AdminTroll\Troll\Trolls\Freeze;
use Swourire\AdminTroll\Troll\Trolls\Jump;
use Swourire\AdminTroll\Troll\Trolls\Pumpkin;
use Swourire\AdminTroll\Troll\Trolls\RealLightning;
use Swourire\AdminTroll\Troll\Trolls\Slow;
use Swourire\AdminTroll\Troll\Trolls\SnowSwitch;
use Swourire\AdminTroll\Troll\Trolls\ThreeSixty;
use Swourire\AdminTroll\Troll\Trolls\Trap;
use Swourire\AdminTroll\Troll\Trolls\Vanish;

class Main extends PluginBase implements Listener
{
    const PREFIX = "[§4Admin Troll§f] §e> §r";
    const TROLLS = [
        "fakesmite <player>",
        "smite <player>",
        "fakeop <player>",
        "freeze <player>",
        "jump <player>",
        "pumpkin <player>",
        "slow <player>",
        "switch <player> (<player> default will make you switch with the player)",
        "threesixty <player> (<amplifier> default will make him turn but slowly)",
        "traper <player> (<time> in seconds, by default 10 seconds)",
        "vanish"
    ];

    private static $Instance;

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new TrollEventWatcher(), $this);

        self::$Instance = $this;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if($sender instanceof Player){
            switch($command->getName()){
                case "admintroll":
                    TrollItems::addTrollInventory($sender);
                    break;
                case "troll":
                    if (isset($args[0])) {
                            if(isset($args[1])){
                                $playerVictim = Server::getInstance()->getPlayer($args[1]);
                                if($playerVictim instanceof Player) {

                                    $troll = null;

                                    switch ($args[0]){
                                        case "fakesmite":
                                            $troll = new FakeLightning($sender, $playerVictim);
                                            break;
                                        case "smite":
                                            $troll = new RealLightning($sender, $playerVictim);
                                            break;
                                        case "fakeop":
                                            $troll = new FakeOp($sender, $playerVictim);
                                            break;
                                        case "freeze":
                                            $troll = new Freeze($sender, $playerVictim);
                                            break;
                                        case "jump":
                                            $troll = new Jump($sender, $playerVictim);
                                            break;
                                        case "pumpkin":
                                            $troll = new Pumpkin($sender, $playerVictim);
                                            break;
                                        case "slow":
                                            $troll = new Slow($sender, $playerVictim);
                                            break;
                                        case "switch":
                                            if(isset($args[2])){
                                                $playerVictim2 = Server::getInstance()->getPlayer($args[2]);
                                                if($playerVictim2 instanceof Player){
                                                    $troll = new SnowSwitch($playerVictim, $playerVictim2);
                                                }else $sender->sendMessage(self::PREFIX . "§cSecond Player not found.");
                                            }else $troll = new SnowSwitch($sender, $playerVictim);
                                            break;
                                        case "threesixty":
                                            if(isset($args[2])){
                                                $troll = new ThreeSixty($sender, $playerVictim, intval($args[2]));
                                            }else $troll = new ThreeSixty($sender, $playerVictim);
                                            break;
                                        case "traper":
                                            if(isset($args[2])){
                                                $troll = new Trap($sender, $playerVictim, intval($args[2]));
                                            }else $troll = new Trap($sender, $playerVictim);
                                            break;
                                        default:
                                            $sender->sendMessage(self::PREFIX . "§cTroll not found.");
                                        }

                                    if($troll instanceof TrollBase){
                                        $troll->apply();
                                    }

                                }else $sender->sendMessage(self::PREFIX . "§cFirst Player not found.");

                            } else {
                                if($args[0] === "vanish") {
                                    (new Vanish($sender, $sender))->apply();
                                    return false;
                                }
                                $this->sendUsage($sender);
                            }
                    } else {
                        $this->sendUsage($sender);
                    }
                    break;
            }
        }
        return false;
    }

    private function sendUsage(Player $sender): void
    {
        $sender->sendMessage(self::PREFIX . "§eList of available trolls and their usages: ");
        foreach (self::TROLLS as $key => $trollName){
            $sender->sendMessage("  §e-> §9{$trollName}");
        }
    }

    public static function getInstance(): self
    {
        return self::$Instance;
    }
}
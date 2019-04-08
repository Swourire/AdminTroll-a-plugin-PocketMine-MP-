<?php

namespace Swourire\AdminTroll;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use Swourire\AdminTroll\Events\Events;
use pocketmine\Player;
use Swourire\AdminTroll\Functions\functions;
use pocketmine\utils\Config;





class Main extends PluginBase implements Listener{

	public $switch = [];
	public $toJump = [];
	const PREFIX = "[§4Admin Troll§f]";
	const RESET_TIME = 10;

		public function onEnable() : void{
			$this->getServer()->getPluginManager()->registerEvents(new Events($this), $this);
			$this->functions = new functions($this);
		}

		public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{				
				
			if($sender instanceof Player){

				if(in_array($sender, $this->switch)){
						$sender->sendMessage(self::PREFIX ."§c OFF");

						$key = array_search($sender, $this->switch);
						unset($this->switch[$key]);
						
						$this->functions->resetAdminMode($sender);

				}else{

						$sender->sendMessage(self::PREFIX ."§a ON");

						$this->functions->resetAdminMode($sender);
						$this->functions->addTrollInventory($sender);

						array_push($this->switch, $sender);	

				}
			}else{
					$this->getLogger()->info( self::PREFIX ." You can't send this command from console !");
			}					
				return true;
		}	
	}
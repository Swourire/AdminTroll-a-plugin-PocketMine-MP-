<?php

namespace Swourire\AdminTroll\Task;

use Swourire\AdminTroll\Main;
use pocketmine\scheduler\Task;
use pocketmine\Player;

class threesixtyTask extends Task
{
    public $yaw = 0;

    private $plugin;

    private $player;

    public function __construct(Main $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun(int $tick)
    {
        $yaw = $this->player->getYaw();

        while ($this->yaw < 360) {
            $this->player->teleport($this->player->getPosition(), $this->yaw++);
        }

        if ($this->yaw === 360) {
            $this->yaw = 0;
            $this->getHandler()->cancel();
        }
    }
}

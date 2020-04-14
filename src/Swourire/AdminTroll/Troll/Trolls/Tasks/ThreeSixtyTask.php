<?php


namespace Swourire\AdminTroll\Troll\Trolls\Tasks;


use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;
use pocketmine\Player;

class ThreeSixtyTask extends Task
{

    private $player;

    private $playerYaw;

    private $multiplier;

    private $count = 0;

    public function __construct(Player $player, int $multiplier)
    {
        $this->multiplier = $multiplier;
        $this->player = $player;
        $this->playerYaw = $player->getYaw();
    }

    public function onRun(int $currentTick)
    {
        $player = $this->player;
        if($this->count < 360){
            $this->playerYaw += 3 * $this->multiplier;
            $this->count += 3 * $this->multiplier;
            $player->teleport($player->asVector3(), $this->playerYaw);
        }else{
            $this->getHandler()->cancel();
        }
    }
}
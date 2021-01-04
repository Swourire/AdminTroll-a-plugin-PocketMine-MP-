<?php


namespace Swourire\AdminTroll\Troll\Trolls;


use pocketmine\Player;
use Swourire\AdminTroll\Main;
use Swourire\AdminTroll\Troll\TrollBase;
use Swourire\AdminTroll\Troll\Trolls\Tasks\ThreeSixtyTask;

class ThreeSixty extends TrollBase
{
    protected $name = "Three Sixty";
    private $multiplier;

    public function __construct(Player $playerTroll, Player $playerVictim, int $multiplier = 1)
    {
        $this->multiplier = $multiplier;
        parent::__construct($playerTroll, $playerVictim);
    }

    public function apply(): void
    {
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ThreeSixtyTask($this->playerVictim, $this->multiplier), 1);
        parent::apply(); // TODO: Change the autogenerated stub
    }
}
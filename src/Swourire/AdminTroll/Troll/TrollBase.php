<?php


namespace Swourire\AdminTroll\Troll;


use pocketmine\Player;
use Swourire\AdminTroll\Main;

abstract class TrollBase
{
    const REAL_SMITE = 0;
    const FAKE_SMITE = 1;
    const TRAP = 2;
    const THREESIXTY = 3;
    const FREEZE = 4;
    const SLOW = 5;
    const JUMP = 6;
    const FAKE_OP = 7;
    const VANISH = 8;
    const PUMPKIN = 9;
    const SWITCH = 10;
    const PUNCH = 11;

    protected $name;

    protected $playerVictim;
    protected $playerTroll;

    public function getName(): string { return $this->name; }

    public function needsCancel(): bool { return true; }

    public function apply(): void
    {
        $this->playerTroll->sendMessage(Main::PREFIX . "Executed successfully troll: {$this->name}.");
    }

    public function __construct(Player $playerTroll, Player $playerVictim)
    {
        $this->playerVictim = $playerVictim;
        $this->playerTroll = $playerTroll;
    }
}
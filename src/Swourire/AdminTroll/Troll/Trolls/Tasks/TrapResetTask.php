<?php

namespace Swourire\AdminTroll\Troll\Trolls\Tasks;

use pocketmine\block\Block;
use pocketmine\scheduler\Task;

class TrapResetTask extends Task
{
    private $blocks;

    public function __construct(array $blocks)
    {
        $this->blocks = $blocks;
    }

    public function onRun(int $currentTick)
    {
        if(empty($this->blocks)) return;
        foreach ($this->blocks as $key => $block){
            if($block instanceof Block){
                $level = $block->getLevel();
                $level->setBlock($block->asVector3(), $block);
            }
        }
    }
}
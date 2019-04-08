<?php
namespace Swourire\AdminTroll\Task;

use Swourire\AdminTroll\Main;
use pocketmine\scheduler\Task;
use pocketmine\level\Level;


class ResetTask extends Task{
    public $time = Main::RESET_TIME;

    private $plugin;

   
    public $blocks;
    public $level;
    public $pos;

    public function __construct(Main $plugin, array $blocks, array $pos, Level $level){
        $this->plugin = $plugin;
        $this->blocks = $blocks;
        $this->pos = $pos;
        $this->level = $level;        
    }

    public function onRun(int $tick){
       
    --$this->time;
        if($this->time === 0){          
            while(!empty($this->pos) && !empty($this->blocks)){
                        $this->level->setBlock(array_shift($this->pos), array_shift($this->blocks), false);                                   
            }
            if(empty($this->pos) && empty($this->blocks)){ 
                    $this->time = Main::RESET_TIME;
                    $this->getHandler()->cancel();
            }
        }
    }
}

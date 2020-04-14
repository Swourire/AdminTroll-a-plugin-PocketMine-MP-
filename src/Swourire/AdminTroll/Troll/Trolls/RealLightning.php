<?php


namespace Swourire\AdminTroll\Troll\Trolls;


use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use Swourire\AdminTroll\Troll\TrollBase;

class RealLightning extends TrollBase
{
    protected $name = "Smite";
    public function apply(): void
    {
        $this->sendLightning();
        parent::apply();
    }

    private function sendLightning(): void
    {
        $player = $this->playerVictim;
        $level = $player->getLevel();

        $lightning = new AddActorPacket();
        $lightning->type = Entity::LIGHTNING_BOLT;
        $lightning->entityRuntimeId = Entity::$entityCount++;
        $lightning->metadata = [];
        $lightning->position = $player->asPosition();
        $lightning->yaw = 0;
        $lightning->pitch = 0;

        $player->getServer()->broadcastPacket($level->getPlayers(), $lightning);
    }

    public function needsCancel(): bool
    {
        return false;
    }
}
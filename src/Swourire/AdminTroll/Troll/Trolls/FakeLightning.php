<?php

namespace Swourire\AdminTroll\Troll\Trolls;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use Swourire\AdminTroll\Troll\TrollBase;

class FakeLightning extends TrollBase
{
    protected $name = "FakeSmite";
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
	$lightning->type = "minecraft:lightning_bolt";
	$lightning->entityRuntimeId = Entity::$entityCount++;
	$lightning->metadata = [];
	$lightning->motion = null;
	$lightning->yaw = $player->getYaw();
	$lightning->pitch = $player->getPitch();
	$lightning->position = new Vector3($player->getX(), $player->getY(), $player->getZ());
        $player->getServer()->broadcastPacket($level->getPlayers(), $lightning);
    }
}

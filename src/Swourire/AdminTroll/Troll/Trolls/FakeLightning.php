<?php

namespace Swourire\AdminTroll\Troll\Trolls;

use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\math\Vector3;
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
    
        $block = $player->getLevel()->getBlock($player->getPosition()->floor()->down());
        $particle = new DestroyBlockParticle(new Vector3($player->getX(), $player->getY(), $player->getZ()), $block);
	$player->getLevel()->addParticle($particle);

        $sound = new PlaySoundPacket();
	$sound->soundName = "ambient.weather.thunder";
	$sound->x = $player->getX();
	$sound->y = $player->getY();
	$sound->z = $player->getZ();
	$sound->volume = 1;
	$sound->pitch = 1;
        $player->getServer()->broadcastPacket($level->getPlayers(), $sound);
    }
}

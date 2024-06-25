<?php
namespace Nepheliashop\Clearlagg;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Human;
use pocketmine\entity\object\ItemEntity;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class Main extends PluginBase {

    const DEFAULT_TIME                 = 120;
    protected int $defaultTime;
    protected int $time;
    protected array $worlds            = [];
    protected array $broadcastTimes    = [];
    protected array $sound             = [];
    protected string $broadcastMessage = "Clearlagg dans §6{s} §fseconde(s)";
    protected string $finishMessage    = "Clearlag §6{e} §fEntités clear";
    protected bool $clearEntities      = true;

    protected function onLoad(): void
    {
        $this->saveDefaultConfig();
        $this->defaultTime = $this->time = (int)$this->getConfig()->getNested('parameters.time', self::DEFAULT_TIME);
        $this->worlds = (array)$this->getConfig()->getNested('parameters.worlds', ['world']);
        $this->broadcastTimes = (array)$this->getConfig()->getNested('parameters.broadcast.times', [60, 30, 15, 10, 3, 2, 1]);
        $this->sound = (array)$this->getConfig()->getNested('parameters.sound', [
            "enabled" => true,
            "name"    => "random.levelup",
            "volume"  => 0.5,
            "pitch"   => 1,
        ]);
        $this->broadcastMessage = (string)$this->getConfig()->getNested('parameters.broadcast.message');
        $this->finishMessage = (string)$this->getConfig()->getNested('parameters.broadcast.finish');
        $this->clearEntities = (bool)$this->getConfig()->getNested('parameters.clear_entities', true);
    }

    protected function onEnable(): void
    {
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (){
            if($this->time <= 0) {
                $this->clearLagg();
            } elseif(in_array($this->time, $this->broadcastTimes)) {
                $this->playSound();
                Server::getInstance()->broadcastMessage(str_replace("{s}", (string) $this->time, $this->broadcastMessage));
            }
            $this->time--;
        }), 20);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "clearlag"){
            $this->clearLagg();
        }
        return true;
    }

    protected function clearLagg() : void
    {
        $close = 0;
        foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            if (in_array($world->getFolderName(), $this->worlds)){
                foreach ($world->getEntities() as $entity){
                    if ($entity instanceof Human) continue;
                    if ($entity instanceof ItemEntity){
                        $close += $entity->getItem()->getCount();
                        $entity->flagForDespawn();
                    } else $close++;
                    if ($this->clearEntities){
                        $close++;
                        $entity->flagForDespawn();
                    }
                }
            }
        }
        $this->playSound();
        Server::getInstance()->broadcastMessage(str_replace("{e}", (string)$close, $this->finishMessage));
        $this->time = $this->defaultTime;
    }

    protected function playSound() : void
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if ($this->sound['enabled']){
                $sound = PlaySoundPacket::create($this->sound['name'], $player->getPosition()->getX(), $player->getPosition()->getY(), $player->getPosition()->getZ(), (float)$this->sound['volume'], (float)$this->sound['pitch']);
                $player->getNetworkSession()->sendDataPacket($sound);
            }
        }
    }

}
<?php

declare(strict_types=1);

namespace matze\letittree;

use matze\letittree\command\LetItTreeCommand;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\EventPriority;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class LetItTree extends PluginBase {
    private static self $instance;

    protected function onEnable(): void{
        self::$instance = $this;

        Server::getInstance()->getCommandMap()->register($this->getName(), new LetItTreeCommand());

        //TODO: Remove after development phase
        Server::getInstance()->getPluginManager()->registerEvent(LeavesDecayEvent::class, function(LeavesDecayEvent $event): void {
            $event->cancel();
        }, EventPriority::LOWEST, $this);
    }

    public static function getInstance(): LetItTree{
        return self::$instance;
    }
}
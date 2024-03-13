<?php

declare(strict_types=1);

namespace matze\letittree\command;

use matze\letittree\form\LetItTreeForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LetItTreeCommand extends Command {
    public function __construct(){
        parent::__construct("letittree", "Place trees directly", null, ["lit"]);
        $this->setPermission("command.letittree.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if(!$sender instanceof Player || !$this->testPermission($sender)) {
            return;
        }
        LetItTreeForm::open($sender);
    }
}
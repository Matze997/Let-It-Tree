<?php

declare(strict_types=1);

namespace matze\letittree\form;

use jojoe77777\FormAPI\SimpleForm;
use matze\letittree\tree\TreeType;
use pocketmine\player\Player;
use pocketmine\utils\Random;

class LetItTreeForm {
    public static function open(Player $player): void {
        $form = new SimpleForm();
        $form->setTitle("Let it Tree");
        foreach(TreeType::getAll() as $id => $type) {
            $form->addButton(ucwords(str_replace("_", " ", $id)), 0, "", function(Player $player) use ($type): void {
                $location = $player->getLocation();
                $transaction = $type->getBlockTransaction($player->getWorld(), $location->getFloorX(), $location->getFloorY(), $location->getFloorZ(), new Random());
                if($transaction === null) {
                    $player->sendMessage("Can not place tree here!");
                    return;
                }
                if(!$transaction->apply()) {
                    $player->sendMessage("Could not apply transaction!");
                } else {
                    $player->sendMessage("Tree placed - Let it tree!");
                }
            });
        }
        $form->sendToPlayer($player);
    }
}
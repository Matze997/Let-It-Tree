<?php

declare(strict_types=1);

namespace matze\letittree\tree;

use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\generator\object\Tree;

class BigTree extends Tree {
    protected function placeTrunk(int $x, int $y, int $z, Random $random, int $trunkHeight, BlockTransaction $transaction): void{
        $transaction->addBlockAt($x, $y - 1, $z, VanillaBlocks::DIRT());
        $transaction->addBlockAt($x, $y - 1, $z + 1, VanillaBlocks::DIRT());
        $transaction->addBlockAt($x + 1, $y - 1, $z + 1, VanillaBlocks::DIRT());
        $transaction->addBlockAt($x + 1, $y - 1, $z, VanillaBlocks::DIRT());

        for($yy = 0; $yy < $trunkHeight; ++$yy){
            if($this->canOverride($transaction->fetchBlockAt($x, $y + $yy, $z))){
                $transaction->addBlockAt($x, $y + $yy, $z, $this->trunkBlock);
            }
            if($this->canOverride($transaction->fetchBlockAt($x, $y + $yy, $z + 1))){
                $transaction->addBlockAt($x, $y + $yy, $z + 1, $this->trunkBlock);
            }
            if($this->canOverride($transaction->fetchBlockAt($x + 1, $y + $yy, $z + 1))){
                $transaction->addBlockAt($x + 1, $y + $yy, $z + 1, $this->trunkBlock);
            }
            if($this->canOverride($transaction->fetchBlockAt($x + 1, $y + $yy, $z))){
                $transaction->addBlockAt($x + 1, $y + $yy, $z, $this->trunkBlock);
            }
        }
    }
}
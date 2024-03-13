<?php

declare(strict_types=1);

namespace matze\letittree\tree\type\spruce;

use pocketmine\block\VanillaBlocks;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\Tree;

class ThinPine extends Tree {
    public function __construct(){
        parent::__construct(VanillaBlocks::SPRUCE_LOG(), VanillaBlocks::SPRUCE_LEAVES());
    }

    public function canPlaceObject(ChunkManager $world, int $x, int $y, int $z, Random $random): bool{
        $this->treeHeight = $random->nextRange(8, 13);
        return parent::canPlaceObject($world, $x, $y, $z, $random);
    }

    protected function placeCanopy(int $x, int $y, int $z, Random $random, BlockTransaction $transaction): void{
        $baseY = $y + $this->generateTrunkHeight($random);

        if($this->canOverride($transaction->fetchBlockAt($x, $baseY, $z))) {
            $transaction->addBlockAt($x, $baseY, $z, $this->leafBlock);
        }

        $pineHeight = $random->nextRange(2, 4);
        $pineRadius = $random->nextRange(1, 3);

        $this->placeCanopyLayer($x, $baseY - $pineHeight, $z, max(1, $pineRadius - 1), $random, $transaction);

        $radius = 1;
        for($yy = 1; $yy < $pineHeight; $yy++) {
            $this->placeCanopyLayer($x, $baseY - $yy, $z, $radius, $random, $transaction);
            if($radius < $pineRadius) {
                $radius++;
            }
        }
    }

    protected function placeCanopyLayer(int $x, int $y, int $z, int $radius, Random $random, BlockTransaction $transaction): void {
        for($xx = -$radius; $xx <= $radius; $xx++) {
            for($zz = -$radius; $zz <= $radius; $zz++) {
                if(abs($xx) === $radius && abs($zz) === $radius) {
                    continue;
                }
                if($this->canOverride($transaction->fetchBlockAt($x + $xx, $y, $z + $zz))) {
                    $transaction->addBlockAt($x + $xx, $y, $z + $zz, $this->leafBlock);
                }
            }
        }
    }
}
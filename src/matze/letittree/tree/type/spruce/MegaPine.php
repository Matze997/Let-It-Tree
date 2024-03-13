<?php

declare(strict_types=1);

namespace matze\letittree\tree\type\spruce;

use matze\letittree\tree\BigTree;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector2;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;

class MegaPine extends BigTree {
    public function __construct(){
        parent::__construct(VanillaBlocks::SPRUCE_LOG(), VanillaBlocks::SPRUCE_LEAVES());
    }

    public function canPlaceObject(ChunkManager $world, int $x, int $y, int $z, Random $random): bool{
        $this->treeHeight = $random->nextRange(13, 31);
        return parent::canPlaceObject($world, $x, $y, $z, $random);
    }

    protected function placeCanopy(int $x, int $y, int $z, Random $random, BlockTransaction $transaction): void{
        $baseY = $this->generateTrunkHeight($random) + $y;

        $this->placeCanopyLayer($x, $baseY, $z, 1, $random, $transaction);

        $leavesHeight = $random->nextRange(5, 8);
        //TODO
    }

    protected function placeCanopyLayer(int $x, int $y, int $z, int $radius, Random $random, BlockTransaction $transaction): void {
        $center = new Vector2($x + 0.5, $z + 0.5);
        for($xx = -$radius; $xx <= $radius; $xx++) {
            for($zz = -$radius; $zz <= $radius; $zz++) {
                if($center->distance(new Vector2($x + $xx, $z + $zz)) > $radius) {
                    continue;
                }
                if($this->canOverride($transaction->fetchBlockAt($x + $xx, $y, $z + $zz))) {
                    $transaction->addBlockAt($x + $xx, $y, $z + $zz, $this->leafBlock);
                }
            }
        }
    }
}
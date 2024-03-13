<?php

declare(strict_types=1);

namespace matze\letittree\tree\type\oak;

use matze\letittree\util\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\Wood;
use pocketmine\math\Facing;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\Tree;

class FancyOak extends Tree {
    public const MIN_HEIGHT = 4;
    public const MAX_HEIGHT = 14;

    public function __construct(){
        parent::__construct(VanillaBlocks::OAK_LOG(), VanillaBlocks::OAK_LEAVES());
    }

    public function canPlaceObject(ChunkManager $world, int $x, int $y, int $z, Random $random): bool{
        for($yy = 0; $yy <= $this::MAX_HEIGHT; $yy++) {
            if(!$this->canOverride($world->getBlockAt($x, $y + $yy, $z))) {
                if($yy <= ($this::MIN_HEIGHT + 1)) {
                    return false;
                }
                break;
            }
        }
        $this->treeHeight = $yy;
        return true;
    }

    protected function generateTrunkHeight(Random $random): int{
        return (int)round($this->treeHeight * 0.618);
    }

    public function getBlockTransaction(ChunkManager $world, int $x, int $y, int $z, Random $random): ?BlockTransaction{
        if(!$this->canPlaceObject($world, $x, $y, $z, $random)){
            return null;
        }

        $transaction = new BlockTransaction($world);
        $transaction->addBlockAt($x, $y - 1, $z, VanillaBlocks::DIRT());

        $trunkHeight = $this->generateTrunkHeight($random);
        for($yy = 0; $yy < $trunkHeight; ++$yy){
            if($this->canOverride($transaction->fetchBlockAt($x, $y + $yy, $z))){
                $transaction->addBlockAt($x, $y + $yy, $z, $this->trunkBlock);
            }
        }

        $this->placeCanopy($x, $trunkHeight, $z, $random, $transaction);

        $canopies = $random->nextRange(0, $trunkHeight - $this::MIN_HEIGHT);
        for($cc = 0; $cc <= $canopies; $cc++) {
            $canopyY = $random->nextRange($this::MIN_HEIGHT, $trunkHeight - 5);
            $canopyTrunkY = max($canopyY - $random->nextBoundedInt(3), $y + $this::MIN_HEIGHT);
            if($canopyY > $trunkHeight || $canopyTrunkY > $trunkHeight) {
                continue;
            }

            $canopyX = 0;
            $canopyZ = 0;
            while($canopyX === 0 && $canopyZ === 0) {
                $canopyX = $random->nextRange(-3, 3);
                $canopyZ = $random->nextRange(-3, 3);
            }
            $this->placeCanopy($x + $canopyX, $y + $canopyY, $z + $canopyZ, $random, $transaction);

            $block = $world->getBlockAt($x + $canopyX, $y + $canopyY, $z + $canopyZ);
            if($this->canOverride($block)) {
                foreach(Utils::getPositionsBetween(new Vector3($x + $canopyX, $y + $canopyY, $z + $canopyZ), new Vector3($x, $canopyTrunkY, $z)) as $vector3) {
                    $trunk = clone $this->trunkBlock;
                    if($this->canOverride($transaction->fetchBlock($vector3))) {
                        $transaction->addBlock($vector3, $trunk->setAxis(Utils::calculateAxis(new Vector3($x, $y, $z), $vector3)));
                    }
                }
            }
        }

        return $transaction;
    }

    public function placeCanopy(int $x, int $y, int $z, Random $random, BlockTransaction $transaction): void{
        $vector3 = new Vector3($x, $y, $z);
        foreach(Facing::HORIZONTAL as $facing) {
            $side = $vector3->getSide($facing);
            if($this->canOverride($transaction->fetchBlock($side))) {
                $transaction->addBlock($side, $this->leafBlock);
            }
            $sideUp = $side->up(4);
            $block = $transaction->fetchBlock($sideUp);
            if($block instanceof Wood || $this->canOverride($block)) {
                $transaction->addBlock($sideUp, $this->leafBlock);
            }
        }

        for($yy = 1; $yy <= 3; $yy++) {
            for($xx = -2; $xx <= 2; $xx++) {
                for($zz = -2; $zz <= 2; $zz++) {
                    if(abs($xx) === 2 && abs($zz) === 2) {
                        continue;
                    }
                    $block = $transaction->fetchBlockAt($x + $xx, $y + $yy, $z + $zz);
                    if($this->canOverride($block)) {
                        $transaction->addBlockAt($x + $xx, $y + $yy, $z + $zz, $this->leafBlock);
                    }
                }
            }
        }
        if($this->canOverride($transaction->fetchBlockAt($x, $y + 4, $z))) {
            $transaction->addBlockAt($x, $y + 4, $z, $this->leafBlock);
        }
    }
}
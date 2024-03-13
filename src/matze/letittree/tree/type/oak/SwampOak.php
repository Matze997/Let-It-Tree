<?php

declare(strict_types=1);

namespace matze\letittree\tree\type\oak;

use pocketmine\block\VanillaBlocks;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\object\Tree;

class SwampOak extends Tree {
    public function __construct(){
        parent::__construct(VanillaBlocks::OAK_LOG(), VanillaBlocks::OAK_LEAVES());
    }

    public function getBlockTransaction(ChunkManager $world, int $x, int $y, int $z, Random $random): ?BlockTransaction{
        $this->treeHeight = $random->nextRange(6, 8);
        $transaction = parent::getBlockTransaction($world, $x, $y, $z, $random);
        if($transaction === null) {
            return null;
        }
        $this->placeVines($x, $y, $z, $random, $transaction);
        return $transaction;
    }

    protected function placeVines(int $x, int $y, int $z, Random $random, BlockTransaction $transaction): void {
        $baseY = $y + $this->treeHeight;

        $air = VanillaBlocks::AIR();

        // Probably not the smartest and fastest way to place vines but idc atm
        for($xx = -3; $xx <= 3; $xx++) {
            for($zz = -3; $zz <= 3; $zz++) {
                for($yy = -1; $yy <= 0; $yy++) {
                    if($random->nextBoundedInt(2) !== 0 || !$transaction->fetchBlockAt($x + $xx, $baseY + $yy, $z + $zz)->isSameState($air)) {
                        continue;
                    }
                    $vector3 = new Vector3($x + $xx, $baseY + $yy, $z + $zz);
                    $sides = [];
                    foreach(Facing::HORIZONTAL as $facing) {
                        $side = $vector3->getSide($facing);
                        if(!$transaction->fetchBlock($side)->isSameState($this->leafBlock)) {
                            continue;
                        }
                        $sides[] = $facing;
                    }
                    if(count($sides) > 0) {
                        $transaction->addBlock($vector3, VanillaBlocks::VINES()->setFaces($sides));
                    }
                }
            }
        }

        for($xx = -4; $xx <= 4; $xx++) {
            for($zz = -4; $zz <= 4; $zz++) {
                for($yy = -3; $yy <= 2; $yy++) {
                    if($random->nextBoundedInt(3) !== 0 || !$transaction->fetchBlockAt($x + $xx, $baseY + $yy, $z + $zz)->isSameState($air)) {
                        continue;
                    }
                    //Same code as above but is it worth writing a method for it?... idk
                    $vector3 = new Vector3($x + $xx, $baseY + $yy, $z + $zz);
                    $sides = [];
                    foreach(Facing::HORIZONTAL as $facing) {
                        $side = $vector3->getSide($facing);
                        if(!$transaction->fetchBlock($side)->isSameState($this->leafBlock)) {
                            continue;
                        }
                        $sides[] = $facing;
                    }
                    if(count($sides) > 0) {
                        $vines = VanillaBlocks::VINES()->setFaces($sides);
                        $transaction->addBlock($vector3, $vines);
                        for($yyy = $vector3->getFloorY(); $yyy >= $y; $yyy--) {
                            if($transaction->fetchBlockAt($vector3->getX(), $yyy, $vector3->getZ())->isSameState($air)) {
                                $transaction->addBlockAt($vector3->getX(), $yyy, $vector3->getZ(), $vines);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function placeCanopy(int $x, int $y, int $z, Random $random, BlockTransaction $transaction): void{
        $baseY = $y + $this->treeHeight;
        for($xx = -2; $xx <= 2; $xx++) {
            for($zz = -2; $zz <= 2; $zz++) {
                for($yy = -1; $yy <= 0; $yy++) {
                    if(abs($xx) === 2 && abs($zz) === 2) {
                        if($yy === -1 && $random->nextBoundedInt(1) === 0 && $this->canOverride($transaction->fetchBlock(new Vector3($x + $xx, $baseY + $yy, $z + $zz)))){
                            $transaction->addBlockAt($x + $xx, $baseY + $yy, $z + $zz, $this->leafBlock);
                        }
                    } elseif($this->canOverride($transaction->fetchBlock(new Vector3($x + $xx, $baseY + $yy, $z + $zz)))) {
                        $transaction->addBlockAt($x + $xx, $baseY + $yy, $z + $zz, $this->leafBlock);
                    }
                }
            }
        }

        for($xx = -3; $xx <= 3; $xx++) {
            for($zz = -3; $zz <= 3; $zz++) {
                for($yy = -3; $yy <= -2; $yy++) {
                    if(abs($xx) === 3 && abs($zz) === 3) {
                        if($random->nextBoundedInt(3) === 0 && $this->canOverride($transaction->fetchBlock(new Vector3($x + $xx, $baseY + $yy, $z + $zz)))){
                            $transaction->addBlockAt($x + $xx, $baseY + $yy, $z + $zz, $this->leafBlock);
                        }
                    } elseif($this->canOverride($transaction->fetchBlock(new Vector3($x + $xx, $baseY + $yy, $z + $zz)))) {
                        $transaction->addBlockAt($x + $xx, $baseY + $yy, $z + $zz, $this->leafBlock);
                    }
                }
            }
        }
    }
}
<?php

declare(strict_types=1);

namespace matze\letittree\util;

use pocketmine\math\Axis;
use pocketmine\math\Vector3;

class Utils {
    public static function calculateAxis(Vector3 $v1, Vector3 $v2): int {
        $xDiff = abs($v1->x - $v2->x);
        $zDiff = abs($v1->z - $v2->z);
        if($xDiff > $zDiff) {
            return Axis::X;
        }
        return Axis::Z;
    }

    /**
     * @return Vector3[]
     */
    public static function getPositionsBetween(Vector3 $v1, Vector3 $v2): array {
        $distance = $v1->distance($v2);
        if($distance <= 1) {
            return [];
        }

        $dx = ($v2->getX() - $v1->getX()) / $distance;
        $dy = ($v2->getY() - $v1->getY()) / $distance;
        $dz = ($v2->getZ() - $v1->getZ()) / $distance;

        $positions = [];

        $position = clone $v1;
        while($position->distance($v2) > 1) {
            $position->x += $dx;
            $position->y += $dy;
            $position->z += $dz;

            $positions[] = clone $position;
        }
        return $positions;
    }
}
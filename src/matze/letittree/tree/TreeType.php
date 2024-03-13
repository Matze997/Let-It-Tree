<?php

declare(strict_types=1);

namespace matze\letittree\tree;

use matze\letittree\tree\type\oak\FancyOak;
use matze\letittree\tree\type\oak\SwampOak;
use matze\letittree\tree\type\spruce\MegaPine;
use matze\letittree\tree\type\spruce\ThinPine;
use pocketmine\utils\CloningRegistryTrait;
use pocketmine\world\generator\object\AcaciaTree;
use pocketmine\world\generator\object\BirchTree;
use pocketmine\world\generator\object\JungleTree;
use pocketmine\world\generator\object\OakTree;
use pocketmine\world\generator\object\SpruceTree;
use pocketmine\world\generator\object\Tree;

/**
 * List based on https://minecraft.wiki/w/Tree
 *
 * // Oak
 * @method static Tree OAK()
 * @method static Tree FANCY_OAK()
 * @method static Tree SWAMP_OAK()
 *
 * // Spruce
 * @method static Tree SPRUCE()
 * @method static Tree MEGA_SPRUCE() //TODO
 * @method static Tree THIN_PINE()
 * @method static Tree MEGA_PINE() //TODO
 *
 * // Birch
 * @method static Tree BIRCH()
 * @method static Tree TALL_BIRCH()
 *
 * // Jungle
 * @method static Tree JUNGLE()
 * @method static Tree MEGA_JUNGLE() //TODO
 * @method static Tree JUNGLE_BUSH() //TODO
 *
 * // Acacia
 * @method static Tree ACACIA()
 *
 * // Dark Oak
 * @method static Tree DARK_OAK() //TODO
 *
 * // Azalea
 * @method static Tree AZALEA() //TODO
 *
 * // Mangrove
 * @method static Tree MANGROVE() //TODO
 *
 * // Cherry
 * @method static Tree CHERRY() //TODO
 *
 * // Huge Fungus
 * @method static Tree HUGE_CRIMSON_FUNGUS() //TODO
 * @method static Tree HUGE_WARPED_FUNGUS() //TODO
 *
 * // Huge Mushroom
 * @method static Tree HUGE_RED_MUSHROOM() //TODO
 * @method static Tree HUGE_BROWN_MUSHROOM() //TODO
 */
class TreeType {
    use CloningRegistryTrait;

    private function __construct(){
    }

    protected static function register(string $name, Tree $tree): void{
        self::_registryRegister($name, $tree);
    }

    /**
     * @return Tree[]
     */
    public static function getAll() : array{
        return self::_registryGetAll();
    }

    protected static function setup(): void{
        self::register("oak", new OakTree());
        self::register("fancy_oak", new FancyOak());
        self::register("swamp_oak", new SwampOak());

        self::register("spruce", new SpruceTree());
        self::register("thin_pine", new ThinPine());
        //self::register("mega_pine", new MegaPine()); //wip

        self::register("birch", new BirchTree());
        self::register("tall_birch", new BirchTree(true));

        self::register("jungle", new JungleTree());

        self::register("acacia", new AcaciaTree());
    }
}
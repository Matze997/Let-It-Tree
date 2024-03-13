<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

use Closure;
use InvalidArgumentException;
use pocketmine\form\Form as IForm;
use pocketmine\player\Player;
use ReturnTypeWillChange;

abstract class Form implements IForm{

    /** @var array */
    protected array $data = [];
    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @throws InvalidArgumentException
     * @deprecated
     * @see Player::sendForm()
     */
    public function sendToPlayer(Player $player) : void {
        $player->sendForm($this);
    }

    public function getCallable() : ?callable {
        return $this->callable;
    }

    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    public function handleResponse(Player $player, $data) : void {
        $this->processData($data, $player);
        if($data instanceof Closure) {
            ($data)($player);

            $data = null;
        }
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    public function processData(&$data, Player $player) : void {
    }

    #[ReturnTypeWillChange] public function jsonSerialize(){
        return $this->data;
    }
}

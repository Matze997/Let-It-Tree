<?php

declare(strict_types = 1);

namespace jojoe77777\FormAPI;

class ModalForm extends Form {

    /** @var string */
    private string $content = "";

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["button1"] = "";
        $this->data["button2"] = "";
    }

    /**
     * @param string $title
     * @return ModalForm
     */
    public function setTitle(string $title) : self {
        $this->data["title"] = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return $this->data["content"];
    }

    /**
     * @param string $content
     * @return ModalForm
     */
    public function setContent(string $content) : self {
        $this->data["content"] = $content;
        return $this;
    }

    /**
     * @param string $text
     * @return ModalForm
     */
    public function setButton1(string $text) : self {
        $this->data["button1"] = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getButton1() : string {
        return $this->data["button1"];
    }

    /**
     * @param string $text
     * @return ModalForm
     */
    public function setButton2(string $text) : self {
        $this->data["button2"] = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getButton2() : string {
        return $this->data["button2"];
    }
}

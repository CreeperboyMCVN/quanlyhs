<?php
abstract class Parser {
    private $user;

    public function __construct($user) {
        $this->user = $user;
    }

    abstract public function parse():string;
}
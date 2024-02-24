<?php

namespace QuanLyHocSinh;

abstract class Parser {
    public $user;

    public function __construct($user) {
        if (!($user instanceof User)) {
            throw \Exception('Expected "User" but got "'.gettype($user)."'", 1000);
        }
        $this->user = $user;
    }

    abstract public function parse():string;
}
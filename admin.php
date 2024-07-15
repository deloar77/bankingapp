<?php

class Admin {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate($username, $password) {
        return $this->username === $username && $this->password === $password;
    }
}
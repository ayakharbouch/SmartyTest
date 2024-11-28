<?php

abstract class AbstractModel {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function createShop($data);
    abstract public function getShopById($id);
    abstract public function updateShop($data);
    abstract public function deleteShop($id);
}
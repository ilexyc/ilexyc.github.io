<?php

use \Phalcon\Mvc\Model;

class Items extends Model {

    public $id;

    public $name;

    public $image;

    public $cost;

    public $digital;

    public function initialize() {
        $this->setSource("items");
    }

    public static function CreateObject($data) {
        $object = new Items();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}
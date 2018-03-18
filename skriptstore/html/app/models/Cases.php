<?php

use \Phalcon\Mvc\Model;

class Cases extends Model {

    public $id;

    public $position;

    public $name;

    public $price;

    public $discount;

    public $chance;

    public $items;

    public $image;

    public $background_image;

    public $type;

    public function initialize() {
        $this->setSource("cases");
    }

    public static function CreateObject($data) {
        $object = new Cases();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}
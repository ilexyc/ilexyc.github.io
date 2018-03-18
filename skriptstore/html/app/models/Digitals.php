<?php

use \Phalcon\Mvc\Model;

class Digitals extends Model {

    public $id;

    public $name;

    public $code;

    public $reserved;

    public function initialize() {
        $this->setSource("digitals");
    }

    public static function CreateObject($data) {
        $object = new Digitals();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}
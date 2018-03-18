<?php

use \Phalcon\Mvc\Model;

class History extends Model {

    public $id;

    public $user_id;

    public $description;

    public $cost;

    public $time;

    public function initialize() {
        $this->setSource("history");
    }

    public static function CreateObject($data) {
        $object = new History();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}
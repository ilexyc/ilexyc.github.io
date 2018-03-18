<?php

use \Phalcon\Mvc\Model;

class Drops extends Model {

    public $id;

    public $user_id;

    public $case_id;

    public $item_id;

    public $price;

    public $time;

    public function initialize() {
        $this->setSource("drops");
    }

    public static function CreateObject($data) {
        $object = new Drops();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}
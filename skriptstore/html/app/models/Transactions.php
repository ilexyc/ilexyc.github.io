<?php

use \Phalcon\Mvc\Model;

class Transactions extends Model {

    public $id;

    public $user_id;

    public $service;
    public $ik_pm_no;
    public $cost;

    public $description;

    public $status;

    public $time;

    public function initialize() {
        $this->setSource("transactions");
    }

    public static function CreateObject($data) {
        $object = new Transactions();
        $object->assign($data);

        return $object;
    }

    public static function UpdateObject($object, $data) {
        $object->assign($data);

        return $object;
    }
}